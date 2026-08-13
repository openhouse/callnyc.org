<?php

declare(strict_types=1);

require_once __DIR__ . '/test-helper.php';

test_case('the bare root renders the original interactive artifact', function (): void {
  $index = realpath(__DIR__ . '/../index.php');
  $root = dirname((string)$index);
  $script = '$_SERVER["REQUEST_URI"]="/"; $_SERVER["HTTP_HOST"]="callnyc.test"; chdir(' . var_export($root, true) . '); include ' . var_export($index, true) . ';';
  $command = escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg($script) . ' 2>&1';

  exec($command, $lines, $exitCode);
  $html = implode("\n", $lines);

  assert_same(0, $exitCode);
  assert_contains('id="nav-mobile"', $html);
  assert_contains('id="index-banner"', $html);
  assert_contains('class="preservation-notice"', $html);
  assert_contains('Call NYC', $html);
  assert_true(!str_contains($html, 'The public record stopped updating.'), 'The discarded contemporary landing page must not intercept the artifact');
});

test_case('the artifact honors the staging noindex boundary', function (): void {
  $index = realpath(__DIR__ . '/../index.php');
  $root = dirname((string)$index);
  $script = 'putenv("CALLNYC_ROBOTS=noindex"); $_SERVER["REQUEST_URI"]="/"; $_SERVER["HTTP_HOST"]="callnyc.test"; chdir(' . var_export($root, true) . '); include ' . var_export($index, true) . ';';
  $command = escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg($script) . ' 2>&1';

  exec($command, $lines, $exitCode);
  $html = implode("\n", $lines);

  assert_same(0, $exitCode);
  assert_contains('<meta name="robots" content="noindex, nofollow">', $html);
  assert_contains('id="index-banner"', $html);
});

finish_tests();
