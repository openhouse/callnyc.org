# Dokku deployment handoff

The application is packaged for Dokku through the repository `Dockerfile` and listens on port `5000`. Production must not move until a clean, exact candidate has passed staging review.

## Intended topology

| Surface | Dokku app | Domain | Robots |
|---|---|---|---|
| Staging candidate | `callnyc-staging` | `staging.callnyc.org` | `noindex, nofollow` |
| Public site | `callnyc` | `callnyc.org`, `www.callnyc.org` | indexable after approval |

The artifact at `/` and its `/archive/2016/` compatibility alias need MySQL. `/health.php` remains database-independent, while the container bootstrap expects its linked database so it can seed the deterministic archive when necessary.

## One-time staging wiring

Run on the Dokku host, adapting the host and certificate commands to the existing installation:

```sh
dokku apps:create callnyc-staging
dokku mysql:create callnyc-staging-db
dokku mysql:link callnyc-staging-db callnyc-staging
dokku config:set callnyc-staging CALLNYC_ARCHIVED=1 CALLNYC_ROBOTS=noindex GIT_REV=<exact-commit>
dokku proxy:ports-set callnyc-staging http:80:5000
dokku domains:set callnyc-staging staging.callnyc.org
```

Deploy the exact candidate commit, not an ambiguous working branch. Then enable TLS through the host’s existing certificate workflow.

## Staging attestation

Record the candidate commit and verify:

```sh
curl -fsS https://staging.callnyc.org/health.php
curl -fsSI https://staging.callnyc.org/
curl -fsS https://staging.callnyc.org/ | rg 'Archived and unofficial|Built in a 24-hour sprint'
curl -fsS https://staging.callnyc.org/archive/2016/ | rg 'Call NYC|historical reconstruction'
curl -fsS https://staging.callnyc.org/ | rg 'mailto:Data@council.nyc.gov,opendata@oti.nyc.gov'
```

Required evidence:

- `/health.php` reports `status: ok` and the exact `GIT_REV`.
- The root emits `X-Robots-Tag: noindex, nofollow` on staging.
- Root, compatibility alias, CSS, local JavaScript, SVG logo, Politico image, and archived PDF return successfully over HTTPS.
- Desktop and mobile screenshots match the approved composition closely enough to preserve its hierarchy.
- The database-backed root and a representative root-level category route render the original navigation, banner, and ranking cards.
- Mutating endpoints remain blocked by `CALLNYC_ARCHIVED=1`.

## Production cutover

Production change requires Jamie’s approval of the exact staged candidate and verified ownership of the current `callnyc.org` Dokku app/domain wiring. Before changing the domain or deployment remote:

1. Capture the current production app name, domain mapping, proxy ports, config keys, database link, and deployed revision.
2. Confirm a rollback target and database backup or reproducible seed path.
3. Set `CALLNYC_ARCHIVED=1` and `GIT_REV=<approved-commit>` on the production app.
4. Deploy the exact approved commit.
5. Verify root, preserved alias, a representative category route, health, assets, TLS, redirects, and indexing posture.
6. Remove `CALLNYC_ROBOTS=noindex` only after the production candidate is confirmed.

This document prepares the preserved successor to replace the old deployment. It does not claim that the production domain has already been switched.
