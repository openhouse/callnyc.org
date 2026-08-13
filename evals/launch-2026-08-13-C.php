<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$index = file_get_contents($root . '/index.php');
$functions = file_get_contents($root . '/functions.php');
$archiveCss = file_get_contents($root . '/css/archive-2026.css');
$contemporaryComponentPath = $root . '/components/contemporary-home.php';
$contemporaryCssPath = $root . '/css/contemporary.css';
$preservationComponentPath = $root . '/components/preservation-header.php';
$preservationHtml = '';

require_once $root . '/functions.php';
if (is_file($preservationComponentPath)) {
  require_once $preservationComponentPath;
  $preservationHtml = render_preservation_header();
}

$criteria = [
  'original_experience_is_root' => [
    'weight' => 20,
    'pass' => !str_contains($index, 'render_contemporary_home') && legacy_request_path('/') === '/',
  ],
  'no_extra_public_pages' => [
    'weight' => 10,
    'pass' => !is_file($contemporaryComponentPath) && archive_url('/') === '/',
  ],
  'original_materialize_visual_system' => [
    'weight' => 10,
    'pass' => str_contains($index, '/css/ghpages-materialize.css')
      && !is_file($contemporaryCssPath)
      && !str_contains($archiveCss, 'Karla')
      && !str_contains($archiveCss, 'Oswald'),
  ],
  'compact_preservation_header' => [
    'weight' => 10,
    'pass' => str_contains($preservationHtml, 'class="preservation-header"')
      && str_contains($preservationHtml, 'Built by Jamie Burkart in a 24-hour sprint'),
  ],
  'politico_artifact' => [
    'weight' => 10,
    'pass' => str_contains($preservationHtml, 'politico-callnyc-2016-page-1.png')
      && str_contains($preservationHtml, 'Politico-Website-provides-new-information-about-council-members-focus.pdf'),
  ],
  'prepopulated_email_action' => [
    'weight' => 15,
    'pass' => str_contains($preservationHtml, 'mailto:District10@council.nyc.gov,opendatateam@oti.nyc.gov?')
      && str_contains($preservationHtml, 'privacy-protected')
      && str_contains($preservationHtml, 'Council%20Connect'),
  ],
  'accurate_archive_boundary' => [
    'weight' => 10,
    'pass' => str_contains($preservationHtml, 'Archived and unofficial')
      && str_contains($preservationHtml, 'dataset as historical')
      && str_contains($preservationHtml, 'No current Council Connect export has been located')
      && !str_contains($preservationHtml, 'does not provide constituent services'),
  ],
  'safe_current_help' => [
    'weight' => 5,
    'pass' => str_contains($index, 'https://council.nyc.gov/districts/')
      && !str_contains($index, 'tel:<?php'),
  ],
  'accessible_archive_shell' => [
    'weight' => 5,
    'pass' => !str_contains($index, 'user-scalable=no')
      && str_contains($index, 'aria-label="Open archive navigation"')
      && str_contains($index, '<img id="front-page-logo"'),
  ],
  'self_hosted_presentation' => [
    'weight' => 5,
    'pass' => !str_contains($index . $preservationHtml, 'fonts.googleapis.com'),
  ],
];

$score = 0;
foreach ($criteria as &$criterion) {
  if ($criterion['pass']) {
    $score += $criterion['weight'];
  }
}

echo json_encode([
  'eval' => 'launch-2026-08-13-C',
  'score' => $score,
  'max_score' => 100,
  'criteria' => $criteria,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;

exit($score === 100 ? 0 : 1);
