<?php

declare(strict_types=1);

require_once __DIR__ . '/test-helper.php';

$source = file_get_contents(__DIR__ . '/../index.php');
$preservationComponent = __DIR__ . '/../components/preservation-notice.php';
require_once $preservationComponent;

test_case('the archive shell has a clear historical boundary', function () use ($source, $preservationComponent): void {
  $notice = render_preservation_notice();
  assert_contains('render_preservation_notice()', $source);
  assert_contains('Archived and unofficial', $notice);
  assert_contains('/data/media/politico-callnyc-2016-page-1.png', $notice);
  assert_contains('advocacy_mailto_url()', file_get_contents($preservationComponent));
  assert_true(!str_contains($source, 'tel:<?php'), 'Historical cards must not emit live calls to 2016 phone numbers');
  assert_contains('Find the current Council contact', $source);
});

test_case('the archive shell no longer relies on broken presentation dependencies', function () use ($source): void {
  assert_true(!str_contains($source, 'fonts.googleapis.com'), 'Archive fonts must be self-hosted');
  assert_true(!str_contains($source, '<object'), 'The CallNYC SVG must use a dependable img element');
  assert_contains('<img id="front-page-logo"', $source);
  assert_contains('/css/archive-2026.css', $source);
  assert_contains('aria-label="Open archive navigation"', $source);
  assert_true(!str_contains($source, 'mdi-navigation-menu'), 'Archive menu must use its explicit local SVG control');
  assert_true(!str_contains($source, 'user-scalable=no'), 'Archive viewport must permit user zoom');
  assert_true(!str_contains($source, 'maximum-scale=1.0'), 'Archive viewport must permit user zoom');
  assert_true(!str_contains($source, 'code.jquery.com'), 'The preserved interaction must not depend on a third-party CDN');
  assert_contains('<script src="/bin/jquery-2.2.1.min.js"></script>', $source);
});

test_case('artifact navigation preserves the original root-level routes', function () use ($source): void {
  assert_contains("artifact_url('/' . \$memberCategory", $source);
  assert_contains('artifact_url(\'/\' . $topCategory', $source);
  assert_true(!str_contains($source, 'up-to-today'), 'Historical copy must not claim a current view');
  assert_true(!str_contains($source, 'offers <b>free personal assistance</b>'), 'Archive hero must not present 2016 copy as current service');
  assert_contains('This is a historical reconstruction.', $source);
});

test_case('archive search results stay within the archive boundary', function (): void {
  $search = file_get_contents(__DIR__ . '/../search.php');
  assert_contains("href: '<?php echo artifact_url", $search);
});

finish_tests();
