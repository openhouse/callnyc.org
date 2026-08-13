#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../library/knowledge-graph.php';

$path = __DIR__ . '/../docs/knowledge-bank/data/graph.json';

try {
  $errors = validate_knowledge_graph(load_knowledge_graph($path));
} catch (Throwable $error) {
  fwrite(STDERR, $error->getMessage() . PHP_EOL);
  exit(1);
}

if ($errors !== []) {
  foreach ($errors as $error) {
    fwrite(STDERR, $error . PHP_EOL);
  }
  exit(1);
}

fwrite(STDOUT, "Knowledge graph is valid.\n");

