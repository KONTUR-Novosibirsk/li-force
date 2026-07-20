<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Shop\App\Services\Parse\LiForceDBOrchestrator;
use Modules\Shop\App\Services\Parse\LiForceParserOrchestrator;

/**
 * Class ParseLiForce
 *
 * Консольная команда для запуска разбора сайта Li-Force.
 *
 * Команда инициирует оркестратор, который:
 * - парсит категории
 * - парсит товары
 * - сохраняет данные
 *
 * Запуск:
 * php artisan parse:li-force
 */
class ParseLiForce extends Command
{
    /**
     * Имя и сигнатура команды Artisan.
     *
     * @var string
     */
    protected $signature = 'parse:li-force';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Разбор сайта Li-Force';

    /**
     * Выполнение команды.
     *
     * @param  LiForceParserOrchestrator  $service  Оркестратор процесса разбора
     */
    public function handle(LiForceParserOrchestrator $service, LiForceDBOrchestrator $db): void
    {
        $this->infoTag('Парс категорий и продуктов ...');

        $service->run(function ($type, $message) {
            match ($type) {
                'ok' => $this->okTag($message),
                'warn' => $this->warnTag($message),
                'info' => $this->infoTag($message),
            };
        });

        $this->infoTag('Парс закончен.');
        $this->infoTag('Сохранение данных в бд ...');

        $db->run(function ($type, $message) {
            match ($type) {
                'ok' => $this->okTag($message),
                'warn' => $this->warnTag($message),
                'info' => $this->infoTag($message),
            };
        });

        $this->infoTag('Сохранение данных в бд закончено.');
    }

    /**
     * Вывод информационного сообщения в консоль.
     *
     * Используется для отображения общего статуса выполнения
     * команды (например: запуск разбора, этапы процесса).
     *
     * Пример вывода:
     * [INFO] Parsing categories
     *
     * @param  string  $text  Текст сообщения
     */
    private function infoTag(string $text): void
    {
        $this->line("<fg=cyan>[INFO]</> $text");
    }

    /**
     * Вывод сообщения об успешном выполнении операции.
     *
     * Используется для отображения успешно обработанных
     * элементов (например: категория или товар).
     *
     * Пример вывода:
     * [OK] Category: Batteries (128 products)
     *
     * @param  string  $text  Текст сообщения
     */
    private function okTag(string $text): void
    {
        $this->line("<fg=green>[OK]</>   $text");
    }

    /**
     * Вывод предупреждения в консоль.
     *
     * Используется для отображения пропущенных или
     * проблемных элементов (например: категория с ошибкой 404).
     *
     * Пример вывода:
     * [WARN] Category skipped (404)
     *
     * @param  string  $text  Текст предупреждения
     */
    private function warnTag(string $text): void
    {
        $this->line("<fg=yellow>[WARN]</> $text");
    }
}
