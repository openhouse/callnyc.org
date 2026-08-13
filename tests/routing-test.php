<?php

declare(strict_types=1);

require_once __DIR__ . '/test-helper.php';
require_once __DIR__ . '/../functions.php';

test_case('the bare root is the contemporary publication', function (): void {
  assert_true(
    function_exists('is_contemporary_home'),
    'Expected is_contemporary_home() to define the contemporary root boundary'
  );
  assert_same(true, is_contemporary_home('/'));
  assert_same(true, is_contemporary_home('/?source=archive'));
  assert_same(false, is_contemporary_home('/archive/2016/'));
});

test_case('archive request paths are normalized for the legacy router', function (): void {
  assert_true(
    function_exists('legacy_request_path'),
    'Expected legacy_request_path() to strip the archive route prefix'
  );
  assert_same('/', legacy_request_path('/archive/2016/'));
  assert_same('/housing/heat.html', legacy_request_path('/archive/2016/housing/heat.html?from=nav'));
});

test_case('legacy links remain inside the archive boundary', function (): void {
  assert_true(
    function_exists('archive_url'),
    'Expected archive_url() to keep historical links inside the archive boundary'
  );
  assert_same('/archive/2016/', archive_url('/'));
  assert_same('/archive/2016/housing/heat.html', archive_url('/housing/heat.html'));
});

finish_tests();

