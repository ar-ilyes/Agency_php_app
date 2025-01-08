<?php 

// This function automatically loads classes from specified directories when they are instantiated.
// It eliminates the need to manually include or require class files.
// The class name is matched to its corresponding file in the models, controllers, or views directories.
spl_autoload_register(function($classname){

	spl_autoload_register(function($classname) {
		$paths = [
			"../app/models/",
			"../app/controllers/",
			"../app/views/"
		];
	
		foreach ($paths as $path) {
			$filename = $path . ucfirst($classname) . ".php";
			if (file_exists($filename)) {
				require $filename;
				return;
			}
		}
	});
});

require 'config.php';
require 'Database.php';
require 'Controller.php';
require 'App.php';