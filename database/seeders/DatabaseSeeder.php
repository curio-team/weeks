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
            'volgorde'  => 'sep',
            'start' => '2023-08-28',
            'eind'  => '2024-02-04'
        ]);

        $feb = Semester::create([
            'schooljaar_id' => Schooljaar::whereYear('start', 2023)->first()->id,
            'volgorde'  => 'feb',
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
    }
}
