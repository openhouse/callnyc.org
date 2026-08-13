<?php

declare(strict_types=1);

$testFiles = [
  __DIR__ . '/routing-test.php',
  __DIR__ . '/preservation-header-test.php',
  __DIR__ . '/health-test.php',
  __DIR__ . '/archive-shell-test.php',
  __DIR__ . '/knowledge-graph-test.php',
];

foreach ($testFiles as $testFile) {
  passthru('php ' . escapeshellarg($testFile), $exitCode);
  if ($exitCode !== 0) {
    exit($exitCode);
  }
}
