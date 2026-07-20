<?php

namespace Modules\Shop\App\Services\Parse;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Class LiForceFile
 *
 * Сервис для скачивания файлов и изображений товаров.
 *
 * Файлы сохраняются в папки:
 * - parse/files — для документов
 * - parse/images — для изображений
 *
 * Использует callable $log для логирования действий.
 */
class LiForceFile
{
    /**
     * Скачивает один файл или изображение и сохраняет его в соответствующую папку.
     *
     * @param  callable  $log  Функция логирования: fn(string $type, string $message)
     * @param  string  $fileLink  URL файла или изображения (может быть относительным)
     * @param  string  $type  Тип файла: 'file' для документов, 'image' для изображений (по умолчанию 'image')
     */
    private function getFile(callable $log, string $fileLink, string $type = 'image'): void
    {
        $folder = $type === 'file' ? 'parse/files' : 'parse/images';
        $filename = basename(parse_url($fileLink, PHP_URL_PATH));
        $path = "$folder/$filename";

        if (Storage::exists($path)) {
            $log('info', $type === 'file' ? "Файл уже существует: $filename" : "Изображение уже существует: $filename");
            return;
        }

        try {
            $response = Http::timeout(20)->get($fileLink);

            if ($response->successful()) {
                Storage::put($path, $response->body());
            }

            $log('ok', $type === 'file' ? 'Сохранен файл: '.$filename : 'Сохранено изображение: '.$filename);
        } catch (Throwable $e) {
            $log('warn', "Файл не найден или таймаут: $fileLink. Ошибка: ".$e->getMessage());
        }
    }

    /**
     * Скачивает все файлы и изображения для массива товаров.
     *
     * Каждый товар должен иметь ключи:
     * - 'files' => массив ссылок на документы
     * - 'images' => массив ссылок на изображения
     * - 'name' => название товара (для логирования)
     *
     * @param  callable  $log  Функция логирования: fn(string $type, string $message)
     * @param  array  $products  Массив товаров
     */
    public function getFiles(callable $log, array $products): void
    {
        foreach ($products as $product) {
            $log('info', 'Загрузка файлов у продукта: '.$product['name']);

            if (count($product['files'])) {
                foreach ($product['files'] as $file) {
                    $this->getFile($log, $file, 'file');
                }
            }

            if (count($product['images'])) {
                foreach ($product['images'] as $image) {
                    $this->getFile($log, $image);
                }
            }
        }
    }

    /**
     * Получает файл изображения из локального хранилища для записи в БД.
     *
     * Метод извлекает имя файла из ссылки, формирует путь к файлу
     * в директории `parse/images/` и проверяет его существование.
     *
     * Если файл найден — возвращается объект File.
     * Если файл отсутствует — возвращается false.
     *
     * @param string $pathLink Ссылка или путь к изображению
     *
     * @return File|false
     */
    public static function getFileForDB(string $pathLink): File|false
    {
        $filename = basename(parse_url($pathLink, PHP_URL_PATH));
        $path = "parse/images/$filename";

        if (!Storage::exists($path)) {
            return false;
        }

        return new File(Storage::path($path));
    }
}
