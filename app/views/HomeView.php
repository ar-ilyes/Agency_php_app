<?php 

class HomeView extends BaseView
{
    public function index()
    {
        $this->renderHead();  // Render the head (including the Tailwind CDN)
        $this->affich_navbar();
        $this->affich_hero();
        $this->renderFooter();  // Render the footer
    }

    public function affich_navbar()
    {
        echo "<nav class='bg-blue-500 p-4 text-white text-center'>Navbar Content</nav>";
    }

    public function affich_hero()
    {
        echo "<div class='bg-red-200 p-8 text-center'>Hero Section Content</div>";
    }

    public function affich_footer()
    {
        echo "<footer class='bg-gray-800 text-white text-center py-4'>Footer Content</footer>";
    }
}
