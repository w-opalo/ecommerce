<?php

namespace App\Enums;

enum ProductStatusEnum: string
{
    case Draft = 'draft';
    case Published = 'published';

    public static function labels(): array
    {
        return [
            self::Draft->value => 'Draft',
            self::Published->value => 'Published',
        ];
    }

    public static function colors(): array
    {
        return [
            'grey' => self::Draft->value,
            'success' => self::Published->value,
        ];
    }
}
