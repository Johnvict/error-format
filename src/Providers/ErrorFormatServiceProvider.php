<?php

namespace Johnvict\ErrorFormat\Providers;


use Illuminate\Support\ServiceProvider;
use Johnvict\ErrorFormat\Services\DataHelper;

class ErrorFormatServiceProvider extends ServiceProvider {
	use DataHelper;
	public function boot() {
		info("ERROR FORMAT PACKAGE WORKS");
	}

	public function register() {

	}
}