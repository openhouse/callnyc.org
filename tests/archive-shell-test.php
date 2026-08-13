<?php

declare(strict_types=1);

require_once __DIR__ . '/test-helper.php';

$source = file_get_contents(__DIR__ . '/../index.php');

test_case('the original interface renders its compact preservation header', function () use ($source): void {
  assert_contains('render_preservation_header()', $source);
  assert_true(!str_contains($source, 'tel:<?php'), 'Historical cards must not emit live calls to 2016 phone numbers');
  assert_contains('Find the current Council contact', $source);
  assert_contains('alt="Historical photograph of <?php echo htmlspecialchars($member[\'name\']', $source);
  assert_contains('target="_blank" rel="noopener" href="https://web.archive.org/', $source);
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
  assert_true(!str_contains($source, 'mailto:contact@callnyc.org'), 'The archive must not publish an unverified project mailbox');
});

test_case('archive navigation keeps the original direct route structure', function () use ($source): void {
  assert_contains("archive_url('/' . \$memberCategory", $source);
  assert_contains('archive_url(\'/\' . $topCategory', $source);
  assert_true(!str_contains($source, 'up-to-today'), 'Historical copy must not claim a current view');
  assert_true(!str_contains($source, 'offers <b>free personal assistance</b>'), 'Archive hero must not present 2016 copy as current service');
  assert_contains('This is a historical reconstruction.', $source);
});

test_case('archive search results use the original route helper', function (): void {
  $search = file_get_contents(__DIR__ . '/../search.php');
  assert_contains("href: '<?php echo archive_url", $search);
});

finish_tests();
