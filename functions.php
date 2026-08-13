<?php
function slugify($text) {
  /*
  $text = transliterator_transliterate("Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();", trim($text));
  $text = str_replace(' ', '-', $text);
  $text = preg_replace('~-+~', '-', $text);
  if (empty($text))
  {
    return 'n-a';
  }
  return $text;
  */

  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text))
  {
    return 'n-a';
  }

  return $text;

}

function base_url(): string {
  $scheme = 'http';
  if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    $scheme = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_PROTO'])[0]);
  } elseif (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    $scheme = 'https';
  }

  $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

  return $scheme . '://' . $host;
}

function is_archived(): bool {
  $value = getenv('CALLNYC_ARCHIVED');
  if ($value === false || $value === '') {
    return false;
  }

  $parsed = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
  if ($parsed === null) {
    return $value !== '0';
  }

  return $parsed;
}

function request_path(string $requestUri): string {
  $path = parse_url($requestUri, PHP_URL_PATH);
  if (!is_string($path) || $path === '') {
    return '/';
  }

  return '/' . ltrim($path, '/');
}

function legacy_request_path(string $requestUri): string {
  $path = request_path($requestUri);
  $archivePrefix = '/archive/2016';

  if ($path === $archivePrefix || $path === $archivePrefix . '/') {
    return '/';
  }

  if (str_starts_with($path, $archivePrefix . '/')) {
    return substr($path, strlen($archivePrefix));
  }

  return $path;
}

function artifact_url(string $path = '/'): string {
  return request_path($path);
}

function advocacy_mailto_url(): string {
  $subject = 'Restore constituent services data publishing';
  $body = implode("\n\n", [
    'To the NYC Council Data Team and NYC Open Data Team:',
    'Please restore regular, privacy-protected publication of NYC Council constituent-services data from Council Connect through the NYC Open Data Portal.',
    'A useful public release should be aggregate or de-identified, machine-readable, documented with a data dictionary and update schedule, and protect residents’ case details.',
    'The historical dataset and the CallNYC archive show the civic value of this public record.',
    'Thank you.',
  ]);

  return 'mailto:Data@council.nyc.gov,opendata@oti.nyc.gov?' . http_build_query(
    ['subject' => $subject, 'body' => $body],
    '',
    '&',
    PHP_QUERY_RFC3986
  );
}
?>
