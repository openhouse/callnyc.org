<?php

declare(strict_types=1);

$GLOBALS['callnyc_test_failures'] = 0;
$GLOBALS['callnyc_test_count'] = 0;

function test_case(string $name, callable $test): void {
  $GLOBALS['callnyc_test_count']++;

  try {
    $test();
    fwrite(STDOUT, "PASS {$name}\n");
  } catch (Throwable $error) {
    $GLOBALS['callnyc_test_failures']++;
    fwrite(STDERR, "FAIL {$name}\n  {$error->getMessage()}\n");
  }
}

function assert_same(mixed $expected, mixed $actual): void {
  if ($expected !== $actual) {
    throw new RuntimeException(
      'Expected ' . var_export($expected, true) . ', received ' . var_export($actual, true)
    );
  }
}

function assert_true(bool $condition, string $message): void {
  if (!$condition) {
    throw new RuntimeException($message);
  }
}

function assert_contains(string $needle, string $haystack): void {
  if (!str_contains($haystack, $needle)) {
    throw new RuntimeException("Expected output to contain: {$needle}");
  }
}

function finish_tests(): never {
  $count = $GLOBALS['callnyc_test_count'];
  $failures = $GLOBALS['callnyc_test_failures'];
  fwrite(STDOUT, "\n{$count} tests, {$failures} failures\n");
  exit($failures === 0 ? 0 : 1);
}

