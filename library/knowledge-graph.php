<?php

declare(strict_types=1);

function validate_knowledge_graph(array $graph): array {
  $errors = [];
  $ids = [];

  foreach ($graph['nodes'] ?? [] as $node) {
    $id = $node['id'] ?? null;
    if (!is_string($id) || $id === '') {
      $errors[] = 'node is missing a non-empty id';
      continue;
    }
    if (isset($ids[$id])) {
      $errors[] = 'duplicate node id: ' . $id;
      continue;
    }
    $ids[$id] = true;
  }

  foreach ($graph['edges'] ?? [] as $edge) {
    $source = $edge['source'] ?? '';
    $target = $edge['target'] ?? '';
    if (!isset($ids[$source])) {
      $errors[] = 'edge ' . $source . ' -> ' . $target . ' references missing source';
    }
    if (!isset($ids[$target])) {
      $errors[] = 'edge ' . $source . ' -> ' . $target . ' references missing target';
    }
  }

  return $errors;
}

function load_knowledge_graph(string $path): array {
  $json = file_get_contents($path);
  if ($json === false) {
    throw new RuntimeException('Unable to read knowledge graph: ' . $path);
  }
  $graph = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
  if (!is_array($graph)) {
    throw new RuntimeException('Knowledge graph must decode to an object');
  }
  return $graph;
}

