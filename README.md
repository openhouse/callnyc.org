# CallNYC.org

CallNYC.org preserves the reconstructed 2016 CallNYC project as a working historical interface. The original experience remains at the public root, with one compact preservation header that identifies the archive, presents the contemporary Politico artifact, and asks NYC Council and NYC Open Data to restore a privacy-protected publication path from Council Connect.

This repository preserves lineage from [`openhouse/CallNYC`](https://github.com/openhouse/CallNYC), branch `feature/archive-2026`. It is independent and unofficial.

## Public surfaces

- `/` — historical CallNYC application with the compact preservation and advocacy header.
- `/archive/2016/` — compatibility alias for older links; newly rendered links use the original root paths.
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

3. Open [http://localhost:8080](http://localhost:8080).

The deterministic seed in `data/sample.csv` is loaded on first start. To seed it again:

```sh
docker compose exec web php bin/seed.php
```

## Verification

```sh
docker compose exec web php tests/run.php
docker compose exec web php bin/validate-knowledge.php
docker compose exec web php evals/launch-2026-08-13-C.php
sh evals/http-public-boundary.sh http://localhost:8080
```

With the bundled Playwright dependency available, `evals/capture-launch.cjs` verifies desktop, tablet, and mobile overflow, asset loading, browser errors, action height, and key contrast ratios. Its checked-in reports are implementation evidence rather than public routes.

## Archive safety

Archive mode defaults to on and disables the historical `getCSV.php` mutation path. Keep `CALLNYC_ARCHIVED=1` explicitly configured in deployment; only an intentional `CALLNYC_ARCHIVED=0` enables the inherited updater. The successor does not attempt to ingest a current feed because none has been established.

Apache returns `404` for the project-internal knowledge bank, tests, evals, design evidence, and maintenance scripts. Repository access and public web access remain separate boundaries.

## Deployment

The Docker image listens on port `5000` for Dokku. Staging and production commands, database wiring, no-index posture, health attestation, and cutover gates are documented in [`docs/deployment.md`](docs/deployment.md).

## Environment variables

- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_PORT`
- `DATABASE_URL` — optional; overrides the individual database settings.
- `CALLNYC_ARCHIVED=1` — required for the historical read-only posture.
- `CALLNYC_ROBOTS=noindex` — required on staging; omit for approved production.
- `GIT_REV` — exact deployed Git commit, returned by `/health.php`.
