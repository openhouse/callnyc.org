#!/bin/sh

set -eu

base_url="${1:-http://127.0.0.1:8080}"
failures=0

assert_status() {
  expected="$1"
  path="$2"
  actual="$(curl -sS -o /dev/null -w '%{http_code}' "${base_url}${path}")"
  if [ "$actual" != "$expected" ]; then
    printf 'FAIL %s expected %s, received %s\n' "$path" "$expected" "$actual" >&2
    failures=$((failures + 1))
    return
  fi
  printf 'PASS %s %s\n' "$path" "$actual"
}

assert_status 200 /
assert_status 200 /health.php
assert_status 200 /data/media/politico-callnyc-2016-page-1.png
assert_status 200 /bin/jquery-2.2.1.min.js
assert_status 403 /getCSV.php
assert_status 404 /docs/knowledge-bank/README.md
assert_status 404 /docs/knowledge-bank/data/graph.json
assert_status 404 /evals/launch-2026-08-13-C.php
assert_status 404 /tests/run.php
assert_status 404 /.impeccable/review/README.md
assert_status 404 /DESIGN.md
assert_status 404 /PRODUCT.md
assert_status 404 /.env
assert_status 404 /.env.example
assert_status 404 /.git

exit "$failures"
