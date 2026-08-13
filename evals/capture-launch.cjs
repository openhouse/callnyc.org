const fs = require('fs');
const path = require('path');
const { chromium } = require('playwright');

const baseUrl = process.argv[2] || 'http://127.0.0.1:18083';
const outputDirectory = path.resolve(process.argv[3] || '.impeccable/review/launch-c-round-1');
const verifyOnly = process.argv.includes('--verify-only');

async function reviewViewport(browser, name, viewport) {
  const page = await browser.newPage({ viewport });
  const consoleErrors = [];
  const pageErrors = [];

  page.on('console', (message) => {
    if (message.type() === 'error') consoleErrors.push(message.text());
  });
  page.on('pageerror', (error) => pageErrors.push(error.message));

  const response = await page.goto(`${baseUrl}/`, { waitUntil: 'domcontentloaded' });
  await page.locator('.preservation-header').waitFor({ state: 'visible' });
  await page.locator('.card').first().waitFor({ state: 'visible' });

  const emailHref = await page.locator('.preservation-header__button').getAttribute('href');
  const logoLoaded = await page.locator('#front-page-logo').evaluate((image) => image.complete && image.naturalWidth > 0);
  const title = await page.title();
  const documentWidth = await page.evaluate(() => document.documentElement.scrollWidth);
  const hasHorizontalOverflow = documentWidth > viewport.width;
  const contrastChecks = await page.evaluate(() => {
    const parseColor = (value) => {
      const values = value.match(/[\d.]+/g)?.map(Number) || [];
      return values.slice(0, 3);
    };
    const luminance = ([red, green, blue]) => {
      const channels = [red, green, blue].map((channel) => {
        const normalized = channel / 255;
        return normalized <= 0.03928
          ? normalized / 12.92
          : ((normalized + 0.055) / 1.055) ** 2.4;
      });
      return (0.2126 * channels[0]) + (0.7152 * channels[1]) + (0.0722 * channels[2]);
    };
    const ratio = (foreground, background) => {
      const lighter = Math.max(luminance(foreground), luminance(background));
      const darker = Math.min(luminance(foreground), luminance(background));
      return (lighter + 0.05) / (darker + 0.05);
    };
    const opaqueBackground = (element) => {
      let current = element;
      while (current) {
        const value = getComputedStyle(current).backgroundColor;
        if (value !== 'rgba(0, 0, 0, 0)' && value !== 'transparent') return parseColor(value);
        current = current.parentElement;
      }
      return [255, 255, 255];
    };

    return [
      { selector: '.preservation-header__button', minimum: 4.5 },
      { selector: '.preservation-header__title', minimum: 3 },
      { selector: '#index-banner h4', minimum: 3 },
      { selector: '.card .card-action a:not(.btn)', minimum: 4.5 },
      { selector: '.page-footer a[href*="b9km-gdpy"]', minimum: 4.5 },
    ].map(({ selector, minimum }) => {
      const element = document.querySelector(selector);
      if (!element) return { selector, minimum, ratio: 0, pass: false };
      const foreground = parseColor(getComputedStyle(element).color);
      const background = opaqueBackground(element);
      const measuredRatio = ratio(foreground, background);
      return { selector, minimum, ratio: Number(measuredRatio.toFixed(2)), pass: measuredRatio >= minimum };
    });
  });
  const cardAction = page.locator('.card .card-action a:not(.btn)').first();
  const measureCardActionContrast = async (state) => cardAction.evaluate((element, interactionState) => {
    const parseColor = (value) => (value.match(/[\d.]+/g)?.map(Number) || []).slice(0, 3);
    const luminance = ([red, green, blue]) => {
      const channels = [red, green, blue].map((channel) => {
        const normalized = channel / 255;
        return normalized <= 0.03928
          ? normalized / 12.92
          : ((normalized + 0.055) / 1.055) ** 2.4;
      });
      return (0.2126 * channels[0]) + (0.7152 * channels[1]) + (0.0722 * channels[2]);
    };
    let backgroundElement = element;
    let background = [255, 255, 255];
    while (backgroundElement) {
      const value = getComputedStyle(backgroundElement).backgroundColor;
      if (value !== 'rgba(0, 0, 0, 0)' && value !== 'transparent') {
        background = parseColor(value);
        break;
      }
      backgroundElement = backgroundElement.parentElement;
    }
    const foregroundLuminance = luminance(parseColor(getComputedStyle(element).color));
    const backgroundLuminance = luminance(background);
    const ratio = (Math.max(foregroundLuminance, backgroundLuminance) + 0.05)
      / (Math.min(foregroundLuminance, backgroundLuminance) + 0.05);
    return {
      selector: '.card .card-action a:not(.btn)',
      state: interactionState,
      minimum: 4.5,
      ratio: Number(ratio.toFixed(2)),
      pass: ratio >= 4.5,
    };
  }, state);
  await cardAction.hover();
  await page.waitForTimeout(350);
  const cardActionHoverContrast = await measureCardActionContrast('hover');
  await cardAction.focus();
  await page.waitForTimeout(350);
  const cardActionFocusContrast = await measureCardActionContrast('focus');
  const interactionContrastChecks = [cardActionHoverContrast, cardActionFocusContrast];
  const actionHeight = await page.locator('.preservation-header__button').evaluate((element) => (
    Math.round(element.getBoundingClientRect().height)
  ));

  const screenshotPath = path.join(outputDirectory, `${name}-viewport.png`);
  if (!verifyOnly) {
    await page.screenshot({ path: screenshotPath, fullPage: false });
  }

  let navigationScreenshot = null;
  let navigationError = null;
  if (name === 'mobile' && !verifyOnly) {
    try {
      await page.evaluate(() => window.scrollTo(0, 0));
      await page.locator('.archive-menu-button').click({ force: true });
      await page.waitForTimeout(350);
      navigationScreenshot = path.join(outputDirectory, 'mobile-navigation.png');
      await page.screenshot({ path: navigationScreenshot, fullPage: false });
    } catch (error) {
      navigationError = error.message;
    }
  }

  await page.close();

  return {
    name,
    viewport,
    status: response ? response.status() : null,
    title,
    emailHref,
    logoLoaded,
    documentWidth,
    hasHorizontalOverflow,
    contrastChecks,
    interactionContrastChecks,
    actionHeight,
    consoleErrors,
    pageErrors,
    screenshotPath: verifyOnly ? null : screenshotPath,
    navigationScreenshot,
    navigationError,
  };
}

(async () => {
  fs.mkdirSync(outputDirectory, { recursive: true });
  const browser = await chromium.launch({ headless: true });

  try {
    const results = [];
    results.push(await reviewViewport(browser, 'desktop', { width: 1440, height: 1000 }));
    results.push(await reviewViewport(browser, 'tablet', { width: 820, height: 1180 }));
    results.push(await reviewViewport(browser, 'mobile', { width: 390, height: 844 }));

    const report = {
      baseUrl,
      capturedAt: new Date().toISOString(),
      results,
    };
    fs.writeFileSync(path.join(outputDirectory, 'report.json'), `${JSON.stringify(report, null, 2)}\n`);
    process.stdout.write(`${JSON.stringify(report, null, 2)}\n`);

    const failed = results.some((result) => (
      result.status !== 200
      || !result.logoLoaded
      || result.hasHorizontalOverflow
      || result.contrastChecks.some((check) => !check.pass)
      || result.interactionContrastChecks.some((check) => !check.pass)
      || result.actionHeight < 44
      || result.consoleErrors.length > 0
      || result.pageErrors.length > 0
      || !result.emailHref?.startsWith('mailto:')
    ));
    process.exitCode = failed ? 1 : 0;
  } finally {
    await browser.close();
  }
})().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
