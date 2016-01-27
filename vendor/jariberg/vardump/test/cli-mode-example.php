<?php

require __DIR__ . '/../src/Vardump.php';

$fixtures = require __DIR__ . '/_fixtures.php';

// NB! CLI mode is autodetected when running from command line
// This example forces CLI mode for display in a browser.

$dump = new Vardump();
$dump->setStylingModeCli();
$dump->dump($fixtures);

echo 'EOF';