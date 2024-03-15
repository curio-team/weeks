<?php

namespace App\Filament\Pages;

use Closure;
use App\Enums\Volgorde;
use App\Enums\WeekType;
use App\Models\Schooljaar;
use App\Models\Semester;
use App\Models\Week;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Wizard as WizardComponent;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\View;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class Wizard extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected static ?string $title = 'Nieuw schooljaar';
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static string $view = 'filament.pages.wizard';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                WizardComponent::make([
                    Step::make('Schooljaar')
                        ->columns(2)
                        ->schema([
                            Forms\Components\DatePicker::make('schooljaar_start')
                                ->autofocus()
                                ->required()
                                ->label('Startdatum')
                                ->hint('eerste dag van het schooljaar')
                                ->default("2024-03-04")
                                ->rules([
                                    function () {
                                        return function (string $attribute, $value, Closure $fail) {
                                            if ((new Carbon($value))->isMonday() === false) {
                                                $fail('Eerste dag moet een maandag zijn!');
                                            }
                                        };
                                    },
                                ]),
                            Forms\Components\DatePicker::make('schooljaar_eind')
                                ->required()
                                ->label('Einddatum')
                                ->hint('laatste dag van het schooljaar')
                                ->after('schooljaar_start')
                                ->default("2024-06-23")
                                ->rules([
                                    function () {
                                        return function (string $attribute, $value, Closure $fail) {
                                            if ((new Carbon($value))->isSunday() === false) {
                                                $fail('Laatste dag moet een zondag zijn!');
                                            }
                                        };
                                    },
                                ]),
                        ]),
                    Step::make('Semesters')
                        ->schema(function (Get $get) {
                            $schooljaar_start = $get('schooljaar_start') ?? "";
                            $schooljaar_eind = $get('schooljaar_eind') ?? "";
                            $fieldsets = array();
                            $i = 0;
                            foreach (Volgorde::cases() as $case) {
                                $fieldsets[] = Forms\Components\Fieldset::make($case->naamExtraLang())
                                    ->schema([
                                        Forms\Components\DatePicker::make("semesters.$i.start")
                                            ->hint('eerste dag van semester')
                                            ->label('Startdatum')
                                            ->required()
                                            ->default("2024-03-04")
                                            ->afterOrEqual($schooljaar_start)
                                            ->rules([
                                                function (Get $get) use ($i) {
                                                    return function (string $attribute, $value, Closure $fail) use ($get, $i) {
                                                        $prev = $i - 1;
                                                        $date = new Carbon($value);
                                                        $prev_end_date = new Carbon($get("semesters.$prev.eind"));
                                                        if ($date < $prev_end_date && $prev >= 0) {
                                                            $fail('Semester moet starten na einde vorige semester!');
                                                        }
                                                        if ($date->isMonday() === false) {
                                                            $fail('Eerste dag moet een maandag zijn!');
                                                        }
                                                    };
                                                },
                                            ]),
                                        Forms\Components\DatePicker::make("semesters.$i.eind")
                                            ->hint('laatste dag van semester')
                                            ->label('Einddatum')
                                            ->default("2024-03-10")
                                            ->required()
                                            ->after('semester_start')
                                            ->beforeOrEqual($schooljaar_eind)
                                            ->rules([
                                                function () {
                                                    return function (string $attribute, $value, Closure $fail) {
                                                        if ((new Carbon($value))->isSunday() === false) {
                                                            $fail('Laatste dag moet een zondag zijn!');
                                                        }
                                                    };
                                                },
                                            ])
                                            ->live()
                                            ->afterStateUpdated(function (Component $component, Set $set, $state) use ($i) {
                                                $next = $i + 1;
                                                $next_date = new Carbon($component->getState());
                                                $next_date = $next_date->addDay()->format('Y-m-d');
                                                $set("semesters.$next.start", $next_date);
                                            }),
                                    ])
                                    ->columns(2);

                                $i++;
                            }
                            return $fieldsets;
                        }),
                    Step::make('Weken')
                        ->schema(function () {
                            $fieldsets = array();
                            $fieldsets[] = View::make('filament.pages.wizard_weken_uitleg');

                            $i = 0;
                            foreach (Volgorde::cases() as $case) {
                                $fieldsets[] = Forms\Components\Fieldset::make($case->naamExtraLang())
                                    ->schema(function (Get $get) use ($i) {
                                        $start_datum = $get("semesters.$i.start");
                                        $eind_datum = $get("semesters.$i.eind");
                                        $fields = array();
                                        if (isset($start_datum) && isset($eind_datum)) {
                                            $j = 0;
                                            $period = new CarbonPeriod($start_datum, '7 days', $eind_datum);
                                            $count = $period->count();
                                            $period->forEach(function (Carbon $date) use ($i, &$j, &$fields, $count) {

                                                $fields[] = Forms\Components\Hidden::make("semesters.$i.weeks.$j.monday");
                                                $this->data['semesters'][$i]['weeks'][$j]['monday'] = $date->format('Y-m-d');

                                                $fields[] = Forms\Components\TextInput::make("semesters.$i.weeks.$j.num")
                                                    ->label($date->format('d-m-Y'))
                                                    ->required()
                                                    ->inlineLabel()
                                                    ->numeric()
                                                    ->suffixAction(
                                                        Action::make('copyDown')
                                                            ->icon('heroicon-o-arrow-down-on-square-stack')
                                                            ->action(function (Set $set, $state) use ($i, $j, $count) {
                                                                $new_state = $state + 1;
                                                                for ($k = $j + 1; $k <= $count; $k++) {
                                                                    $set("semesters.$i.weeks.$k.num", $new_state);
                                                                    $new_state++;
                                                                }
                                                            })
                                                    );

                                                $fields[] = Forms\Components\Select::make("semesters.$i.weeks.$j.type")->hiddenLabel()->options(WeekType::class)->default('lesweek')->selectablePlaceholder(false);
                                                $fields[] = Forms\Components\TextInput::make("semesters.$i.weeks.$j.name")->hiddenLabel()->placeholder('Naam');

                                                $j++;
                                            });
                                        }
                                        return $fields;
                                    })->columns(3);

                                $i++;
                            }
                            return $fieldsets;
                        }),
                ])->submitAction(
                    new HtmlString(Blade::render(<<<BLADE
                        <x-filament::button type="submit" size="sm">Opslaan</x-filament::button>
                    BLADE))
                )
            ])->statePath('data');
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function create(): void
    {
        $data = $this->form->getState();
        $schooljaar = new Schooljaar();
        $schooljaar->start = $data['schooljaar_start'];
        $schooljaar->eind = $data['schooljaar_eind'];
        $schooljaar->save();

        $volgorde = 1;
        foreach ($data['semesters'] as $data_semester) {
            $semester = new Semester();
            $semester->schooljaar_id = $schooljaar->id;
            $semester->volgorde = $volgorde;
            $semester->start = $data_semester['start'];
            $semester->eind = $data_semester['eind'];
            $semester->save();

            foreach ($data_semester['weeks'] as $data_week) {
                $week = new Week();
                $week->semester_id = $semester->id;
                $week->maandag = $data_week['monday'];
                $week->nummer = $data_week['num'];
                $week->naam = $data_week['name'] ?? null;
                $week->type = $data_week['type'] ?? 'lesweek';
                $week->save();
            }

            $volgorde++;
        }

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();

        $this->redirect('/admin/schooljaren');
    }
}
