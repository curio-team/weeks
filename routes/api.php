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

Route::get('/only/{type}', function ($type) {
    $weeks = Week::getCurrentWeeks();
    $data  = Week::prepareData($weeks);

    switch ($type) {
        case 'week':
            return $data['week']->nummer;

        default:
            return ['error' => ['code' => 3, 'text' => 'ongeldig type']];
    }
});

Route::get('/list', function (Request $request) {
    
    $result = array();
    
    $weeks = Week::whereDate('maandag', '>=', $request->start)->whereDate('maandag', '<=', $request->end)->get();
    foreach($weeks as $week)
    {
        $title = "<strong>Week $week->nummer</strong>";
        if($week->naam) $title = "Week $week->nummer - <strong>$week->naam</strong>";
        $result[] = [
            'id' => 'w' . $week->id,
            'allDay' => true,
            'start' => $week->maandag,
            'end' => $week->maandag->addDays(5),
            'title' => $title,
            'color' => '#48486e',
            'textColor' => '#ededf7'
        ];
    }

    $semesters = Semester::whereDate('start', '<=', $request->end)->whereDate('eind', '>=', $request->start)->get();
    foreach($semesters as $semester)
    {
        $result[] = [
            'id' => 's' . $semester->id,
            'allDay' => true,
            'start' => $semester->start,
            'end' => $semester->eind,
            'title' => "<em>Blok $semester->naam_kort / {$semester->schooljaar->naam}</em>",
            'color' => '#c1d6ca',
            'textColor' => '#626e67'
        ];
    }

    return $result;
});