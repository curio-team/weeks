<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $table = 'semesters';
    protected $casts = [
        'start' => 'date:Y-m-d',
        'eind'  => 'date:Y-m-d',
    ];

    public function schooljaar()
    {
        return $this->belongsTo(Schooljaar::class);
    }

    public function weeks()
    {
        return $this->hasMany(Week::class);
    }

    public function getNaamAttribute()
    {
        return $this->start->year . "-" . $this->volgorde;
    }

    public function getNaamKortAttribute()
    {
        $jaar = substr($this->start->year, 2, 2);
        return $jaar . $this->volgorde;
    }
}
