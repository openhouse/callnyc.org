const criteria = require('./launch-2026-08-13-A.json');
const { inspectLaunchSurface } = require('./lib/inspect-launch-surface.cjs');

const urlIndex = process.argv.indexOf('--url');
const baseUrl = urlIndex >= 0 ? process.argv[urlIndex + 1] : (process.env.CALLNYC_BASE_URL || 'http://127.0.0.1:8080/');

function score(surface) {
  return {
    original_experience:
      surface.root.status === 200 &&
      surface.rootHeading === 'Call NYC' &&
      surface.hasOriginalNavigation &&
      surface.hasOriginalHero &&
      surface.memberCardCount >= 45 &&
      surface.categoryHrefs.length >= 20 &&
      surface.firstCategory.status === 200,
    historical_safety:
      surface.telLinkCount === 0 &&
      surface.currentContactLinkCount >= surface.memberCardCount &&
      surface.historicMemberLinkCount >= 45,
    archive_clarity:
      /archiv/i.test(surface.archiveText) &&
      /2016/.test(surface.archiveText) &&
      /no longer publish/i.test(surface.archiveText) &&
      surface.archiveRouteCount === 0 &&
      surface.firstCategory.hasArchiveFrame,
    politico_evidence:
      /Website provides new information about council members' focus/i.test(surface.html) &&
      /Mar\. 14, 2016/i.test(surface.html) &&
      surface.politico.documentStatus === 200 &&
      surface.politico.documentSignature === '%PDF' &&
      surface.politico.imageStatus === 200 &&
      surface.politico.imageSignature === 'PNG',
    advocacy_action:
      [...surface.advocacy.recipients].sort().join(',') ===
        ['data@council.nyc.gov', 'opendatateam@oti.nyc.gov'].sort().join(',') &&
      surface.advocacy.subject === 'Restore constituent services data publishing' &&
      /CouncilStat/i.test(surface.advocacy.body) &&
      /NYC Open Data/i.test(surface.advocacy.body) &&
      /anonymized|privacy-protected/i.test(surface.advocacy.body),
    self_contained_resilience:
      surface.externalScriptHosts.length === 0 &&
      surface.logo.tagName.toLowerCase() === 'img' &&
      surface.logo.status === 200,
  };
}

(async () => {
  const surface = await inspectLaunchSurface(baseUrl);
  const results = score(surface);
  let total = 0;

  for (const criterion of criteria.criteria) {
    const passed = Boolean(results[criterion.id]);
    if (passed) total += criterion.weight;
    console.log(`${passed ? 'PASS' : 'FAIL'}  ${String(criterion.weight).padStart(2)}  ${criterion.id}`);
  }

  console.log(`\nSCORE ${total}/100`);
  process.exitCode = total === 100 ? 0 : 1;
})().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
