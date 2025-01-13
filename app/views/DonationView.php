<?php
class DonationView {
    public function afficher_site($data) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Make a Donation</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-100">
            <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                <div class="max-w-md w-full space-y-8">
                    <div>
                        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                            Make a Donation
                        </h2>
                    </div>
                    <form class="mt-8 space-y-6" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="member_id" value="<?= htmlspecialchars($data['member_id']) ?>">
                        
                        <div class="rounded-md shadow-sm -space-y-px">
                            <div class="mb-4">
                                <label for="amount" class="block text-sm font-medium text-gray-700">
                                    Donation Amount
                                </label>
                                <input
                                    id="amount"
                                    name="amount"
                                    type="number"
                                    step="0.01"
                                    required
                                    class="mt-1 appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                    placeholder="Enter amount"
                                >
                            </div>
                            
                            <div class="mb-4">
                                <label for="payment_receipt" class="block text-sm font-medium text-gray-700">
                                    Payment Receipt
                                </label>
                                <input
                                    id="payment_receipt"
                                    name="payment_receipt"
                                    type="file"
                                    required
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="mt-1 appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                >
                                <p class="mt-1 text-sm text-gray-500">
                                    Accepted formats: PDF, JPG, JPEG, PNG
                                </p>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Submit Donation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
}
