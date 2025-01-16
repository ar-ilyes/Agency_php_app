<?php 
class HomeView extends BaseView
{
    private Home $controller;
    
    public function __construct()
    {
        $this->controller = new Home();
    }

    public function index()
    {
        $news_query = $this->controller->get_latest_news();
        $this->renderHead();
        $this->render_top_navbar();
        $this->render_carousel();
        $this->render_middle_navbar();
        $this->render_news_section($news_query);
        $this->render_membership_section();
        $this->render_partners_section();
        $this->render_footer();
    }

    private function render_top_navbar()
    {
    ?>
        <nav class="bg-gray-800 px-6 py-4 flex justify-between items-center">
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="flex items-center">
                    <img class="h-8 w-auto" src="/assets/images/charity.png" alt="Logo">
                    <span class="ml-2 text-white text-lg font-semibold">Association</span>
                </a>
            </div>
            <div class="flex gap-6">
                <a href="#" class="text-white hover:text-gray-300 transition-colors" target="_blank">Facebook</a>
                <a href="#" class="text-white hover:text-gray-300 transition-colors" target="_blank">Twitter</a>
                <a href="#" class="text-white hover:text-gray-300 transition-colors" target="_blank">Instagram</a>
            </div>
        </nav>
    <?php
    }

    private function render_carousel()
    {
    ?>
        <div id="carousel-container" class="mt-5 h-[80vh] w-[90vw] relative mx-auto mb-5 overflow-x-hidden">
            <div id="carousel-images" class="w-[400vw] h-full flex gap-4 items-center animate-carousel">
                <img src="/assets/images/aurassi.png" alt="Aurassi Hotel" class="h-full w-[90vw] object-cover">
                <img src="/assets/images/holidayInn.png" alt="Holiday Inn" class="h-full w-[90vw] object-cover">
                <img src="/assets/images/mariotteC.png" alt="Mariotte Hotel" class="h-full w-[90vw] object-cover">
                <img src="/assets/images/sheratonA.png" alt="Sheraton Hotel" class="h-full w-[90vw] object-cover">
            </div>
        </div>
        <style>
            @keyframes carousel {
                0% {
                    transform: translateX(0);
                }
                100% {
                    transform: translateX(calc(-90vw * 3 - 48px));
                }
            }
            .animate-carousel {
                animation: carousel 16s infinite linear;
            }
            .animate-carousel:hover {
                animation-play-state: paused;
            }

            /* Clone the first image at the end for smooth transition */
            #carousel-images::after {
                content: "";
                position: absolute;
                top: 0;
                right: 0;
                width: 90vw;
                height: 100%;
                background-image: url('/assets/images/aurassi.png');
                background-size: cover;
                margin-left: 16px;
            }
        </style>

