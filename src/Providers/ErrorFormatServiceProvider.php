<?php

namespace Johnvict\ErrorFormat\Providers;


use Illuminate\Support\ServiceProvider;
use Johnvict\ErrorFormat\Services\ErrorFormat;

class ErrorFormatServiceProvider extends ServiceProvider {
	use ErrorFormat;
	public function boot() {
		info("ERROR FORMAT PACKAGE WORKS");
	}

	public function register() {

	}
}