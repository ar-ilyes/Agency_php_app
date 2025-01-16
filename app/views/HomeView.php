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
                    <img class="h-10 w-auto" src="/assets/images/charity.png" alt="Logo">
                    <span class="ml-2 text-white text-xl font-semibold">Association</span>
                </a>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex gap-4 mr-6">
                    <a href="#" class="text-gray-300 hover:text-white transition-colors" target="_blank">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white transition-colors" target="_blank">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white transition-colors" target="_blank">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <a href="/auth" class="text-white hover:text-gray-300 transition-colors">Sign In</a>
                    <a href="/register" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Join Us</a>
                </div>
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
            document.addEventListener('DOMContentLoaded', function() {
                const carousel = document.getElementById('carousel-images');
                
                carousel.addEventListener('animationend', function() {
                    carousel.style.animation = 'none';
                    carousel.offsetHeight; 
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
                <a href="#section1" class="text-white hover:text-gray-300 px-4 py-2 text-lg transition-colors">Latest News</a>
                <a href="#section2" class="text-white hover:text-gray-300 px-4 py-2 text-lg transition-colors">Membership advantages</a>
                <a href="#section3" class="text-white hover:text-gray-300 px-4 py-2 text-lg transition-colors">our partners</a>
            </div>
        </nav>
    <?php
    }

    private function render_news_section($news_query) {
        ?>
            <section id="section1" class="bg-[#FAF9F6] py-12 px-6 relative">
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
            <section id="section2" class="bg-[#FAF9F6] py-12 px-6 relative">
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
            <section class="bg-[#FAF9F6] py-16">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-12">
                        <h2 class="text-4xl font-bold mb-4">Our Trusted Partners</h2>
                        <div class="w-24 h-1 bg-blue-600 mx-auto"></div>
                        <p class="text-gray-600 mt-4 max-w-2xl mx-auto">
                            We collaborate with leading organizations to bring the best value to our members
                        </p>
                    </div>
    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        <?php foreach($partners as $partner): ?>
                            <div class="group">
                                <div class="bg-white w-100 p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                                    <div class="aspect-square relative mb-4 overflow-hidden rounded-lg">
                                        <img 
                                            src="/<?= htmlspecialchars($partner['logo']) ?>"
                                            alt="<?= htmlspecialchars($partner['name']) ?>"
                                            class="w-full h-full object-contain p-4 group-hover:scale-110 transition-transform duration-300"
                                        >
                                    </div>
                                    
                                    <div class="text-center">
                                        <h3 class="font-semibold text-lg text-gray-800 mb-2">
                                            <?= htmlspecialchars($partner['name']) ?>
                                        </h3>
                                        <div class="flex items-center justify-center text-gray-600">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"/>
                                            </svg>
                                            <span class="text-sm">
                                                <?= htmlspecialchars($partner['city']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
    
                    <div class="text-center mt-12">
                        <a href="/partners" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
                            View All Partners
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </section>
        <?php
        }


    private function render_footer()
    {
    ?>
        <footer class="bg-gray-900 text-white py-12">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">About Us</h3>
                        <p class="text-gray-400">A charitable association based in Algiers that connects you with partners and gives you the possibility to help with donations and volunteer at events.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="/about" class="text-gray-400 hover:text-white transition-colors">About</a></li>
                            <li><a href="/events" class="text-gray-400 hover:text-white transition-colors">Events</a></li>
                            <li><a href="/membership" class="text-gray-400 hover:text-white transition-colors">Membership</a></li>
                            <li><a href="/contact" class="text-gray-400 hover:text-white transition-colors">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact</h3>
                        <ul class="space-y-2 text-gray-400">
                            <li>Oued Smar</li>
                            <li>Algiers</li>
                            <li>Phone: (123) 456-7890</li>
                            <li>Email: info@association.com</li>
                        </ul>
                    </div>
                    <img src="/assets/images/charity.png" alt="Logo" class="h-32 w-auto">
                </div>
            </div>
        </footer>
    <?php
    }


    public function setData($data) {
        $this->data = $data;
    }

    public function setController($controller) {
        $this->controller = $controller;
    }
}