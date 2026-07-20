<?php

namespace Modules\Shop\App\Services\Parse;

use Exception;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

/**
 * Класс для разбора каталога и продуктов с сайта Li-Force.ru
 *
 * Использует Symfony DomCrawler и HTTP-клиент Laravel для получения HTML-страниц.
 */
class LiForceParser
{
    /**
     * Базовый URL сайта
     */
    protected string $base = 'https://www.li-force.ru';

    /**
     * LiForceParser constructor.
     *
     * @param  LiForceFile  $file  Сервис для работы с файлами и изображениями
     */
    public function __construct(protected LiForceFile $file)
    {
    }

    /**
     * Получает объект Crawler для указанного URL
     *
     * @param  string  $url  URL страницы для разбора
     *
     * @throws Exception Если страница не загружена успешно
     */
    private function getCrawler(string $url): Crawler
    {
        $response = Http::timeout(20)->get($url);

        if (! $response->successful()) {
            throw new Exception('Failed: '.$url);
        }

        return new Crawler($response->body());
    }

    /**
     * Парсит категории и подкатегории (имя, ссылка, ссылка на изображение)
     *
     * @return array Массив категорий с подкатегориями и ссылками
     *
     * @throws
     */
    private function parseCategoriesLinks(): array
    {
        $crawler = $this->getCrawler($this->base.'/shop/index');

        $categories = [];

        $crawler->filter('.cat-item')->each(function (Crawler $node) use (&$categories) {
            $titleNode = $node->filter('.cat-item__title a');

            if (! $titleNode->count()) {
                return;
            }

            $name = trim($titleNode->text());
            $href = trim($titleNode->attr('href'));

            if (! str_starts_with($href, '/shop')) {
                return;
            }

            $url = $this->base.$href;

            /** Изображение категории */
            $image = null;
            if ($node->filter('img')->count()) {
                $image = $this->base.$node->filter('img')->attr('src');
            }

            $category = [
                'name' => $name,
                'url' => $url,
                'image' => $image,
                'childCategory' => [],
            ];

            /** Подкатегории */
            if ($node->filter('.cat-item__child a')->count()) {
                $node->filter('.cat-item__child a')->each(function (Crawler $child) use (&$category) {
                    $childName = trim($child->text());
                    $childHref = trim($child->attr('href'));

                    if (str_starts_with($childHref, '/shop')) {
                        $category['childCategory'][$childName] = [
                            'url' => $this->base.$childHref,
                        ];
                    }
                });
            }

            $categories[] = $category;
        });

        return $categories;
    }

    /**
     * Парсит все ссылки на продукты
     *
     * @param  string  $url  Ссылка на категорию
     * @return array Массив уникальных ссылок на продукты
     *
     * @throws
     */
    private function parseProductsFromCategoryLinks(string $url): array
    {
        $products = [];

        $crawler = $this->getCrawler($url);

        $lastPage = 1;

        if ($crawler->filter('.pagination a')->count()) {
            $pages = $crawler->filter('.pagination a')->each(function (Crawler $node) {
                $href = $node->attr('href');

                if (preg_match('/page=(\d+)/', $href, $m)) {
                    return (int) $m[1];
                }

                return 1;
            });

            $lastPage = max($pages);
        }

        for ($page = 1; $page <= $lastPage; $page++) {

            $pageUrl = $url.(str_contains($url, '?') ? '&' : '?').'page='.$page;

            $crawler = $this->getCrawler($pageUrl);

            $links = $crawler
                ->filter('.product__wrapper > a')
                ->each(function (Crawler $node) {

                    $href = $node->attr('href');

                    if (str_starts_with($href, '/shop/view/')) {
                        return $this->base.$href;
                    }

                    return null;
                });

            $links = array_filter($links);

            $products = array_merge($products, $links);
        }

        return array_unique($products);
    }

    /**
     * Формирует дерево из категорий, подкатегорий и ссылок на продукты (первый этап обработки)
     *
     * @param  callable  $log  Функция логирования: function(string $level, string $message)
     * @return array Массив категорий с подкатегориями и ссылками на продукты
     */
    public function getCatalogArrayLinks(callable $log): array
    {
        $categories = $this->parseCategoriesLinks();
        /** Удаление 12 элемента так как битая ссылка ведет на 404 */
        unset($categories[12]);

        foreach ($categories as $key => $category) {
            try {
                if (count($category['childCategory'])) {
                    foreach ($category['childCategory'] as $childName => $child) {
                        $products = $this->parseProductsFromCategoryLinks($child['url']);
                        $categories[$key]['childCategory'][$childName]['products'] = $products;
                        $log('ok', "Подкатегория: $childName (".count($products).' продуктов)');
                    }

                } else {
                    $products = $this->parseProductsFromCategoryLinks($category['url']);
                    $categories[$key]['products'] = $products;
                    $log('ok', "Категория: {$category['name']} (".count($products).' продуктов)');
                }
            } catch (Throwable $e) {
                $log('warn', "Категория пропущена ({$category['name']}) Ошибка: ".$e->getMessage());
            }
        }

        return $categories;
    }

    /**
     * Нормализует HTML, заменяя относительные пути на абсолютные
     *
     * @param  string  $html  HTML-код для нормализации
     * @return string Нормализованный HTML
     */
    private function normalizeHtml(string $html): string
    {
        $html = preg_replace('/style="[^"]*"/i', '', $html);
        $html = preg_replace_callback('/(src|href)="(\/[^"]*)"/i', function ($matches) {
            return $matches[1].'="'.$this->base.$matches[2].'"';
        }, $html);
        $html = preg_replace('/<p>\s*<\/p>/i', '', $html);

        return trim($html);
    }

