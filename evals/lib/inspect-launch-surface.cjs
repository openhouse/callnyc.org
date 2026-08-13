const { URL } = require('node:url');

function matches(html, pattern) {
  return [...html.matchAll(pattern)];
}

function stripMarkup(value = '') {
  return value
    .replace(/<script\b[^>]*>[\s\S]*?<\/script>/gi, ' ')
    .replace(/<style\b[^>]*>[\s\S]*?<\/style>/gi, ' ')
    .replace(/<[^>]+>/g, ' ')
    .replace(/&nbsp;/gi, ' ')
    .replace(/&amp;/gi, '&')
    .replace(/&#39;/gi, "'")
    .replace(/&quot;/gi, '"')
    .replace(/\s+/g, ' ')
    .trim();
}

function tagWithTestId(html, testId) {
  const escaped = testId.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  const match = html.match(new RegExp(`<[^>]+data-testid=["']${escaped}["'][^>]*>`, 'i'));
  return match ? match[0] : '';
}

function attribute(tag, name) {
  const escaped = name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  const match = tag.match(new RegExp(`\\s${escaped}=["']([^"']*)["']`, 'i'));
  return match ? match[1] : '';
}

function elementTextById(html, id) {
  const escaped = id.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  const match = html.match(new RegExp(`<([a-z0-9]+)[^>]*id=["']${escaped}["'][^>]*>([\\s\\S]*?)<\\/\\1>`, 'i'));
  return stripMarkup(match ? match[2] : '');
}

function elementTextByTestId(html, testId) {
  const escaped = testId.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  const match = html.match(new RegExp(`<([a-z0-9]+)[^>]*data-testid=["']${escaped}["'][^>]*>([\\s\\S]*?)<\\/\\1>`, 'i'));
  return stripMarkup(match ? match[2] : '');
}

function cssDeclaration(stylesheet, selector, property) {
  const escapedSelector = selector.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  const escapedProperty = property.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  const rule = stylesheet.match(new RegExp(`${escapedSelector}\\s*\\{([^}]*)\\}`, 'i'));
  if (!rule) return '';

  const declaration = rule[1].match(new RegExp(`(?:^|;)\\s*${escapedProperty}\\s*:\\s*([^;}]*)`, 'i'));
  return declaration ? declaration[1].replace(/!important/gi, '').trim().toLowerCase() : '';
}

async function fetchResource(url) {
  const response = await fetch(url, { redirect: 'follow' });
  const buffer = Buffer.from(await response.arrayBuffer());
  return {
    url: response.url,
    status: response.status,
    contentType: response.headers.get('content-type') || '',
    robots: response.headers.get('x-robots-tag') || '',
    buffer,
    text: buffer.toString('utf8'),
  };
}

function parseJson(value) {
  try {
    return JSON.parse(value);
  } catch (_error) {
    return null;
  }
}

async function inspectLaunchSurface(baseUrl) {
  const normalizedBase = new URL(baseUrl);
  const root = await fetchResource(normalizedBase);
  const html = root.text;
  const launchStylesheet = await fetchResource(new URL('/css/launch.css', normalizedBase));
  const healthResponse = await fetchResource(new URL('/health.php', normalizedBase));
  const protectedPathResponses = await Promise.all([
    '/.env',
    '/.env.example',
    '/.git/config',
    '/.github/workflows/launch-evals.yml',
    '/Dockerfile',
    '/README.md',
    '/docker-compose.yml',
    '/docs/knowledge-bank/README.md',
    '/evals/launch-2026-08-13-A.json',
    '/package.json',
    '/tests/launch-surface.test.cjs',
  ].map(async (path) => [path, await fetchResource(new URL(path, normalizedBase))]));

  const categoryHrefs = [...new Set(matches(html, /href=["'](\/[^"'#?]+\.html)["']/gi).map((match) => match[1]))];
  const firstCategory = categoryHrefs.length
    ? await fetchResource(new URL(categoryHrefs[0], normalizedBase))
    : { status: 0, text: '' };

  const politicoLinkTag = tagWithTestId(html, 'politico-evidence-link');
  const politicoImageTag = tagWithTestId(html, 'politico-thumbnail');
  const advocacyTag = tagWithTestId(html, 'restore-data-email');
  const logoTag = tagWithTestId(html, 'callnyc-logo');

  const politicoHref = attribute(politicoLinkTag, 'href');
  const politicoImageSrc = attribute(politicoImageTag, 'src');
  const advocacyHref = attribute(advocacyTag, 'href');
  const logoSrc = attribute(logoTag, 'src');

  const politicoDocument = politicoHref
    ? await fetchResource(new URL(politicoHref, normalizedBase))
    : { status: 0, contentType: '', buffer: Buffer.alloc(0) };
  const politicoImage = politicoImageSrc
    ? await fetchResource(new URL(politicoImageSrc, normalizedBase))
    : { status: 0, contentType: '', buffer: Buffer.alloc(0) };
  const logo = logoSrc
    ? await fetchResource(new URL(logoSrc, normalizedBase))
    : { status: 0, contentType: '', buffer: Buffer.alloc(0) };

  let mailto = { recipients: [], subject: '', body: '' };
  if (advocacyHref.toLowerCase().startsWith('mailto:')) {
    const parsed = new URL(advocacyHref.replace(/&amp;/g, '&'));
    mailto = {
      recipients: decodeURIComponent(parsed.pathname)
        .split(',')
        .map((recipient) => recipient.trim().toLowerCase())
        .filter(Boolean),
      subject: parsed.searchParams.get('subject') || '',
      body: parsed.searchParams.get('body') || '',
    };
  }

  const externalScriptHosts = matches(html, /<script\b[^>]*\bsrc=["'](https?:\/\/[^"']+)["']/gi)
    .map((match) => new URL(match[1]).host);
  const claimIds = [...new Set(
    matches(html, /\sdata-claim-id=["']([^"']+)["']/gi)
      .flatMap((match) => match[1].split(/\s+/))
      .filter(Boolean),
  )];
  const categoryHeaders = matches(html, /<a\b[^>]*class=["'][^"']*collapsible-header[^"']*["'][^>]*>/gi)
    .map((match) => match[0]);

  return {
    root,
    health: {
      status: healthResponse.status,
      payload: parseJson(healthResponse.text),
    },
    protectedPaths: Object.fromEntries(
      protectedPathResponses.map(([path, response]) => [path, response.status]),
    ),
    html,
    title: stripMarkup((html.match(/<title>([\s\S]*?)<\/title>/i) || [])[1]),
    rootHeading: stripMarkup((html.match(/<h1\b[^>]*>([\s\S]*?)<\/h1>/i) || [])[1]),
    hasOriginalNavigation: /id=["']nav-mobile["']/i.test(html),
    hasOriginalHero: /id=["']index-banner["']/i.test(html),
    hasOriginalFooter: /class=["'][^"']*page-footer/i.test(html),
    viewportContent: attribute((html.match(/<meta\b[^>]*name=["']viewport["'][^>]*>/i) || [])[0] || '', 'content'),
    skipLinkHref: attribute((html.match(/<a\b[^>]*class=["'][^"']*skip-link[^"']*["'][^>]*>/i) || [])[0] || '', 'href'),
    categoryHeaderCount: categoryHeaders.length,
    focusableCategoryHeaderCount: categoryHeaders.filter((tag) => /\shref=["'][^"']+["']/i.test(tag) || /\stabindex=["']0["']/i.test(tag)).length,
    memberCardCount: matches(html, /class=["'][^"']*section\s+scrollspy[^"']*["']/gi).length,
    historicMemberLinkCount: matches(html, /href=["']https:\/\/web\.archive\.org\/web\/20170710152429\/https:\/\/council\.nyc\.gov\/district-/gi).length,
    categoryHrefs,
    firstCategory: {
      status: firstCategory.status,
      heading: stripMarkup((firstCategory.text.match(/<h1\b[^>]*>([\s\S]*?)<\/h1>/i) || [])[1]),
      hasArchiveFrame: /id=["']archive-status["']/i.test(firstCategory.text),
    },
    archiveText: elementTextById(html, 'archive-status'),
    archiveRouteCount: matches(html, /href=["'][^"']*\/archive(?:\/|["'])/gi).length,
    palette: {
      archiveBackground: cssDeclaration(launchStylesheet.text, '.archive-status', 'background'),
      archiveText: cssDeclaration(launchStylesheet.text, '.archive-status', 'color'),
      actionBackground: cssDeclaration(launchStylesheet.text, '.archive-status .restore-data-button', 'background-color'),
      originalHeroBackground: cssDeclaration(launchStylesheet.text, '#index-banner', 'background-color'),
      originalFooterBackground: cssDeclaration(launchStylesheet.text, '.page-footer', 'background-color'),
    },
    telLinkCount: matches(html, /href=["']tel:/gi).length,
    currentContactLinkCount: matches(html, /href=["']https:\/\/council\.nyc\.gov\/districts\/?["']/gi).length,
    politico: {
      href: politicoHref,
      imageSrc: politicoImageSrc,
      imageAlt: attribute(politicoImageTag, 'alt'),
      documentStatus: politicoDocument.status,
      documentType: politicoDocument.contentType,
      documentSignature: politicoDocument.buffer.subarray(0, 4).toString('ascii'),
      imageStatus: politicoImage.status,
      imageType: politicoImage.contentType,
      imageSignature: politicoImage.buffer.subarray(1, 4).toString('ascii'),
    },
    advocacy: {
      href: advocacyHref,
      label: elementTextByTestId(html, 'restore-data-email'),
      ...mailto,
    },
    logo: {
      tagName: (logoTag.match(/^<([a-z0-9]+)/i) || [])[1] || '',
      src: logoSrc,
      status: logo.status,
      contentType: logo.contentType,
    },
    externalScriptHosts,
    claimIds,
  };
}

module.exports = { inspectLaunchSurface };
