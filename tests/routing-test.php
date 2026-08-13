<?php

declare(strict_types=1);

require_once __DIR__ . '/test-helper.php';
require_once __DIR__ . '/../functions.php';

test_case('the original artifact owns both the root and preserved archive aliases', function (): void {
  assert_true(
    function_exists('legacy_request_path'),
    'Expected legacy_request_path() to strip the archive route prefix'
  );
  assert_same('/', legacy_request_path('/'));
  assert_same('/housing/heat.html', legacy_request_path('/housing/heat.html?from=nav'));
  assert_same('/', legacy_request_path('/archive/2016/'));
  assert_same('/housing/heat.html', legacy_request_path('/archive/2016/housing/heat.html?from=nav'));
});

test_case('newly rendered links stay on the original root-level routes', function (): void {
  assert_true(
    function_exists('artifact_url'),
    'Expected artifact_url() to preserve the original public route structure'
  );
  assert_same('/', artifact_url('/'));
  assert_same('/housing/heat.html', artifact_url('/housing/heat.html'));
});

finish_tests();
