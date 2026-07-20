<?php

namespace Modules\Shop\App\Services\Parse;

use Illuminate\Support\Str;
use Modules\Shop\App\Models\ShopAttribute;
use Modules\Shop\App\Models\ShopAttributeValue;
use Modules\Shop\App\Models\ShopCategory;
use Modules\Shop\App\Models\ShopProduct;
use Modules\Storage\App\Services\ImageService;
use Throwable;

/**
 * Сервис импорта каталога LiForce в базу данных магазина.
 *
 * Отвечает за:
 * - создание категорий
 * - создание и обновление товаров
 * - добавление атрибутов товаров
 * - добавление изображений
 *
 * Данные читаются из JSON каталога LiForce.
 */
class LiForceDBService
{
    /**
     * @param ImageService $imageService Сервис загрузки изображений
     */
    public function __construct(protected ImageService $imageService)
    {
    }

    /**
     * Добавляет атрибуты товару.
     *
     * Если атрибут уже существует:
     *  - проверяется наличие значения в dictionary
     *  - если его нет — добавляется
     *  - значение связывается с товаром
     *
     * Если атрибут не существует — он создается.
     *
     * @param callable $log Функция логирования
     * @param ShopProduct $product Товар
     * @param array<string,string> $attributesArr Массив атрибутов [name => value]
     *
     * @return void
     */
    private function addAttributesToProduct(callable $log, ShopProduct $product, array $attributesArr): void
    {
        if (count($attributesArr)) {
            foreach ($attributesArr as $name => $value) {
                $shopAttributes = ShopAttribute::query()->where('name', $name)->first();

                if ($shopAttributes) {
                    $dictionary = is_array($shopAttributes->dictionary)
                        ? $shopAttributes->dictionary
                        : json_decode($shopAttributes->dictionary, true) ?? [];

                    if (isset($dictionary['value'])) {
                        $dictionary = [$dictionary];
                    }

                    $existingValues = array_column($dictionary, 'value');

                    if (!in_array($value, $existingValues)) {
                        $dictionary[] = ['value' => $value];
                        $shopAttributes->dictionary = $dictionary;
                        $shopAttributes->save();

                        $log('ok', 'Добавлено значение: ' . $value . ' в атрибут: ' . $name);
                    }
                } else {
                    $shopAttributes = new ShopAttribute();
                    $shopAttributes['name'] = $name;
                    $shopAttributes['type'] = 'select';
                    $shopAttributes['dictionary'] = ['value' => $value];
                    $shopAttributes->save();

                    $log('ok', 'Создан атрибут: ' . $name . ' со значением: ' . $value);
                }

                $attributeValues = ShopAttributeValue::query()
                    ->where('attribute_id', $shopAttributes['id'])
                    ->where('product_id', $product['id'])
                    ->first();

                if (!$attributeValues) {
                    $attributeValues = new ShopAttributeValue();
                    $attributeValues['value'] = $value;
                    $attributeValues['attribute_id'] = $shopAttributes['id'];
                    $attributeValues['product_id'] = $product['id'];
                    $attributeValues->save();

                    $log('ok', 'Атрибут: ' . $name . ' со значением: ' . $value . ' добавлен товару: ' . $product['title']);
                }
            }
        }
    }

    /**
     * Добавляет изображения к товару.
     *
     * @param callable $log Функция логирования
     * @param ShopProduct $product Товар
     * @param array<int,string> $images Список путей к изображениям
     *
     * @return void
     *
     * @throws
     */
    private function addImagesToProduct(callable $log, ShopProduct $product, array $images): void
    {
        if (!$product->images()->first()) {
            foreach ($images as $image) {
                $imageFile = LiForceFile::getFileForDB($image);

                if ($imageFile) {
                    $this->imageService->upload([
                        'imageable_type' => ShopProduct::class,
                        'imageable_id' => $product['id'],
                        'group' => 'preview',
                        'image' => $imageFile,
                    ]);

                    $log('ok', 'Добавлено изображение: ' . $imageFile->getFilename());
                } else {
                    $log('warn', "файл не найден");
                }
            }
        }
    }