    /**
     * Получает данные продукта (имя, цена, атрибуты и тд)
     *
     * @param  string  $productLink  Ссылка на продукт
     * @return array Ассоциативный массив с данными продукта:
     *               - name: string|null
     *               - article: string|null
     *               - price: float|null
     *               - set: string|null
     *               - warranty: string|null
     *               - files: array
     *               - images: array
     *               - description: string|null
     *               - attributes: array
     *
     * @throws
     */
    private function getProduct(string $productLink): array
    {
        $crawler = $this->getCrawler($productLink);

        /**
         * Название товара
         */
        $name = $crawler->filter('h1[itemprop="name"]')->count()
            ? trim($crawler->filter('h1[itemprop="name"]')->text())
            : null;

        /**
         * Артикул
         */
        $article = null;
        if ($crawler->filter('.pv__article b')->count()) {
            $article = trim($crawler->filter('.pv__article b')->text());
        }

        /**
         * Цена
         */
        $price = null;
        if ($crawler->filter('.prices [itemprop="price"]')->count()) {
            $price = (float) $crawler->filter('.prices [itemprop="price"]')->attr('content');
        }

        /**
         * Комплектация
         */
        $set = $crawler->filterXPath('//div[.//span[text()="Комплектация"]]/following-sibling::div')->count()
            ? trim($crawler->filterXPath('//div[.//span[text()="Комплектация"]]/following-sibling::div')->text())
            : null;

        /**
         * Гарантия
         */
        $warranty = $crawler->filterXPath('//div[.//span[text()="Гарантия"]]/following-sibling::div')->count()
            ? trim($crawler->filterXPath('//div[.//span[text()="Гарантия"]]/following-sibling::div')->text())
            : null;

        /**
         * Ссылки на файлы
         */
        $files = [];
        $node = $crawler->filterXPath('//div[contains(@class,"pv-main__title")][span[text()="Файлы"]]/following-sibling::a[@class="sert"]');
        if ($node->count()) {
            $node->each(function (Crawler $a) use (&$files) {
                $href = $a->attr('href');
                if ($href) {
                    $files[] = $this->base.$href;
                }
            });
        }

        /**
         * Ссылки на изображения (вся галерея)
         */
        $images = [];
        $crawler->filter('.goods-photos-slider a.fancy')->each(function (Crawler $node) use (&$images) {
            $href = $node->attr('href');
            if ($href) {
                $images[] = $this->base.$href;
            }
        });

        /**
         * Описание
         */
        $description = null;

        if ($crawler->filter('#desc-tab')->count()) {
            $description = $this->normalizeHtml($crawler->filter('#desc-tab')->html());
        }

        /**
         * Характеристики
         */
        $attributes = [];
        $crawler->filter('.char-table tr')->each(function (Crawler $tr) use (&$attributes) {
            $cols = $tr->filter('td');
            if ($cols->count() == 2) {
                $key = trim($cols->eq(0)->text());
                $value = trim($cols->eq(1)->text());
                if ($key && $value) {
                    $attributes[$key] = $value;
                }
            }
        });

        return [
            'name' => $name,
            'article' => $article,
            'price' => $price,
            'set' => $set,
            'warranty' => $warranty,
            'files' => $files,
            'images' => $images,
            'description' => $description,
            'attributes' => $attributes,
        ];
    }

    /**
     * Получает продукты по ссылкам
     *
     * @param  callable  $log  Функция логирования
     * @param  array  $productsLinks  Массив ссылок на продукты
     * @return array Массив данных продуктов
     */
    private function getProducts(callable $log, array $productsLinks): array
    {
        $products = [];

        foreach ($productsLinks as $key => $productLink) {
            try {
                $products[$key] = $this->getProduct($productLink);
                $log('ok', 'Добавлен продукт: '.$products[$key]['name']);
            } catch (Throwable $e) {
                $log('warn', "Продукт пропущен ($productLink) Ошибка: " . $e->getMessage());
            }
        }

        return $products;
    }

    /**
     * Создает удобный массив данных (категории, подкатегории, продукты со всеми характеристиками) для дальнейшей записи в бд
     *
     * @param  callable  $log  Функция логирования
     * @param  array  $catalogLinks  Массив категорий со ссылками на продукты
     * @return array Полный каталог для записи в БД
     */
    public function getCatalog(callable $log, array $catalogLinks): array
    {
        if (! $catalogLinks) {
            return [];
        }

        $catalog = [];

        foreach ($catalogLinks as $key => $category) {
            try {
                $catalog[$key] = [
                    'name' => $category['name'],
                    'image_url' => $category['image'],
                ];

                if (count($category['childCategory'])) {
                    foreach ($category['childCategory'] as $childName => $child) {
                        $products = $this->getProducts($log, $child['products']);
                        $catalog[$key]['childCategory'][$childName]['products'] = $products;
                    }
                } else {
                    $products = $this->getProducts($log, $category['products']);
                    $catalog[$key]['products'] = $products;
                }
            } catch (Throwable $e) {
                $log('warn', "Категория пропущена ({$category['name']}) Ошибка: ".$e->getMessage());
            }
        }

        return $catalog;
    }

    /**
     * Загрузка изображений и файлов
     *
     * @param  callable  $log  Функция логирования
     * @param  array  $catalog  Каталог с продуктами
     */
    public function getFiles(callable $log, array $catalog): void
    {
        foreach ($catalog as $category) {
            try {
                if (count($category['childCategory'])) {
                    foreach ($category['childCategory'] as $child) {
                        $this->file->getFiles($log, $child['products']);
                    }
                } else {
                    $this->file->getFiles($log, $category['products']);
                }
            } catch (Throwable $e) {
                $log('warn', "Категория пропущена ({$category['name']}) Ошибка: ".$e->getMessage());
            }
        }
    }
}
