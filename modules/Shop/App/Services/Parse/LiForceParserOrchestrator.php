<?php

namespace Modules\Shop\App\Services\Parse;

class LiForceParserOrchestrator
{
    public function __construct(protected LiForceParser $parser, protected LiForceJson $json)
    {
    }

    public function run(callable $log): void
    {
        $catalogLinks = $this->parser->getCatalogArrayLinks($log);
        $this->json->saveJson($catalogLinks, 'li-force_catalog_links');

        $log('info', "Данные каталога с ссылками на продукты сохранены.");
        $log('info', "Запуск загрузки данных продукта ...");

        $catalogLinks = $this->json->readJson('li-force_catalog_links');
        $catalog = $this->parser->getCatalog($log, $catalogLinks);
        $this->json->saveJson($catalog, 'li-force_catalog');

        $log('info', "Полный каталог сохранен.");
        $log('info', "Загрузка изображений и файлов ...");

        $catalog = $this->json->readJson('li-force_catalog');
        $this->parser->getFiles($log, $catalog);

        $log('info', "Изображения и файлы загружены.");
    }
}
