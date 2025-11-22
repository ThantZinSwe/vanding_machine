<?php $content = ob_get_clean(); ?>
<?php ob_start(); ?>

<form class="w-full mx-auto space-y-6 bg-white p-6 rounded-xl shadow-md" action="/login" method="POST">
    <div>
        <label for="email" class="block text-sm font-semibold text-gray-700">Email address</label>
        <div class="mt-2 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <i class="fa-solid fa-envelope"></i>
            </span>
            <input type="email" name="email" id="email" placeholder="you@example.com" required
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
        </div>
    </div>

    <div>
        <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
        <div class="mt-2 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <i class="fa-solid fa-lock"></i>
            </span>
            <input type="password" name="password" id="password" placeholder="••••••••" required
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
        </div>
    </div>

    <div>
        <button type="submit"
            class="w-full py-3 text-white font-medium bg-indigo-600 rounded-lg shadow hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Sign In
        </button>
    </div>

    <p class="text-center text-sm text-gray-600">
        Don't have an account?
        <a href="/register" class="font-medium text-indigo-600 hover:text-indigo-500 transition">Register</a>
    </p>
</form>

<?php $content = ob_get_clean(); ?>
<?php include base_path('resources/views/layouts/guest.php'); ?>
