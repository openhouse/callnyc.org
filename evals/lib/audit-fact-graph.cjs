const { URL } = require('node:url');

const OFFICIAL_HOSTS = new Set([
  'api.us.socrata.com',
  'council.nyc.gov',
  'data.cityofnewyork.us',
]);

function auditFactGraph({ ledger, graph, renderedClaimIds }) {
  const failures = [];
  const sourceById = new Map(ledger.sources.map((source) => [source.id, source]));
  const claimById = new Map(ledger.claims.map((claim) => [claim.id, claim]));
  const graphNodeIds = new Set(graph.nodes.map((node) => node.id));
  const checkedAt = Date.parse(`${ledger.checkedAt}T00:00:00Z`);

  if (!Number.isFinite(checkedAt)) failures.push('ledger checkedAt must be an ISO date');

  for (const claimId of renderedClaimIds) {
    const claim = claimById.get(claimId);
    if (!claim || !claim.publicCopy) failures.push(`rendered claim is not governed: ${claimId}`);
    if (!graphNodeIds.has(claimId)) failures.push(`rendered claim is absent from the graph: ${claimId}`);
  }

  for (const claim of ledger.claims.filter((entry) => entry.publicCopy)) {
    if (!renderedClaimIds.includes(claim.id)) failures.push(`governed public claim is not rendered: ${claim.id}`);
    if (!Array.isArray(claim.sourceIds) || claim.sourceIds.length === 0) {
      failures.push(`claim has no evidence: ${claim.id}`);
    }

    for (const sourceId of claim.sourceIds || []) {
      const source = sourceById.get(sourceId);
      if (!source) {
        failures.push(`claim references an unknown source: ${claim.id} -> ${sourceId}`);
        continue;
      }
      if (!graphNodeIds.has(sourceId)) failures.push(`source is absent from the graph: ${sourceId}`);
    }
  }

  const absenceClaim = claimById.get('claim.council-connect-publication-gap');
  const boundedAbsence = Boolean(
    absenceClaim &&
    absenceClaim.rating === 'supported-bounded' &&
    absenceClaim.statement.includes(ledger.checkedAt) &&
    Array.isArray(absenceClaim.limitations) &&
    absenceClaim.limitations.length >= 2 &&
    absenceClaim.limitations.some((limit) => /not-found|does not prove/i.test(limit)) &&
    absenceClaim.limitations.some((limit) => /exact .*date|why/i.test(limit))
  );
  if (!boundedAbsence) failures.push('bounded absence claim is missing its date, rating, or evidentiary limits');

  if (absenceClaim) {
    const officialEvidence = absenceClaim.sourceIds
      .map((sourceId) => sourceById.get(sourceId))
      .filter(Boolean)
      .filter((source) => {
        try {
          return OFFICIAL_HOSTS.has(new URL(source.url).host);
        } catch {
          return false;
        }
      });
    if (officialEvidence.length !== absenceClaim.sourceIds.length) {
      failures.push('present-day absence claim must rely entirely on official public evidence');
    }
  }

  const privateRoots = ledger.knowledgeEcosystemReview.roots
    .filter((root) => root.visibility === 'private');
  const serializedLedger = JSON.stringify(ledger);
  const privateBoundary = Boolean(
    privateRoots.length >= 1 &&
    privateRoots.every((root) => root.usedAsPublishedEvidence === false) &&
    ledger.knowledgeEcosystemReview.privateContentProjected === false &&
    ledger.knowledgeEcosystemReview.privatePathsProjected === false &&
    !/\/(?:Users|Volumes)\//.test(serializedLedger) &&
    !/archival-research-projects/i.test(serializedLedger)
  );
  if (!privateBoundary) failures.push('private graph boundary is not fail-closed');

  return {
    passed: failures.length === 0,
    failures,
    checks: {
      renderedClaims: !failures.some((failure) => /rendered claim|public claim is not rendered/i.test(failure)),
      graphCoverage: !failures.some((failure) => /absent from the graph/i.test(failure)),
      boundedAbsence,
      privateBoundary,
    },
  };
}

module.exports = { auditFactGraph };
