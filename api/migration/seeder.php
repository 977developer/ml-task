<?php

require __DIR__.'/../vendor/autoload.php';

use App\Models\Subscribers;
use App\Core\Db;
use Dotenv\Dotenv;

// Load custom environment variables
$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();

// Create subscibers table if not exist
$db = new Db();
$db->createTable();

// Load faker
$faker = Faker\Factory::create();

// Insert
for ($i = 1; $i <= 10000; $i++) { 
	Subscribers::insertDB([
		'firstName' => $faker->firstname,
		'lastName' => $faker->lastname,
		'email' => $faker->unique()->safeEmail,
		'status' => rand(0,1),
	], false);
}

die;
