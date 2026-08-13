<?php

declare(strict_types=1);

require_once __DIR__ . '/test-helper.php';

$preservationComponent = __DIR__ . '/../components/preservation-notice.php';
if (is_file($preservationComponent)) {
  require_once $preservationComponent;
}

test_case('the preservation notice lets the original artifact lead', function (): void {
  assert_true(
    function_exists('render_preservation_notice'),
    'Expected render_preservation_notice() to provide the compact archival context'
  );

  $html = render_preservation_notice();
  assert_contains('Archived and unofficial', $html);
  assert_contains('Built in a 24-hour sprint', $html);
  assert_contains('href="https://council.nyc.gov/districts/"', $html);
  assert_true(!str_contains($html, 'href="/archive/2016/"'), 'The notice must not make visitors enter a second page to reach the artifact');
});

test_case('the click-to-email action opens a bounded draft to both responsible data teams', function (): void {
  assert_true(
    function_exists('advocacy_mailto_url'),
    'Expected advocacy_mailto_url() to build the public action'
  );

  $url = advocacy_mailto_url();
  assert_true(str_starts_with($url, 'mailto:Data@council.nyc.gov,opendata@oti.nyc.gov?'), 'Expected both verified institutional recipients');

  $query = parse_url($url, PHP_URL_QUERY);
  parse_str((string)$query, $fields);
  assert_same('Restore constituent services data publishing', $fields['subject'] ?? null);
  assert_contains('privacy-protected', $fields['body'] ?? '');
  assert_contains('Council Connect', $fields['body'] ?? '');
  assert_contains('NYC Open Data Portal', $fields['body'] ?? '');
});

test_case('the Politico evidence is visible, attributed, and linked', function (): void {
  $html = render_preservation_notice();
  assert_contains('/data/media/politico-callnyc-2016-page-1.png', $html);
  assert_contains('Screenshot of the March 14, 2016 Politico New York article', $html);
  assert_contains('/data/media/Politico-Website-provides-new-information-about-council-members-focus.pdf', $html);
  assert_contains('As seen in Politico New York', $html);
});

finish_tests();
