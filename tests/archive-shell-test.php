<?php

declare(strict_types=1);

require_once __DIR__ . '/test-helper.php';

$source = file_get_contents(__DIR__ . '/../index.php');

test_case('the archive shell has a clear historical boundary', function () use ($source): void {
  assert_contains('class="archive-boundary"', $source);
  assert_contains('Archived and unofficial', $source);
  assert_contains('/data/media/politico-callnyc-2016-page-1.png', $source);
  assert_contains('href="/#restore"', $source);
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
});

test_case('current official help precedes advocacy on narrow screens', function (): void {
  $css = file_get_contents(__DIR__ . '/../css/contemporary.css');
  assert_contains('.help-now { order: -1;', $css);
});

test_case('new archive navigation stays within the archive boundary', function () use ($source): void {
  assert_contains("archive_url('/' . \$memberCategory", $source);
  assert_contains('archive_url(\'/\' . $topCategory', $source);
  assert_true(!str_contains($source, 'up-to-today'), 'Historical copy must not claim a current view');
  assert_true(!str_contains($source, 'offers <b>free personal assistance</b>'), 'Archive hero must not present 2016 copy as current service');
  assert_contains('This is a historical reconstruction.', $source);
});

test_case('archive search results stay within the archive boundary', function (): void {
  $search = file_get_contents(__DIR__ . '/../search.php');
  assert_contains("href: '<?php echo archive_url", $search);
});

finish_tests();
