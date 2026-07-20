<?php

namespace Modules\Reviews\App\Enums;

enum ReviewSource: string
{
    case Ozon = 'ozon';
    case Wb = 'wb';

    public function label(): string
    {
        return match ($this) {
            self::Ozon => 'Ozon',
            self::Wb => 'Wildberries',
        };
    }

    public static function options(): array
    {
        return array_combine(
            array_map(fn ($case) => $case->value, self::cases()),
            array_map(fn ($case) => $case->label(), self::cases())
        );
    }
}
