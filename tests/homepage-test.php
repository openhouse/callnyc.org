<?php

declare(strict_types=1);

require_once __DIR__ . '/test-helper.php';

$homepageComponent = __DIR__ . '/../components/contemporary-home.php';
if (is_file($homepageComponent)) {
  require_once $homepageComponent;
}

test_case('the contemporary homepage gives visitors a truthful front door', function (): void {
  assert_true(
    function_exists('render_contemporary_home'),
    'Expected render_contemporary_home() to render the new public front door'
  );

  $html = render_contemporary_home();
  assert_contains('The public record stopped updating.<br>The work did not.', $html);
  assert_contains('href="/archive/2016/"', $html);
  assert_contains('href="https://council.nyc.gov/districts/"', $html);
  assert_contains('href="https://portal.311.nyc.gov/"', $html);
  assert_true(!str_contains($html, 'up-to-today'), 'Contemporary copy must not describe historical data as current');
});

test_case('the advocacy request is sourced and bounded', function (): void {
  $html = render_contemporary_home();
  assert_contains('What the sources establish', $html);
  assert_contains('No official explanation for that change has been located.', $html);
  assert_contains('privacy-protected', $html);
  assert_contains('https://data.cityofnewyork.us/d/b9km-gdpy', $html);
  assert_contains('https://council.nyc.gov/amanda-farias/join-our-team-constituent-liaison/', $html);
  assert_contains('https://opendata.cityofnewyork.us/overview/', $html);
});

test_case('the Politico evidence is visible, attributed, and linked', function (): void {
  $html = render_contemporary_home();
  assert_contains('/data/media/politico-callnyc-2016-page-1.png', $html);
  assert_contains('Screenshot of the March 14, 2016 Politico New York article', $html);
  assert_contains('/data/media/Politico-Website-provides-new-information-about-council-members-focus.pdf', $html);
  assert_contains('Miranda Neubauer', $html);
});

test_case('the design direction contract survives in rendered markup', function (): void {
  $html = render_contemporary_home();
  assert_true(
    preg_match('/<body[^>]*>\s*<!--\s*THESIS:/s', $html) === 1,
    'Expected the design contract to be the first child of body'
  );
  assert_contains('FORM: Open public letter', $html);
  assert_contains('seed a96de65f', $html);
});

finish_tests();

