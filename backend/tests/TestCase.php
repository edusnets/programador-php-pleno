<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication;
	public static $migrationsRun = false;

	public function setUp(){
		parent::setUp();

		if(!static::$migrationsRun) {
			echo "\n => Criando arquivo .env de test\n";
			Artisan::call('config:clear');
			Artisan::call('config:cache');

			if(\Illuminate\Support\Facades\Schema::hasTable('migrations')){
				echo " => Database Rollback\n";
				Artisan::call('migrate:rollback');
			}

			echo " => Make migrations\n";
			Artisan::call('migrate');

			static::$migrationsRun = true;
		}
	}
}
