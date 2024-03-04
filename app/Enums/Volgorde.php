<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Volgorde: int implements HasLabel{
    case SEP = 1;
    case FEB = 2;

    public function naam(): string
    {
        return match($this)
        {
            self::SEP => 'sep',
            self::FEB => 'feb'
        };
    }

    public function naamLang(): string
    {
        return match($this)
        {
            self::SEP => 'september',
            self::FEB => 'februari'
        };
    }

    public function naamExtraLang(): string
    {
        return "{$this->value}e semester / {$this->naamLang()}";
    }

    public function getLabel(): ?string
    {
        return $this->naamExtraLang();
    }
}
