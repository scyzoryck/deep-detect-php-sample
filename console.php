#!/usr/bin/php
<?php
require __DIR__ . '/vendor/autoload.php';

use Scyzoryck\DeepDetectSample\Command;
use Symfony\Component\Console\Application;

$config = require __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
$client = new \GuzzleHttp\Client(['base_uri' => $config['deep-detect-url']]);

$application = new Application();
$application->add(new Command\CheckStatusCommand($client));
$application->add(new Command\InitCommand($client));
$application->add(new Command\RunCommand($client));
$application->run();
