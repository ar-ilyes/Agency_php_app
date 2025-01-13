<?php
class EventView {
    public function afficher_site($data) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Association Events</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-100">
            <div class="min-h-screen p-8">
                <div class="max-w-7xl mx-auto">
                    <h1 class="text-3xl font-bold text-gray-900 mb-8">Upcoming Events</h1>
                    
                    <?php if (!empty($data['member_events'])): ?>
                    <div class="mb-12">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Your Registered Events</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($data['member_events'] as $event): ?>
                                <div class="bg-white rounded-lg shadow-md p-6">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($event['title']) ?></h3>
                                        <span class="px-2 py-1 text-sm rounded <?= $event['status'] === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                            <?= ucfirst(htmlspecialchars($event['registration_status'])) ?>
                                        </span>
                                    </div>
                                    <p class="text-gray-600 mt-2"><?= htmlspecialchars($event['description']) ?></p>
                                    <div class="mt-4 text-sm text-gray-500">
                                        <p>📍 <?= htmlspecialchars($event['location']) ?></p>
                                        <p>📅 <?= date('d/m/Y H:i', strtotime($event['date_start'])) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($data['events'] as $event): ?>
                            <div class="bg-white rounded-lg shadow-md p-6">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($event['title']) ?></h3>
                                <p class="text-gray-600 mb-4"><?= htmlspecialchars($event['description']) ?></p>
                                
                                <div class="text-sm text-gray-500 space-y-1 mb-4">
                                    <p>📍 <?= htmlspecialchars($event['location']) ?></p>
                                    <p>📅 <?= date('d/m/Y H:i', strtotime($event['date_start'])) ?></p>
                                    <p>👥 Volunteers: <?= $event['current_volunteers'] ?>/<?= $event['max_volunteers'] ?></p>
                                </div>
                                
                                <?php if ($event['current_volunteers'] < $event['max_volunteers']): ?>
                                    <button
                                        onclick="registerVolunteer(<?= $event['id'] ?>, <?= $data['member_id'] ?>)"
                                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                                    >
                                        Volunteer for this event
                                    </button>
                                <?php else: ?>
                                    <button
                                        disabled
                                        class="w-full bg-gray-300 text-gray-500 py-2 px-4 rounded-md cursor-not-allowed"
                                    >
                                        Event is full
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <script>
            async function registerVolunteer(eventId, memberId) {
                try {
                    const response = await fetch('/event', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `event_id=${eventId}&member_id=${memberId}`
                    });
                    
                    const result = await response.json();
                    alert(result.message);
                    
                    if (result.success) {
                        window.location.reload();
                    }
                } catch (error) {
                    alert('An error occurred. Please try again.');
                }
            }
            </script>
        </body>
        </html>
        <?php
    }
}