        <script>
            // Add infinite scroll effect
            document.addEventListener('DOMContentLoaded', function() {
                const carousel = document.getElementById('carousel-images');
                
                carousel.addEventListener('animationend', function() {
                    // Reset the animation without visual interruption
                    carousel.style.animation = 'none';
                    carousel.offsetHeight; // Trigger reflow
                    carousel.style.animation = null;
                });
            });
        </script>
    <?php
    }

    private function render_middle_navbar()
    {
    ?>
        <nav class="bg-gray-800 py-4 sticky top-0 z-50">
            <div class="flex justify-center space-x-8">
                <a href="#section1" class="text-white hover:text-gray-300 px-4 py-2 text-lg transition-colors">Section 1</a>
                <a href="#section2" class="text-white hover:text-gray-300 px-4 py-2 text-lg transition-colors">Section 2</a>
                <a href="#section3" class="text-white hover:text-gray-300 px-4 py-2 text-lg transition-colors">Section 3</a>
            </div>
        </nav>
    <?php
    }

    private function render_news_section($news_query) {
        ?>
            <section id="section1" class="bg-gray-100 py-12 px-6 relative">
                <h2 class="text-3xl font-bold mb-8">Latest News & Events</h2>
                <a href="/news" class="absolute top-6 right-6 text-gray-600 hover:text-gray-900">See more</a>
                
                <div class="flex justify-center gap-6 flex-wrap">
                    <?php foreach($news_query as $item): ?>
                        <a href="/<?= $item['type'] ?>/<?= $item['id'] ?>" 
                           class="bg-white rounded-lg shadow-lg overflow-hidden w-72 transform hover:-translate-y-2 transition-transform">
                            <?php if(!empty($item['image'])): ?>
                                <img src="<?= htmlspecialchars($item['image']) ?>" 
                                     alt="<?= htmlspecialchars($item['title']) ?>" 
                                     class="w-full h-48 object-cover">
                            <?php endif; ?>
                            <div class="p-6">
                                <h3 class="font-bold text-lg mb-2"><?= htmlspecialchars($item['title']) ?></h3>
                                <p class="text-gray-600"><?= htmlspecialchars(substr($item['description'], 0, 100)) ?>...</p>
                                <div class="mt-4 text-sm text-gray-500">
                                    <?php if($item['type'] === 'event'): ?>
                                        <?= date('M d, Y', strtotime($item['date_start'])) ?>
                                    <?php else: ?>
                                        <?= date('M d, Y', strtotime($item['created_at'])) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php
        }private function render_membership_section() {
            $benefits = $this->data['benefits'];
        ?>
            <section id="section2" class="bg-gray-100 py-12 px-6 relative">
                <h2 class="text-3xl font-bold mb-8">Membership Advantages</h2>
                <a href="/benefits" class="absolute top-6 right-6 text-gray-600 hover:text-gray-900">See more</a>
                
                <div class="flex justify-center gap-6 flex-wrap">
                    <?php foreach($benefits as $type): ?>
                        <div class="w-full md:w-1/6 lg:w-1/6">
                            <div class="<?= $type['card_color'] ?> rounded-lg p-4 mb-4">
                                <h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($type['membership_type']['name']) ?></h3>
                            </div>
                            <?php foreach($type['advantages'] as $advantage): ?>
                                <div class="bg-white rounded-lg shadow-lg p-4 mb-4">
                                    <h4 class="font-bold"><?= htmlspecialchars($advantage['partner_name']) ?></h4>
                                    <p class="text-gray-600"><?= htmlspecialchars($advantage['description']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php
        }

    private function render_partners_section()
    {
        $partners = $this->data['partners'];
    ?>
        <section class="bg-gray-100 pt-12 pb-20 px-6 text-center ">
            <h2 class="text-3xl font-bold mb-8">Our Partners</h2>
            <div class="flex flex-wrap justify-center gap-6">
                <?php foreach($partners as $partner): ?>
                <div class="w-24 h-24 rounded-full border-2 border-gray-300 transform hover:scale-110 transition-transform">
                    <img src="/<?= htmlspecialchars($partner['logo']) ?>" 
                         alt="<?= htmlspecialchars($partner['name']) ?>" 
                         class="w-full h-full object-cover">
                    <div class="text-sm mt-2"><?= htmlspecialchars($partner['name']) ?></div>
                    <div class="text-xs text-gray-500"><?= htmlspecialchars($partner['city']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php
    }


    private function render_footer()
    {
    ?>
        <footer class="bg-gray-800 text-white py-8 text-center">
            <div class="flex justify-center gap-8 mb-4">
                <a href="#home" class="hover:text-gray-300 transition-colors">Home</a>
                <a href="#about" class="hover:text-gray-300 transition-colors">About</a>
                <a href="#services" class="hover:text-gray-300 transition-colors">Services</a>
                <a href="#contact" class="hover:text-gray-300 transition-colors">Contact</a>
            </div>
            <p class="text-sm">&copy; 2024 Your Company. All rights reserved.</p>
        </footer>
        </body>
        </html>
    <?php
    }

    public function print_nav_bar()
    {
    ?>
        <nav class="bg-gray-800 px-6 py-4">
            <ul class="flex space-x-6">
                <?php
                $menu_query = $this->controller->get_navbar_menu_controller();

                foreach ($menu_query as $menu_item) {
                    if ((bool) $menu_item['has_children']) {
                        $submenu_query = $this->controller->get_navbar_submenu_model($menu_item['id']);
                        ?>
                        <li class="relative group">
                            <a href="<?= $menu_item['url'] ?>" class="text-white hover:text-gray-300 transition-colors"><?= $menu_item['name'] ?></a>
                            <div class="absolute hidden group-hover:block bg-white shadow-lg rounded-lg mt-2 py-2 w-48">
                                <?php foreach ($submenu_query as $submenu_item): ?>
                                    <p class="px-4 py-2 hover:bg-gray-100"><?= $submenu_item['name'] ?></p>
                                    <hr class="border-gray-200">
                                <?php endforeach; ?>
                            </div>
                        </li>
                        <?php
                    } else {
                        echo "<li><a href=\"{$menu_item['url']}\" class=\"text-white hover:text-gray-300 transition-colors\">{$menu_item['name']}</a></li>";
                    }
                }
                ?>
            </ul>
        </nav>
    <?php
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function setController($controller) {
        $this->controller = $controller;
    }
}