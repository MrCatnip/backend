# Mini PHP Framework (CMS?)

- ✅ Vanilla PHP, no frameworks (custom router, controllers, model, PDO, views)
- ✅ PHP 8.4+
- ✅ OOP
- ✅ MVC architecture
- ✅ MySQL
- ✅ Basic routing (`GET`/`POST`/`PUT`/`DELETE`) & global error handling
- ✅ Composer (PSR-4 autoloading only)

Demo feature: users CRUD with filtering - create/update/delete via real
`POST`/`PUT`/`DELETE` (`fetch` + JSON).

**Run:** `composer install` → `cp .env.example .env` (set DB creds) → import
`database/schema.sql` → `php -S localhost:8000 -t public`.

**Time spent:** ~1:40 hrs without the README, 2:40 hrs with the README (6 hrs if we account me speedrunning hello worlds in php)

## Assumptions / Notes / About Me

_(Feel free to skip - this is just context, not part of the deliverable.)_

- **AI was used.** Doing it the Stack Overflow way would prolly take me
  3–4x the time.

- **About the 3h / 6h constraint** - I read it as one of two things:
  - **A) A time-pressure check** (deliver / fix bugs on a deadline). I keep the
    mental model of my projects fresh, so I don't spend more than necessary,
    including when I'm oncall at 3 AM.
  - **B) A "how much is this dude going to bill us for a simple CRUD?" check.**
    I do love min-maxing, meaning I'm spending hours cleaning code or building
    little frameworks for myself, but I don't bill for that. It always evens out
    in the long run and I get more bang for the buck.

- **Heads-up for the next round:** in case we move forward, I'll be "cheating"
  a bit - expanding this repo beforehand with common functionality so I'm ready
  for most scenarios during the tech interview.
