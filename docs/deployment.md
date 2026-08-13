# Dokku deployment handoff

The application is packaged for Dokku through the repository `Dockerfile` and listens on port `5000`. Production must not move until a clean, exact candidate has passed staging review.

## Intended topology

| Surface | Dokku app | Domain | Robots |
|---|---|---|---|
| Staging candidate | `callnyc-staging` | `staging.callnyc.org` | `noindex, nofollow` |
| Public site | `callnyc` | `callnyc.org`, `www.callnyc.org` | indexable after approval |

The historical application at the public root needs MySQL. `/health.php` remains database-independent, while the container bootstrap expects its linked database so it can seed the deterministic archive when necessary.

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
curl -fsS https://staging.callnyc.org/ | rg 'Archived and unofficial'
curl -fsS https://staging.callnyc.org/ | rg 'restore constituent services data publishing'
```

Required evidence:

- `/health.php` reports `status: ok` and the exact `GIT_REV`.
- The root emits `X-Robots-Tag: noindex, nofollow` on staging.
- Root, representative historical routes, CSS, SVG logo, Politico image, and archived PDF return successfully over HTTPS.
- Project-internal paths (`/docs/`, `/evals/`, `/tests/`, `/bin/`, `/.impeccable/`, `DESIGN.md`, `PRODUCT.md`, and `README.md`) return `404`.
- Desktop, tablet, and mobile screenshots preserve the original fixed-sidebar/card composition and the compact header remains responsive.
- The archive database renders a representative category route.
- Mutating endpoints remain blocked by `CALLNYC_ARCHIVED=1`.
- `getCSV.php` remains blocked even if `CALLNYC_ARCHIVED` is accidentally omitted; archive safety fails closed.

## Production cutover

Production change requires Jamie’s approval of the exact staged candidate and verified ownership of the current `callnyc.org` Dokku app/domain wiring. Before changing the domain or deployment remote:

1. Capture the current production app name, domain mapping, proxy ports, config keys, database link, and deployed revision.
2. Confirm a rollback target and database backup or reproducible seed path.
3. Set `CALLNYC_ARCHIVED=1` and `GIT_REV=<approved-commit>` on the production app.
4. Deploy the exact approved commit.
5. Verify root, a representative historical route, the `/archive/2016/` compatibility alias, health, assets, TLS, and indexing posture.
6. Remove `CALLNYC_ROBOTS=noindex` only after the production candidate is confirmed.

This document prepares the successor to replace the old deployment. It does not claim that the production domain has already been switched.
