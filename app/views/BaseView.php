<?php 
class BaseView
{
    public function renderHead()
    {
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>My App</title>
            <!-- Tailwind CDN -->
            <script src="https://cdn.tailwindcss.com"></script>
        </head>';
        $this->renderNavbar();
    }
    protected function renderNavbar()
    {
        $userType = $_SESSION['user']['type'] ?? null;
        $links = $this->getNavLinks($userType);
        if($userType){
        ?>
        <nav class="bg-gray-800 shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <!-- Logo Section -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="flex items-center">
                            <img class="h-8 w-auto" src="/assets/images/charity.png" alt="Logo">
                            <span class="ml-2 text-white text-lg font-semibold">Association</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-4">
                        <?php foreach ($links as $link): ?>
                            <a href="<?= $link['url'] ?>" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                <?= $link['text'] ?>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex items-center md:hidden">
                        <button type="button" 
                                onclick="toggleMobileMenu()"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="hidden md:hidden" id="mobileMenu">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <?php foreach ($links as $link): ?>
                        <a href="<?= $link['url'] ?>" 
                           class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                            <?= $link['text'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </nav>

        <script>
            function toggleMobileMenu() {
                const menu = document.getElementById('mobileMenu');
                menu.classList.toggle('hidden');
            }
        </script>

        <?php
        }else{
            //
        }
    }

    protected function getNavLinks($userType)
    {
        $links = [];
        
        switch ($userType) {
            case 'member':
                $links = [
                    ['url' => '/aidRequest', 'text' => 'Aid Requests'],
                    ['url' => '/Benefits', 'text' => 'Benefits'],
                    ['url' => '/Donation', 'text' => 'Donation'],
                    ['url' => '/Event', 'text' => 'Events'],
                    ['url' => '/Member', 'text' => 'Member Area'],
                    ['url' => '/News', 'text' => 'News'],
                    ['url' => '/Notification', 'text' => 'Notifications'],
                    ['url' => '/Partners', 'text' => 'Partners'],
                    ['url' => '/Auth', 'text' => 'Account']
                ];
                break;
                
            case 'partner':
                $links = [
                    ['url' => '/partner', 'text' => 'Dashboard'],
                    ['url' => '/News', 'text' => 'News'],
                    ['url' => '/Partners', 'text' => 'Partners'],
                    ['url' => '/auth', 'text' => 'Account']
                ];
                break;
                
            case 'admin':
                $links = [
                    ['url' => '/adminAnnouncement', 'text' => 'Announcements'],
                    ['url' => '/adminDonation', 'text' => 'Donations'],
                    ['url' => '/adminEvent', 'text' => 'Events'],
                    ['url' => '/adminMember', 'text' => 'Members'],
                    ['url' => '/adminPartner', 'text' => 'Partners'],
                    ['url' => '/auth', 'text' => 'Account'],
                    ['url' => '/adminAidRequest', 'text' => 'aidRequests'],
                    ['url' => '/adminPaymentHistory', 'text' => 'adminPaymentHistory'],
                ];
                break;
                
            default:
                // For guests or unknown user types
                $links = [
                    ['url' => '/auth', 'text' => 'Login']
                ];
                break;
        }
        
        return $links;
    }
    public function renderFooter()
    {
        echo '<footer class="bg-gray-800 text-white text-center py-4">
            Footer Content
        </footer>
        </html>';
    }
}
