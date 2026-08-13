<?php

declare(strict_types=1);

require_once __DIR__ . '/test-helper.php';

test_case('the deployment health endpoint is database-independent', function (): void {
  $health = realpath(__DIR__ . '/../health.php');
  assert_true(is_string($health), 'Expected health.php at the application root');

  $script = 'include ' . var_export($health, true) . ';';
  exec(escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg($script) . ' 2>&1', $lines, $exitCode);
  $payload = json_decode(implode("\n", $lines), true);

  assert_same(0, $exitCode);
  assert_same('ok', $payload['status'] ?? null);
  assert_same('callnyc', $payload['service'] ?? null);
});

finish_tests();

