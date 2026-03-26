<?php

namespace Tests\Feature;

use App\Enums\Volgorde;
use App\Enums\WeekType;
use App\Models\Schooljaar;
use App\Models\Semester;
use App\Models\Week;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SemesterApiTest extends TestCase
{
    use RefreshDatabase;

    private function createSchoolYearAndSemester(string $yearStart, string $yearEnd, string $semStart, string $semEnd, Volgorde $volgorde = Volgorde::SEP, ?int $cohort = null): Semester
    {
        $schooljaar = Schooljaar::create([
            'start' => $yearStart,
            'eind' => $yearEnd,
        ]);

        return Semester::create([
            'schooljaar_id' => $schooljaar->id,
            'volgorde' => $volgorde,
            'start' => $semStart,
            'eind' => $semEnd,
            'cohort' => $cohort,
        ]);
    }

    public function test_root_endpoint_returns_current_week_semester_and_schoolyear(): void
    {
        Carbon::setTestNow('2026-03-23 12:00:00'); // Monday

        $semester = $this->createSchoolYearAndSemester(
            '2025-08-01',
            '2026-07-31',
            '2026-02-01',
            '2026-06-30',
            Volgorde::FEB
        );

        Week::create([
            'semester_id' => $semester->id,
            'maandag' => '2026-03-23',
            'nummer' => 5,
            'type' => WeekType::LES,
        ]);

        $response = $this->getJson('/api/');

        $response->assertOk()
            ->assertJsonStructure([
                'week' => ['maandag', 'nummer', 'type'],
                'semester' => ['start', 'eind', 'volgorde'],
                'schooljaar' => ['start', 'eind'],
            ])
            ->assertJsonPath('week.maandag', '2026-03-23')
            ->assertJsonPath('week.nummer', 5)
            ->assertJsonPath('week.type', WeekType::LES->value)
            ->assertJsonPath('semester.start', '2026-02-01')
            ->assertJsonPath('semester.eind', '2026-06-30')
            ->assertJsonPath('schooljaar.start', '2025-08-01')
            ->assertJsonPath('schooljaar.eind', '2026-07-31');
    }

    public function test_only_week_endpoint_returns_current_week_number(): void
    {
        Carbon::setTestNow('2026-03-23 08:00:00');

        $semester = $this->createSchoolYearAndSemester(
            '2025-08-01',
            '2026-07-31',
            '2026-02-01',
            '2026-06-30',
            Volgorde::FEB
        );

        Week::create([
            'semester_id' => $semester->id,
            'maandag' => '2026-03-23',
            'nummer' => 9,
            'type' => WeekType::LES,
        ]);

        $response = $this->get('/api/only/week');

        $response->assertOk();
        $this->assertSame('9', trim($response->getContent()));
    }

    public function test_cohort_specific_week_overrides_default_week(): void
    {
        Carbon::setTestNow('2026-03-23 10:00:00');

        $semester = $this->createSchoolYearAndSemester(
            '2025-08-01',
            '2026-07-31',
            '2026-02-01',
            '2026-06-30',
            Volgorde::FEB
        );

        // Default week for everyone
        Week::create([
            'semester_id' => $semester->id,
            'maandag' => '2026-03-23',
            'nummer' => 3,
            'type' => WeekType::LES,
            'cohort' => null,
        ]);

        // Cohort-specific override for cohort 42 on same Monday
        Week::create([
            'semester_id' => $semester->id,
            'maandag' => '2026-03-23',
            'nummer' => 7,
            'type' => WeekType::LES,
            'cohort' => 42,
        ]);

        $this->getJson('/api/')
            ->assertOk()
            ->assertJsonPath('week.nummer', 3);

        $this->getJson('/api/cohort/42')
            ->assertOk()
            ->assertJsonPath('week.nummer', 7);
    }

    public function test_list_endpoint_returns_weeks_and_semesters_with_expected_titles_and_colors(): void
    {
        // Create a semester that is within the requested list range
        $semester = $this->createSchoolYearAndSemester(
            '2025-08-01',
            '2026-07-31',
            '2026-03-01',
            '2026-04-30',
            Volgorde::FEB
        );

        // Three consecutive Mondays with different week types
        Week::create([
            'semester_id' => $semester->id,
            'maandag' => '2026-03-16', // buffer (no name) -> title should use "Bufferweek"
            'nummer' => 1,
            'type' => WeekType::BUFFER,
        ]);
        Week::create([
            'semester_id' => $semester->id,
            'maandag' => '2026-03-23', // normal schoolweek
            'nummer' => 2,
            'type' => WeekType::LES,
        ]);
        Week::create([
            'semester_id' => $semester->id,
            'maandag' => '2026-03-30', // holiday with name
            'nummer' => 3,
            'naam' => 'Voorjaarsvakantie',
            'type' => WeekType::VAKANTIE,
        ]);

        $response = $this->getJson('/api/list?start=2026-03-10&end=2026-04-05');

        $response->assertOk();

        $data = $response->json();

        // Should contain at least 4 events: 3 weeks + 1 semester span
        $this->assertGreaterThanOrEqual(4, count($data));

        // Find week events by title (avoid relying on mutated start/end dates)
        $buffer = collect($data)->first(fn($e) => isset($e['title']) && str_contains($e['title'], 'Week 1 - <strong>Bufferweek</strong>'));
        $this->assertNotNull($buffer);
        $this->assertSame('#48486e', $buffer['color']);
        $this->assertSame('#ededf7', $buffer['textColor']);

        $les = collect($data)->first(fn($e) => isset($e['title']) && str_contains($e['title'], 'Week 2'));
        $this->assertNotNull($les);
        $this->assertSame('#48486e', $les['color']);

        $vakantie = collect($data)->first(fn($e) => isset($e['title']) && str_contains($e['title'], 'Week 3 - <strong>Voorjaarsvakantie</strong>'));
        $this->assertNotNull($vakantie);

        // There should also be a semester event within range
        $semesterEvent = collect($data)->first(fn($e) => str_starts_with($e['id'], 's'));
        $this->assertNotNull($semesterEvent);
        $this->assertSame('#c1d6ca', $semesterEvent['color']);
        $this->assertSame('#626e67', $semesterEvent['textColor']);
        $this->assertStringContainsString('Blok', $semesterEvent['title']);
    }

    public function test_list_endpoint_validates_input_dates(): void
    {
        $this->getJson('/api/list')->assertStatus(400)
            ->assertJsonPath('error.code', 2);

        $this->getJson('/api/list?start=not-a-date&end=also-bad')->assertStatus(400)
            ->assertJsonPath('error.code', 2);

        $this->getJson('/api/list?start=2026-04-10&end=2026-04-01')->assertStatus(400)
            ->assertJsonPath('error.code', 2);
    }
}
