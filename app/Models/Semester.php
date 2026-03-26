<?php

namespace App\Models;

use App\Enums\Volgorde;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('semesters')]
class Semester extends Model
{
    protected function casts(): array
    {
        return [
            'start' => 'date:Y-m-d',
            'eind' => 'date:Y-m-d',
            'volgorde' => Volgorde::class,
        ];
    }

    public function schooljaar(): BelongsTo
    {
        return $this->belongsTo(Schooljaar::class);
    }

    public function weeks(): HasMany
    {
        return $this->hasMany(Week::class);
    }

    public function getNaamAttribute()
    {
        return $this->start->year.'-'.$this->volgorde->naam();
    }

    public function getNaamKortAttribute()
    {
        $jaar = substr($this->start->year, 2, 2);

        return $jaar.$this->volgorde->naam();
    }
}
