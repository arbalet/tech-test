<?php

require __DIR__ . '/../src/Vardump.php';
require __DIR__ . '/../autoload.php';

$fixtures = require __DIR__ . '/_fixtures.php';

vardump($fixtures);

vardump_html($fixtures);
