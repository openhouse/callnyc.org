<?php

declare(strict_types=1);

$url = getenv('CALLNYC_EVAL_URL') ?: 'http://localhost:5000/';
$html = @file_get_contents($url);

if (!is_string($html) || $html === '') {
  fwrite(STDERR, "Unable to load evaluation target: {$url}\n");
  exit(2);
}

$contains = static fn(string $needle): bool => str_contains($html, $needle);
$checks = [
  [
    'id' => 'artifact-first',
    'weight' => 30,
    'pass' => $contains('id="nav-mobile"') && $contains('id="index-banner"') && $contains('class="card"'),
    'evidence' => 'Original navigation, category banner, and ranking cards render at the public root.',
  ],
  [
    'id' => 'single-surface',
    'weight' => 10,
    'pass' => !$contains('href="/archive/2016/"') && !$contains('The public record stopped updating.'),
    'evidence' => 'No successor landing page or second-page gate stands between the reader and the artifact.',
  ],
  [
    'id' => 'preservation-truth',
    'weight' => 15,
    'pass' => $contains('Archived and unofficial') && $contains('Built in a 24-hour sprint') && $contains('historical reconstruction'),
    'evidence' => 'The current wrapper identifies the work, its sprint context, and its archival status.',
  ],
  [
    'id' => 'politico-evidence',
    'weight' => 15,
    'pass' => $contains('/data/media/politico-callnyc-2016-page-1.png') && $contains('As seen in Politico New York') && $contains('Politico-Website-provides-new-information-about-council-members-focus.pdf'),
    'evidence' => 'The real preserved Politico thumbnail is visible, attributed, and linked to the archived PDF.',
  ],
  [
    'id' => 'prefilled-civic-action',
    'weight' => 20,
    'pass' => $contains('mailto:Data@council.nyc.gov,opendata@oti.nyc.gov?') && $contains('Restore%20constituent%20services%20data%20publishing'),
    'evidence' => 'The CTA opens a pre-addressed and pre-populated email to the Council and OTI data teams.',
  ],
  [
    'id' => 'access-and-safety',
    'weight' => 10,
    'pass' => $contains('class="skip-link"')
      && $contains('aria-label="Open archive navigation"')
      && $contains('<script src="/bin/jquery-2.2.1.min.js"></script>')
      && !$contains('href="tel:')
      && !$contains('code.jquery.com'),
    'evidence' => 'The page retains keyboard entry points, uses its local runtime, and does not reactivate historical phone actions.',
  ],
];

$score = 0;
foreach ($checks as &$check) {
  if ($check['pass']) {
    $score += $check['weight'];
  }
}
unset($check);

$result = [
  'target' => $url,
  'score' => $score,
  'possible' => 100,
  'checks' => $checks,
];

fwrite(STDOUT, json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
exit($score === 100 ? 0 : 1);
