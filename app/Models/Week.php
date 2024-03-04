<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Week extends Model
{
    protected $table = 'weken';
    protected $casts = [
        'maandag' => 'date:Y-m-d'
    ];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public static function getCurrentWeeks($cohort = null)
    {
        $maandag = now()->startOfWeek(Carbon::MONDAY);

        if($cohort) {
            $week = Week::whereDate('maandag', $maandag)->where('cohort', $cohort)->get();
            if($week->count() > 0) return $week;
        }

        return Week::whereDate('maandag', $maandag)->get();
    }

    public static function prepareData($weeks)
    {
        // Als we een exacte match hebben, geef die terug;
        if(count($weeks) >= 1) {
            $week = $weeks->first();
            $semester = $week->semester;
            $schooljaar = $semester->schooljaar;

            unset($week->id, $week->semester_id, $week->created_at, $week->updated_at, $week->semester);
            unset($semester->id, $semester->schooljaar_id, $semester->created_at, $semester->updated_at, $semester->schooljaar);
            unset($schooljaar->id, $schooljaar->created_at, $schooljaar->updated_at);
            unset($week->cohort, $semester->cohort);

            return [
                "week" => $week,
                "semester" => $semester,
                "schooljaar" => $schooljaar
            ];
        }
        elseif(count($weeks) < 1) return ['error' => ['code' => 1, 'text' => 'geen week gevonden']];
    }
}
