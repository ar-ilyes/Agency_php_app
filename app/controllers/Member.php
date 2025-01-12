<?php 

class Member
{
	use Controller;

	public function index()
	{

		// Create an instance of the Home view
        $view = new MemberProfileView();
        
        // Call the view's index method
        $view->index();
	}

}
