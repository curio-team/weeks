<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum WeekType: string implements HasLabel
{
    case LES = 'lesweek';
    case BUFFER = 'bufferweek';
    case VAKANTIE = 'vakantie';

    public function naam(): string
    {
        return ucfirst($this->value);
    }

    public function getLabel(): ?string
    {
        return $this->naam();
    }
}
