<?php

declare(strict_types=1);

require_once __DIR__ . '/test-helper.php';

$validator = __DIR__ . '/../library/knowledge-graph.php';
if (is_file($validator)) {
  require_once $validator;
}

test_case('a graph with unique nodes and resolved edges is valid', function (): void {
  assert_true(
    function_exists('validate_knowledge_graph'),
    'Expected validate_knowledge_graph() to protect graph integrity'
  );

  $graph = [
    'nodes' => [
      ['id' => 'project.callnyc'],
      ['id' => 'system.council-connect'],
    ],
    'edges' => [
      ['source' => 'project.callnyc', 'target' => 'system.council-connect'],
    ],
  ];

  assert_same([], validate_knowledge_graph($graph));
});

test_case('duplicate node ids are rejected', function (): void {
  $graph = [
    'nodes' => [
      ['id' => 'project.callnyc'],
      ['id' => 'project.callnyc'],
    ],
    'edges' => [],
  ];

  assert_same(['duplicate node id: project.callnyc'], validate_knowledge_graph($graph));
});

test_case('edges cannot point to missing nodes', function (): void {
  $graph = [
    'nodes' => [
      ['id' => 'project.callnyc'],
    ],
    'edges' => [
      ['source' => 'project.callnyc', 'target' => 'system.missing'],
    ],
  ];

  assert_same(
    ['edge project.callnyc -> system.missing references missing target'],
    validate_knowledge_graph($graph)
  );
});

test_case('the project knowledge graph resolves every declared relationship', function (): void {
  $path = __DIR__ . '/../docs/knowledge-bank/data/graph.json';
  assert_true(is_file($path), 'Expected the project-internal graph at docs/knowledge-bank/data/graph.json');
  $graph = load_knowledge_graph($path);
  assert_same([], validate_knowledge_graph($graph));
});

finish_tests();
