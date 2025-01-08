<?php 

class Home
{
	use Controller;

	public function index()
	{

		// Create an instance of the Home view
        $view = new HomeView();
        
        // Call the view's index method
        $view->index();
	}

}
