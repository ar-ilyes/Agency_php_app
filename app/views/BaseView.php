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
    }

    public function renderFooter()
    {
        echo '<footer class="bg-gray-800 text-white text-center py-4">
            Footer Content
        </footer>
        </html>';
    }
}