    /**
     * Добавляет или обновляет товар.
     *
     * @param callable $log Функция логирования
     * @param array $product Данные товара
     * @param int $categoryId ID категории
     *
     * @return void
     */
    private function addProduct(callable $log, array $product, int $categoryId): void
    {
        $productDB = ShopProduct::query()->where('alias', Str::slug($product['name']))->first();

        if (!$productDB) {
            $productDB = new ShopProduct();
            $productDB['title'] = $product['name'];
            $productDB['alias'] = Str::slug($product['name']);
            $productDB['article'] = $product['article'];
            $productDB['set'] = $product['set'];
            $productDB['warranty'] = $product['warranty'];
            $productDB['price'] = $product['price'];
            $productDB['description'] = $product['description'];
            $productDB['category_id'] = $categoryId;
            $productDB->save();

            $log('ok', 'Добавлен продукт: ' . $product['name']);
        } else {
            $log('ok', 'Обновление продукта: ' . $product['name']);
        }

        $this->addImagesToProduct($log, $productDB, $product['images']);
        $this->addAttributesToProduct($log, $productDB, $product['attributes']);
    }

    /**
     * Добавляет список товаров категории.
     *
     * @param callable $log Функция логирования
     * @param int $categoryId ID категории
     * @param array $products Список товаров
     *
     * @return void
     */
    private function addProducts(callable $log, int $categoryId, array $products): void
    {
        foreach ($products as $product) {
            try {
                $this->addProduct($log, $product, $categoryId);
            } catch (Throwable $e) {
                $log('warn', "Ошибка в продукте" . $product['name'] . ': ' . $e->getMessage());
            }
        }
    }

    /**
     * Создает категорию если она отсутствует.
     *
     * @param callable $log Функция логирования
     * @param array|string $category Данные категории или имя
     * @param int|null $rootId ID родительской категории
     *
     * @return ShopCategory
     */
    private function addCategory(callable $log, array|string $category, ?int $rootId = null): ShopCategory
    {
        $categoryName = $rootId ? $category : $category['name'];
        $categoryDB = ShopCategory::query()
            ->where('alias', Str::slug($categoryName))
            ->first();

        if (!$categoryDB) {
            $categoryDB = new ShopCategory();
            $categoryDB['title'] = $categoryName;
            $categoryDB['alias'] = Str::slug($categoryName);

            if ($rootId) {
                $categoryDB['parent_id'] = $rootId;
            }

            $categoryDB->save();

            $log('ok', ($rootId ? 'Главная категория' : 'Категория') . ' записана в бд: ' . $categoryDB['title']);
        }

        return $categoryDB;
    }

    /**
     * Импортирует весь каталог LiForce в базу данных.
     *
     * Читает JSON каталог и:
     * - создает категории
     * - создает товары
     * - добавляет атрибуты
     *
     * @param callable $log Функция логирования
     *
     * @return void
     */
    public function addDB(callable $log): void
    {
        $catalog = (new LiForceJson())->readJson('li-force_catalog');

        foreach ($catalog as $rootCategory) {
            $rootCategoryDB = $this->addCategory($log, $rootCategory);

            if (isset($rootCategory['childCategory']) && count($rootCategory['childCategory'])) {
                foreach ($rootCategory['childCategory'] as $name => $category) {
                    $categoryDB = $this->addCategory($log, $name, $rootCategoryDB['id']);
                    if (isset($category['products']) && count($category['products'])) {
                        $this->addProducts($log, $categoryDB['id'], $category['products']);
                    }
                }
            } else {
                if (isset($rootCategory['products']) && count($rootCategory['products'])) {
                    $this->addProducts($log, $rootCategoryDB['id'], $rootCategory['products']);
                }
            }
        }
    }
}
