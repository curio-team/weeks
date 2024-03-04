<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Schooljaar;
use App\Models\Semester;
use App\Models\Week;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        for($i = 2023; $i <= 2029; $i++)
        {
            $j = $i + 1;
            Schooljaar::create([
                'start' => "$i-08-01",
                'eind'  => "$j-07-31"
            ]);
        }

        $sep = Semester::create([
            'schooljaar_id' => Schooljaar::whereYear('start', 2023)->first()->id,
            'volgorde'  => 1,
            'start' => '2023-08-28',
            'eind'  => '2024-02-04'
        ]);

        $feb = Semester::create([
            'schooljaar_id' => Schooljaar::whereYear('start', 2023)->first()->id,
            'volgorde'  => 2,
            'start' => '2024-02-05',
            'eind'  => '2024-07-07'
        ]);

        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2023-08-28',
            'nummer'        => 0,
            'naam'          => 'Introductieweek',
            'type'          => 'bufferweek'
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2023-09-04',
            'nummer'        => 1
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2023-09-11',
            'nummer'        => 2
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2023-09-18',
            'nummer'        => 3
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2023-09-25',
            'nummer'        => 4
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2023-10-02',
            'nummer'        => 5
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2023-10-09',
            'nummer'        => 6
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2023-10-16',
            'nummer'        => 6,
            'naam'          => 'Herfstvakantie',
            'type'          => 'vakantie',
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2024-01-15',
            'nummer'        => 16,
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2024-01-22',
            'nummer'        => 17,
            'naam'          => 'Bufferweek',
            'type'          => 'bufferweek',
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2024-01-29',
            'nummer'        => 17,
            'naam'          => 'Bufferweek',
            'type'          => 'bufferweek',
        ]);

        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-02-05',
            'nummer'        => 1,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-02-12',
            'nummer'        => 1,
            'naam'          => 'Voorjaarsvakantie',
            'type'          => 'vakantie',
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-02-19',
            'nummer'        => 2,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-02-26',
            'nummer'        => 3,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-03-04',
            'nummer'        => 4,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-03-11',
            'nummer'        => 5,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-03-18',
            'nummer'        => 6,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-03-25',
            'nummer'        => 7,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-04-01',
            'nummer'        => 8,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-04-08',
            'nummer'        => 9,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-04-15',
            'nummer'        => 10,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-04-22',
            'nummer'        => 10,
            'naam'          => 'Meivakantie',
            'type'          => 'vakantie'
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-04-29',
            'nummer'        => 10,
            'naam'          => 'Meivakantie',
            'type'          => 'vakantie'
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-05-06',
            'nummer'        => 11,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-05-13',
            'nummer'        => 12,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-05-20',
            'nummer'        => 13,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-05-27',
            'nummer'        => 14,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-06-03',
            'nummer'        => 15,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-06-10',
            'nummer'        => 16,
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-06-17',
            'nummer'        => 17,
            'naam'          => 'Bufferweek',
            'type'          => 'bufferweek',
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-06-24',
            'nummer'        => 17,
            'naam'          => 'Bufferweek',
            'type'          => 'bufferweek',
        ]);
        Week::create([
            'semester_id'   => $feb->id,
            'maandag'       => '2024-07-01',
            'nummer'        => 17,
            'naam'          => 'Diplomeringsweek',
            'type'          => 'bufferweek',
        ]);



        ////////////////



        $sep = Semester::create([
            'schooljaar_id' => Schooljaar::whereYear('start', 2023)->first()->id,
            'volgorde'  => 1,
            'start' => '2023-08-28',
            'eind'  => '2024-04-07',
            'cohort'        => 21
        ]);
        $feb = Semester::create([
            'schooljaar_id' => Schooljaar::whereYear('start', 2023)->first()->id,
            'volgorde'  => 2,
            'start' => '2024-04-08',
            'eind'  => '2024-07-07',
            'cohort'        => 21
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2024-02-05',
            'nummer'        => 17,
            'cohort'        => 21
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2024-02-12',
            'nummer'        => 17,
            'naam'          => 'Voorjaarsvakantie',
            'type'          => 'vakantie',
            'cohort'        => 21
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2024-02-19',
            'nummer'        => 18,
            'cohort'        => 21
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2024-02-26',
            'nummer'        => 19,
            'cohort'        => 21
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2024-03-04',
            'nummer'        => 20,
            'cohort'        => 21
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2024-03-11',
            'nummer'        => 21,
            'cohort'        => 21
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2024-03-18',
            'nummer'        => 22,
            'cohort'        => 21
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2024-03-25',
            'nummer'        => 23,
            'cohort'        => 21
        ]);
        Week::create([
            'semester_id'   => $sep->id,
            'maandag'       => '2024-04-01',
            'nummer'        => 24,
            'cohort'        => 21
        ]);
    }
}
