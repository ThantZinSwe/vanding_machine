<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vending Machine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-sm fixed w-full z-10 shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="text-2xl font-bold text-indigo-600 flex items-center gap-2">
                        Vending Machine
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden sm:flex sm:space-x-8 items-center">
                    <a href="/products" class="text-gray-900 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">
                        Products
                    </a>
                    
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="/admin/manage.php" class="text-gray-500 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">
                            Manage Inventory
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Auth Buttons -->
                <div class="hidden sm:flex items-center space-x-4">
                    <span class="text-sm text-gray-500">
                        Hello, <span class="font-semibold text-gray-700"><?php echo htmlspecialchars(auth()->name); ?></span>
                    </span>
                    <form action="/logout" method="POST">
                        <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg text-sm font-medium transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <main class="flex-grow pt-24 pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        <?php echo $content; ?>
    </main>
</body>
</html>
    