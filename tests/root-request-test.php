<?php

declare(strict_types=1);

require_once __DIR__ . '/test-helper.php';

test_case('the bare root renders without opening the archive database', function (): void {
  $index = realpath(__DIR__ . '/../index.php');
  $root = dirname((string)$index);
  $script = '$_SERVER["REQUEST_URI"]="/"; $_SERVER["HTTP_HOST"]="callnyc.test"; chdir(' . var_export($root, true) . '); include ' . var_export($index, true) . ';';
  $command = escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg($script) . ' 2>&1';

  exec($command, $lines, $exitCode);
  $html = implode("\n", $lines);

  assert_same(0, $exitCode);
  assert_contains('The public record stopped updating.<br>The work did not.', $html);
  assert_true(!str_contains($html, 'SQLSTATE'), 'The contemporary root must not depend on MySQL');
});

finish_tests();

