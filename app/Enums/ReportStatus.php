<?php

namespace App\Enums;

enum ReportStatus: string
{
    case Pending = 'pending';
    case Reviewed = 'reviewed';
    case Dismissed = 'dismissed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::Reviewed => 'Ditinjau',
            self::Dismissed => 'Ditolak',
        };
    }
}
