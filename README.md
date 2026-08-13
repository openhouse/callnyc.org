# CallNYC.org

CallNYC.org is a contemporary steward of the reconstructed 2016 CallNYC project. The public root explains the present status of the constituent-services record, sends residents to current official help, and asks NYC Council and NYC OTI to restore a privacy-protected publication path from Council Connect to NYC Open Data. The historical application lives at `/archive/2016/`.

This repository preserves lineage from [`openhouse/CallNYC`](https://github.com/openhouse/CallNYC), branch `feature/archive-2026`. It is independent and unofficial.

## Public surfaces

- `/` — contemporary archive-and-advocacy publication; no database dependency.
- `/archive/2016/` — historical application backed by the reconstructed dataset.
- `/health.php` — database-independent deployment health and revision response.
- `docs/knowledge-bank/` — project-internal typed knowledge wiki; not automatically public.

## Local development

1. Copy the environment defaults:

   ```sh
   cp .env.example .env
   ```

2. Build and start the application and archive database:

   ```sh
   docker compose up --build
   ```

3. Open [http://localhost:8080](http://localhost:8080). The archive is at [http://localhost:8080/archive/2016/](http://localhost:8080/archive/2016/).

The deterministic seed in `data/sample.csv` is loaded on first start. To seed it again:

```sh
docker compose exec web php bin/seed.php
```

## Verification

```sh
docker compose exec web php tests/run.php
docker compose exec web php bin/validate-knowledge.php
```

## Archive safety

Keep `CALLNYC_ARCHIVED=1`. It disables the historical `getCSV.php` mutation path. The successor intentionally does not attempt to ingest a current feed because none has been established.

## Deployment

The Docker image listens on port `5000` for Dokku. Staging and production commands, database wiring, no-index posture, health attestation, and cutover gates are documented in [`docs/deployment.md`](docs/deployment.md).

## Environment variables

- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_PORT`
- `DATABASE_URL` — optional; overrides the individual database settings.
- `CALLNYC_ARCHIVED=1` — required for the historical read-only posture.
- `CALLNYC_ROBOTS=noindex` — required on staging; omit for approved production.
- `GIT_REV` — exact deployed Git commit, returned by `/health.php`.
