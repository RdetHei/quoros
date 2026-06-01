<?php

namespace App\Enums;

enum ReportReason: string
{
    case Spam = 'spam';
    case Harassment = 'harassment';
    case Copyright = 'copyright';
    case Inappropriate = 'inappropriate';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Spam => 'Spam',
            self::Harassment => 'Harassment / Intimidation',
            self::Copyright => 'Copyright Infringement',
            self::Inappropriate => 'Inappropriate Content',
            self::Other => 'Other',
        };
    }
}
