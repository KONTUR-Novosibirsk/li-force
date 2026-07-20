<?php

namespace Modules\Shop\App\Services\Parse;

use Illuminate\Support\Facades\Storage;

class LiForceJson
{
    /**
     * Сохранить данные каталога в JSON файл.
     *
     * @param  array  $data  Данные для сохранения
     * @param  string  $name  Имя файла
     */
    public function saveJson(array $data, string $name): void
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        Storage::put('parse/'.$name.'.json', $json);
    }

    /**
     * Прочитать JSON файл каталога и вернуть массив данных.
     *
     * @param  string  $name  Имя файла
     */
    public function readJson(string $name): array
    {
        $path = 'parse/'.$name.'.json';

        if (! Storage::exists($path)) {
            return [];
        }

        $json = Storage::get($path);

        $data = json_decode($json, true);

        return is_array($data) ? $data : [];
    }
}
