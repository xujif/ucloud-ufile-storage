<?php
namespace Xujif\UcloudUfileStorage;

use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class UfileServiceProvider extends ServiceProvider {

	public function boot() {
		\Storage::extend(
			'ucloud-ufile',
			function ($app, $config) {
				$ufileAdapter = new UcloudUfileAdapter(
					$config['bucket'],
					$config['public_key'],
					$config['secret_key'],
					$config['suffix'],
					$config['prefix'],
				);
				$fs = new Filesystem($ufileAdapter);
				return $fs;
			}
		);
	}

	public function register() {

	}
}
