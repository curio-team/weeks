<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schooljaar extends Model
{
    protected $table = 'schooljaren';

    protected function casts(): array
    {
        return [
            'start' => 'date:Y-m-d',
            'eind' => 'date:Y-m-d',
        ];
    }

    public function getNaamAttribute()
    {
        return "Schooljaar {$this->start->format('y')}-{$this->eind->format('y')}";
    }

    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class);
    }
}
