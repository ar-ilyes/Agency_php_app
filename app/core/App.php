<?php


class App
{
	private $controller = 'Home';

	private function splitURL()
	{
		$URL = $_GET['url'] ?? 'home';
		$URL = explode("/", trim($URL,"/"));
		echo $URL[0];
		return $URL;	
	}

	public function loadController()
	{
		$URL = $this->splitURL();

		$filename = "../app/controllers/".ucfirst($URL[0]).".php";
		echo $filename;
		if(file_exists($filename))
		{
			require $filename;
			$this->controller = ucfirst($URL[0]);
			unset($URL[0]);
		}else{
			$filename = "../app/controllers/_404.php";
			require $filename;
			$this->controller = "_404";
		}

		$controller = new $this->controller;

		$controller->index();

	}	

}


