<?php $content = ob_get_clean(); ?>
<?php ob_start(); ?>

<form class="w-full mx-auto space-y-6 bg-white p-6 rounded-xl shadow-md" action="#" method="POST">
    <div>
        <label for="username" class="block text-sm font-semibold text-gray-700">Username</label>
        <div class="mt-2 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <i class="fa-solid fa-user"></i>
            </span>
            <input type="text" name="username" id="username" placeholder="johndoe" required
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
        </div>
    </div>

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
            <input type="password" name="password" id="password" required
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
        </div>
        <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters.</p>
    </div>

    <div>
        <label for="confirm_password" class="block text-sm font-semibold text-gray-700">Confirm Password</label>
        <div class="mt-2 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <i class="fa-solid fa-check-double"></i>
            </span>
            <input type="password" name="confirm_password" id="confirm_password" required
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
        </div>
    </div>

    <div>
        <button type="submit"
            class="w-full py-3 text-white font-medium bg-indigo-600 rounded-lg shadow hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Register
        </button>
    </div>

    <p class="text-center text-sm text-gray-600">
        Already have an account?
        <a href="/login" class="font-medium text-indigo-600 hover:text-indigo-500 transition">Sign in</a>
    </p>
</form>

<?php $content = ob_get_clean(); ?>
<?php include base_path('resources/views/layouts/guest.php'); ?>