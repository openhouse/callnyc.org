const { readFileSync } = require('node:fs');
const { resolve } = require('node:path');
const { auditFactGraph } = require('./lib/audit-fact-graph.cjs');
const { inspectLaunchSurface } = require('./lib/inspect-launch-surface.cjs');

function argument(name, fallback = '') {
  const index = process.argv.indexOf(name);
  return index >= 0 ? process.argv[index + 1] : fallback;
}

(async () => {
  const ledgerPath = resolve(argument('--ledger', 'evals/facts/callnyc-copy-2026-08-13.json'));
  const baseUrl = argument('--url', process.env.CALLNYC_BASE_URL || 'http://127.0.0.1:8080/');
  const ledger = JSON.parse(readFileSync(ledgerPath, 'utf8'));
  const graph = JSON.parse(readFileSync(resolve(ledger.graphPath), 'utf8'));
  const surface = await inspectLaunchSurface(baseUrl);
  const report = auditFactGraph({ ledger, graph, renderedClaimIds: surface.claimIds });

  const checks = [
    ['rendered claims', report.checks.renderedClaims],
    ['graph coverage', report.checks.graphCoverage],
    ['bounded absence', report.checks.boundedAbsence],
    ['private graph boundary', report.checks.privateBoundary],
  ];
  for (const [label, passed] of checks) console.log(`${passed ? 'PASS' : 'FAIL'}  ${label}`);
  for (const failure of report.failures) console.error(`- ${failure}`);

  process.exitCode = report.passed ? 0 : 1;
})().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
