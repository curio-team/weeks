<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Models\Week;
use App\Models\Semester;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//
// FULLCALENDAR OM TE DISPLAYEN IN BROWSER?!
//

Route::get('/', function () {
    $weeks = Week::getCurrentWeeks();
    return Week::prepareData($weeks);
});

Route::get('/cohort/{cohort}', function ($cohort) {
    $weeks = Week::getCurrentWeeks($cohort);
    return Week::prepareData($weeks);
});

Route::get('/only/{type}', fn($type) => getOnly($type));
Route::get('/cohort/{cohort}/only/{type}', fn ($cohort, $type) => getOnly($type, $cohort));

function getOnly($type, $cohort = null)
{
    $weeks = Week::getCurrentWeeks($cohort);
    $data  = Week::prepareData($weeks);

    switch ($type) {
        case 'week':
            return $data['week']->nummer;

        default:
            return ['error' => ['code' => 3, 'text' => 'ongeldig type']];
    }
}

Route::get('/list', function (Request $request) {
    
    $result = array();
    
    $weeks = Week::whereDate('maandag', '>=', $request->start)->whereDate('maandag', '<=', $request->end)->get();
    foreach($weeks as $week)
    {
        $title = "<strong>Week $week->nummer</strong>";
        if($week->naam) $title = "Week $week->nummer - <strong>$week->naam</strong>";
        if($week->cohort) $title = "C$week->cohort: $title";
        $result[] = [
            'id' => 'w' . $week->id,
            'allDay' => true,
            'start' => $week->maandag,
            'end' => $week->maandag->addDays(5),
            'title' => $title,
            'color' => '#48486e',
            'textColor' => '#ededf7',
            'cohort' => $week->cohort ?? 0
        ];
    }

    $semesters = Semester::whereDate('start', '<=', $request->end)->whereDate('eind', '>=', $request->start)->get();
    foreach($semesters as $semester)
    {
        $title = "<em>Blok $semester->naam_kort / {$semester->schooljaar->naam}</em>";
        if($semester->cohort) $title = "C$semester->cohort: $title";
        $result[] = [
            'id' => 's' . $semester->id,
            'allDay' => true,
            'start' => $semester->start,
            'end' => $semester->eind,
            'title' => $title,
            'color' => '#c1d6ca',
            'textColor' => '#626e67',
            'cohort' => (100 + $semester->cohort) ?? 100
        ];
    }

    return $result;
});
