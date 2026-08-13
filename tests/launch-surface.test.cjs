const test = require('node:test');
const assert = require('node:assert/strict');
const { before } = require('node:test');
const { inspectLaunchSurface } = require('../evals/lib/inspect-launch-surface.cjs');

const baseUrl = process.env.CALLNYC_BASE_URL || 'http://127.0.0.1:8080/';
let surface;

before(async () => {
  surface = await inspectLaunchSurface(baseUrl);
});

test('the preserved browse, rank, and drill-down experience still works', () => {
  assert.equal(surface.root.status, 200);
  assert.equal(surface.rootHeading, 'Call NYC');
  assert.equal(surface.hasOriginalNavigation, true);
  assert.equal(surface.hasOriginalHero, true);
  assert.equal(surface.hasOriginalFooter, true);
  assert.ok(surface.memberCardCount >= 45, `expected at least 45 member cards, found ${surface.memberCardCount}`);
  assert.ok(surface.categoryHrefs.length >= 20, `expected at least 20 service links, found ${surface.categoryHrefs.length}`);
  assert.equal(surface.firstCategory.status, 200);
  assert.notEqual(surface.firstCategory.heading, 'Call NYC');
  assert.ok(surface.historicMemberLinkCount >= 45, 'member portraits should retain their coordinated archival destinations');
});

test('the contemporary context is a compact frame on the original one-page surface', () => {
  assert.match(surface.archiveText, /archiv/i);
  assert.match(surface.archiveText, /2016/);
  assert.match(surface.archiveText, /Council Connect/i);
  assert.match(surface.archiveText, /historical/i);
  assert.match(surface.archiveText, /daily 311/i);
  const archiveWordCount = surface.archiveText.split(/\s+/).filter(Boolean).length;
  assert.ok(archiveWordCount <= 120, `archival band should stay compact; found ${archiveWordCount} words`);
  assert.equal(surface.archiveRouteCount, 0);
  assert.equal(surface.firstCategory.hasArchiveFrame, true);
});

test('the archive well uses the portfolio home surface without recoloring CallNYC', () => {
  assert.deepEqual(surface.palette, {
    archiveBackground: '#f3f6f8',
    archiveText: '#0b5f81',
    actionBackground: '#0b5f81',
    originalHeroBackground: '#ec6c71',
    originalFooterBackground: '#a21318',
  });
});

test('every material archival assertion is connected to the fact-check graph', () => {
  assert.deepEqual(
    [...surface.claimIds].sort(),
    [
      'claim.callnyc-archival-origin',
      'claim.council-connect-publication-gap',
    ].sort(),
  );
});

test('the Politico evidence is a real thumbnail linked to the preserved article', () => {
  assert.match(surface.html, /Website provides new information about council members' focus/i);
  assert.match(surface.html, /Mar\. 14, 2016/i);
  assert.ok(surface.politico.imageAlt.length >= 20, 'the thumbnail needs descriptive alternative text');
  assert.equal(surface.politico.documentStatus, 200);
  assert.match(surface.politico.documentType, /application\/pdf/i);
  assert.equal(surface.politico.documentSignature, '%PDF');
  assert.equal(surface.politico.imageStatus, 200);
  assert.match(surface.politico.imageType, /image\/png/i);
  assert.equal(surface.politico.imageSignature, 'PNG');
});

test('the advocacy control prepares a reviewable email to both responsible public teams', () => {
  assert.match(surface.advocacy.href, /^mailto:/i);
  assert.deepEqual(
    [...surface.advocacy.recipients].sort(),
    ['data@council.nyc.gov', 'opendatateam@oti.nyc.gov'].sort(),
  );
  assert.equal(surface.advocacy.subject, 'Restore constituent services data publishing');
  assert.match(surface.advocacy.body, /Council Connect/i);
  assert.match(surface.advocacy.body, /NYC Open Data/i);
  assert.match(surface.advocacy.body, /311/i);
  assert.match(surface.advocacy.body, /anonymized|privacy-protected/i);
  assert.match(surface.advocacy.label, /restore constituent services data publishing/i);
});

test('archival exploration does not dial historical numbers', () => {
  assert.equal(surface.telLinkCount, 0);
  assert.ok(
    surface.currentContactLinkCount >= surface.memberCardCount,
    `expected a current contact path for each of ${surface.memberCardCount} cards`,
  );
});

test('the preserved page is self-contained enough to render without third-party scripts', () => {
  assert.deepEqual(surface.externalScriptHosts, []);
  assert.equal(surface.logo.tagName.toLowerCase(), 'img');
  assert.equal(surface.logo.status, 200);
  assert.match(surface.logo.contentType, /image\/svg\+xml/i);
});

test('the long archival interface has a keyboard entrance and permits browser zoom', () => {
  assert.equal(surface.skipLinkHref, '#index-banner');
  assert.doesNotMatch(surface.viewportContent, /user-scalable\s*=\s*no/i);
  assert.doesNotMatch(surface.viewportContent, /maximum-scale\s*=\s*1/i);
  assert.ok(surface.categoryHeaderCount > 0);
  assert.equal(surface.focusableCategoryHeaderCount, surface.categoryHeaderCount);
});
