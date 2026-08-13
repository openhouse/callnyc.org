<?php

declare(strict_types=1);

require_once __DIR__ . '/test-helper.php';

$component = __DIR__ . '/../components/preservation-header.php';
if (is_file($component)) {
  require_once $component;
}

test_case('the preservation header keeps the 2016 project primary', function (): void {
  assert_true(
    function_exists('render_preservation_header'),
    'Expected render_preservation_header() to provide the compact archive context'
  );

  $html = render_preservation_header();
  assert_contains('Archived and unofficial', $html);
  assert_contains('Built by Jamie Burkart in a 24-hour sprint', $html);
  assert_contains('NYC Open Data lists the public constituent-services dataset as historical.', $html);
  assert_contains('No current Council Connect export has been located.', $html);
  assert_true(
    !str_contains($html, 'NYC Council no longer publishes'),
    'The public header must not overstate the bounded finding'
  );
  assert_contains('Tell NYC Council and NYC Open Data Portal:', $html);
  assert_contains('restore constituent services data publishing', $html);
  assert_contains('red darken-3 preservation-header__button', $html);
  assert_contains('<p id="preservation-title" class="preservation-header__title">', $html);
  assert_true(!str_contains($html, '<h2'), 'The preservation label must not precede the page h1 in heading order');
});

test_case('the Politico artifact is visible, attributed, and linked', function (): void {
  $html = render_preservation_header();
  assert_contains('/data/media/politico-callnyc-2016-page-1.png', $html);
  assert_contains('Screenshot of the March 14, 2016 POLITICO New York article about CallNYC', $html);
  assert_contains('/data/media/Politico-Website-provides-new-information-about-council-members-focus.pdf', $html);
  assert_contains('As seen in POLITICO New York', $html);
});

test_case('the email action addresses both public-data stewards with bounded language', function (): void {
  assert_true(
    function_exists('advocacy_mailto_url'),
    'Expected advocacy_mailto_url() to build the pre-populated email action'
  );

  $mailto = advocacy_mailto_url();
  assert_true(
    str_starts_with($mailto, 'mailto:District10@council.nyc.gov,opendatateam@oti.nyc.gov?'),
    'Expected the action to address the current Council Technology chair and NYC Open Data team'
  );

  $query = parse_url($mailto, PHP_URL_QUERY);
  assert_true(is_string($query), 'Expected a populated mailto query');
  parse_str($query, $fields);
  assert_same('Restore constituent services data publishing', $fields['subject'] ?? null);
  assert_contains('privacy-protected', $fields['body'] ?? '');
  assert_contains('Council Connect', $fields['body'] ?? '');
  assert_contains('NYC Open Data Portal', $fields['body'] ?? '');
});

finish_tests();
