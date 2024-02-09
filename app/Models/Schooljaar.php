<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schooljaar extends Model
{
    protected $table = 'schooljaren';
    protected $casts = [
        'start' => 'date:Y-m-d',
        'eind'  => 'date:Y-m-d',
    ];

    public function getNaamAttribute()
    {
        return "Schooljaar {$this->start->format('y')}-{$this->eind->format('y')}";
    }
}
