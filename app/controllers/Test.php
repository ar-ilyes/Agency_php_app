<?php 

class Test
{
	use Controller; 

	public function index()
	{
		$view = new TestView();

		$view->index();
	}
}
