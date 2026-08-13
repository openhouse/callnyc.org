# CallNYC (Archived Demo)

## Local development (Docker Compose)

1. Copy env defaults (optional):
   ```sh
   cp .env.example .env
   ```
2. Build + start the stack:
   ```sh
   docker compose up --build
   ```
3. Seed data (only needed on first run; bootstrap runs this automatically if `cases` is missing):
   ```sh
   docker compose exec web php bin/seed.php
   ```
4. Open the app at: http://localhost:8080

Docker Compose mounts the repo into the container so edits are reflected on refresh.

## Seed data (deterministic)

The seed script builds the schema and loads `data/sample.csv`.

```sh
docker compose exec web php bin/seed.php
```

This script is idempotent and safe to re-run.

## Troubleshooting

- If you see `Table 'callnyc.cases' doesn't exist`, seed the database:
  ```sh
  docker compose exec web php bin/seed.php
  ```

## Archive mode

Set `CALLNYC_ARCHIVED=1` to disable mutating endpoints like `getCSV.php`.
This is enabled by default in `docker-compose.yml` and recommended for production.

The public presentation is always labeled as a 2016 archive. It retains the original
browse-and-rank interface, links member portraits to the coordinated 2017 Wayback
snapshot, and directs present-day contact actions to the current NYC Council directory.

## Launch evaluation

With the Docker stack running at `http://localhost:8080`:

```sh
npm test
npm run eval -- --url http://127.0.0.1:8080/
npm run eval:facts -- --url http://127.0.0.1:8080/
```

The tests exercise the rendered PHP application, one category route, the Politico PDF
and thumbnail, the visitor-controlled advocacy email, historical-contact safety, and
self-contained presentation assets. They verify that the archival well uses the lighter
portfolio homepage surface color while the original CallNYC hero and footer retain their
colors. They also bind every material present-day claim
to the project Knowledge Wiki, require public evidence for public copy, keep the
Council Connect absence finding dated and bounded, and prevent private graph material
from becoming public evidence. The weighted launch rubric lives in
`evals/launch-2026-08-13-A.json`; the fact ledger lives in
`evals/facts/callnyc-copy-2026-08-13.json`.

## Dokku staging checklist

- Create the isolated staging app and MySQL service:
  ```sh
  dokku apps:create callnyc-staging
  dokku mysql:create callnyc-staging-db
  dokku mysql:link callnyc-staging-db callnyc-staging
  ```
- Pin archive mode, crawler posture, and the exact deployed revision:
  ```sh
  dokku config:set callnyc-staging CALLNYC_ARCHIVED=1 CALLNYC_ROBOTS=noindex GIT_REV=<exact-commit>
  ```
- Dokku ports:
  - Dockerfile listens on `5000` (Dokku default). If using a different port, set:
    ```sh
    dokku proxy:ports-set callnyc-staging http:80:5000
    ```
- Set the staging hostname, then enable HTTPS only after DNS resolves to the Dokku host:
  ```sh
  dokku domains:set callnyc-staging staging.callnyc.org
  dokku letsencrypt:enable callnyc-staging
  ```

`GET /health.php` returns the service and `GIT_REV`. Staging HTML responses emit
`X-Robots-Tag: noindex, nofollow` when `CALLNYC_ROBOTS=noindex`.

The public web root also returns `403 Forbidden` for hidden paths and for
repository, evaluation, test, documentation, and deployment artifacts. The
production image excludes those non-runtime files as a second boundary.

## Environment variables

- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_PORT`
- `DATABASE_URL` (optional, overrides DB_* when set)
- `CALLNYC_ARCHIVED=1` to harden legacy write endpoints
- `CALLNYC_ROBOTS=noindex` to prevent indexing of staging HTML
- `GIT_REV` to identify the exact deployed commit at `/health.php`
