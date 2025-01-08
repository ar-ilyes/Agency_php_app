<?php 

class HomeView
{
    public function index()
    {
        $this->affich_navbar();
        $this->affich_hero();
        $this->affich_footer();
    }

    public function affich_navbar()
    {
        echo "<nav>Navbar Content</nav>";
    }

    public function affich_hero()
    {
        echo "<div>Hero Section Content</div>";
    }

    public function affich_footer()
    {
        echo "<footer>Footer Content</footer>";
    }
}
