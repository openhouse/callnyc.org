<?php

declare(strict_types=1);

require_once __DIR__ . '/test-helper.php';
require_once __DIR__ . '/../functions.php';

test_case('archive safety fails closed when deployment config is absent', function (): void {
  putenv('CALLNYC_ARCHIVED');
  assert_same(true, is_archived());

  putenv('CALLNYC_ARCHIVED=0');
  assert_same(false, is_archived());

  putenv('CALLNYC_ARCHIVED');
});

test_case('the bare root opens the preserved CallNYC experience', function (): void {
  assert_same('/', legacy_request_path('/'));
  assert_same('/', legacy_request_path('/?source=press'));
});

test_case('archive request paths are normalized for the legacy router', function (): void {
  assert_true(
    function_exists('legacy_request_path'),
    'Expected legacy_request_path() to strip the archive route prefix'
  );
  assert_same('/', legacy_request_path('/archive/2016/'));
  assert_same('/housing/heat.html', legacy_request_path('/archive/2016/housing/heat.html?from=nav'));
});

test_case('historical links stay on the original public paths', function (): void {
  assert_true(
    function_exists('archive_url'),
    'Expected archive_url() to keep the original route structure'
  );
  assert_same('/', archive_url('/'));
  assert_same('/housing/heat.html', archive_url('/housing/heat.html'));
});

finish_tests();
