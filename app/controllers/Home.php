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
	public function get_latest_news(): array
    {
        // Return static data for now
        return [
            ['title' => 'News Item 1'],
            ['title' => 'News Item 2'],
            ['title' => 'News Item 3'],
        ];
    }

}
