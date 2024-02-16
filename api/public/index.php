<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';
require(__DIR__ . '/../src/Routes.php');

use App\Core\App;
use Dotenv\Dotenv;

// Load custom environment variables
$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();

function dump($a, $b = false, $c = false) {
	var_dump($a);

	if ($b) {
		var_dump($b);
	}

	if ($c) {
		var_dump($c);
	}
	
	die;
}

// Start the app
$app = App::getInstance();
$app->start();
