<?php

use Illuminate\Support\Facades\Facade;

return [

    'dev' => [
        'offset_weeks' => env('DEV_OFFSET_WEEKS', null),
    ],

    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
    ])->toArray(),

];
