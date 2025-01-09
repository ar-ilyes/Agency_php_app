<?php 

class TestView
{
	public function index()
	{
		$this->displayNavbar(); 
		$this->displayHero();  
	}

	private function displayNavbar()
	{
		echo '<nav><h1>Navbar</h1></nav>';
	}

	private function displayHero()
	{
		echo '<section><h1>Test page view</h1></section>';
	}
}
