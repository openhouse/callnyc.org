const test = require('node:test');
const assert = require('node:assert/strict');
const { spawnSync } = require('node:child_process');
const { existsSync, mkdtempSync, readFileSync, rmSync, writeFileSync } = require('node:fs');
const { tmpdir } = require('node:os');
const { join, resolve } = require('node:path');

const repoRoot = resolve(__dirname, '..');
const auditScript = join(repoRoot, 'evals', 'audit-copy-facts.cjs');
const ledgerPath = join(repoRoot, 'evals', 'facts', 'callnyc-copy-2026-08-13.json');
const baseUrl = process.env.CALLNYC_BASE_URL || 'http://127.0.0.1:8080/';

function runAudit(extraArgs = []) {
  return spawnSync(
    process.execPath,
    [auditScript, '--ledger', ledgerPath, '--url', baseUrl, ...extraArgs],
    { cwd: repoRoot, encoding: 'utf8' },
  );
}

test('rendered present-day claims pass the public-evidence and graph-governance audit', () => {
  const result = runAudit();

  assert.equal(result.status, 0, result.stdout + result.stderr);
  assert.match(result.stdout, /PASS\s+rendered claims/i);
  assert.match(result.stdout, /PASS\s+bounded absence/i);
  assert.match(result.stdout, /PASS\s+private graph boundary/i);
});

test('the audit rejects an absence claim whose evidentiary limit is removed', () => {
  assert.equal(existsSync(ledgerPath), true, 'the fact ledger must exist before it can be mutated');
  const temporaryDirectory = mkdtempSync(join(tmpdir(), 'callnyc-fact-audit-'));
  const alteredLedgerPath = join(temporaryDirectory, 'facts.json');

  try {
    const ledger = JSON.parse(readFileSync(ledgerPath, 'utf8'));
    const absenceClaim = ledger.claims.find(
      (claim) => claim.id === 'claim.council-connect-publication-gap',
    );
    absenceClaim.limitations = [];
    writeFileSync(alteredLedgerPath, `${JSON.stringify(ledger, null, 2)}\n`);

    const result = spawnSync(
      process.execPath,
      [auditScript, '--ledger', alteredLedgerPath, '--url', baseUrl],
      { cwd: repoRoot, encoding: 'utf8' },
    );

    assert.notEqual(result.status, 0, 'the unsupported universal absence claim must fail closed');
    assert.match(result.stdout + result.stderr, /bounded absence/i);
  } finally {
    rmSync(temporaryDirectory, { recursive: true, force: true });
  }
});
