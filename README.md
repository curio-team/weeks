# Weeks

Een eenvoudige API die per datum/semester inzicht geeft in weekinformatie: lesweken (schoolweeks), vakanties (holidays) en buffers (geen vakantie, geen les).

**Kernmodellen:** `Schooljaar`, `Semester`, `Week` met enums `WeekType` (lesweek, bufferweek, vakantie) en `Volgorde` (sep/feb).

## API

**API-routes:** zie [routes/api.php](routes/api.php).

- GET `/api/`
	- Beschrijving: Huidige weekinformatie op basis van de maandag van “nu”, plus bijbehorend semester en schooljaar.
	- Response: `{ week: { maandag, nummer, type, naam? }, semester: { start, eind, volgorde }, schooljaar: { start, eind } }`

- GET `/api/cohort/{cohort}`
	- Beschrijving: Zelfde als `/api/`, maar geeft prioriteit aan een week die specifiek voor het cohort is vastgelegd (op dezelfde maandag).

- GET `/api/only/{type}`
	- Beschrijving: Geeft een enkel veld terug. Momenteel ondersteund: `week` (het weeknummer van de huidige week).
	- Voorbeelden: `/api/only/week`

- GET `/api/cohort/{cohort}/only/{type}`
	- Beschrijving: Variant van `only` met cohortvoorkeur.

- GET `/api/list?start=YYYY-MM-DD&end=YYYY-MM-DD`
	- Beschrijving: Lijst van “events” (weken binnen het bereik en overlappende semesters) bruikbaar voor bijvoorbeeld FullCalendar.
	- Validatie: beide queryparams verplicht; datumformaat en volgorde (start <= end) worden gecontroleerd.
	- Items:
		- Week-event: `{ id: 'w{ID}', allDay: true, start, end, title, color: '#48486e', textColor: '#ededf7', cohort }`
			- Titelopbouw: standaard `Week {nummer}`; met `naam`: `Week {nummer} - <strong>{naam}</strong>`; met cohort: `C{cohort}: ...`
			- Bufferweek zonder `naam` wordt automatisch getiteld als `Bufferweek`.
			- `end` is vijf dagen na `start` (maandag + 5).
		- Semester-event: `{ id: 's{ID}', allDay: true, start, end, title, color: '#c1d6ca', textColor: '#626e67', cohort }`
			- Titel: `Blok {naam_kort} / {schooljaar->naam}`; met cohort: `C{cohort}: ...`

### Voorbeelden

Gebruik `curl` (of browser) om de endpoints te testen:

```bash
# Huidige week/semester/schooljaar
curl http://localhost:8000/api/

# Cohort-specifieke week
curl http://localhost:8000/api/cohort/42

# Alleen het huidige weeknummer
curl http://localhost:8000/api/only/week

# Lijst met events voor kalenderweergave
curl "http://localhost:8000/api/list?start=2026-03-10&end=2026-04-05"
```

## Testen

PHPUnit 12 Feature-tests dekken de basisfunctionaliteit van de API: huidige week, cohort-override, lijstweergave (schoolweken, vakanties, bufferweken) en inputvalidatie.

Zie [tests/Feature/SemesterApiTest.php](tests/Feature/SemesterApiTest.php).

Uitvoeren:

```bash
composer install
php artisan key:generate
php artisan migrate
composer test
```

De tests bevriezen de tijd (Carbon) om resultaten voorspelbaar te maken en maken testdata aan voor `Schooljaar`, `Semester` en `Week`. Hiermee wordt o.a. gecontroleerd dat:
- Een cohort-specifieke week de generieke week overschrijft op dezelfde maandag.
- Bufferweken zonder naam als “Bufferweek” worden getoond in de titel.
- `/api/list` correcte events en kleuren oplevert voor weken en semesters, met duidelijke titels.
