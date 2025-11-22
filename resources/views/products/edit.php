<?php $content = ob_get_clean(); ?>
<?php ob_start(); ?>
<div class="max-w-xl mx-auto">
    <?php if (isset($success)): ?>
        <div class="rounded-md border-l-4 border-green-500 bg-white p-4 shadow-md mb-10" role="alert">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            Operation Successful!
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            <?= $success ?>
                        </p>
                    </div>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button class="inline-flex text-gray-400 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150" onclick="this.parentElement.parentElement.parentElement.remove()">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <form class="space-y-6 bg-white p-6 rounded-xl shadow-md" action="/products/<?= $product->id ?>" method="POST">
        <input type="hidden" name="_method" value="PUT">
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700">Product Name</label>
            <div class="mt-2 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fa-solid fa-box"></i>
                </span>
                <input type="text" name="name" id="name" placeholder="Product Name" 
                    value="<?= $old['name'] ?? $product->name ?>" 
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
            <p class="mt-1 text-xs text-red-500 font-semibold"><?= $errors['name'] ?? '' ?></p>
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700">Price</label>
            <div class="mt-2 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fa-solid fa-dollar-sign"></i>
                </span>
                <input type="text" name="price" id="price" placeholder="0.00" 
                    value="<?= $old['price'] ?? $product->price ?>"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
            <p class="mt-1 text-xs text-red-500 font-semibold"><?= $errors['price'] ?? '' ?></p>
        </div>

        <div>
            <label for="quantity_available" class="block text-sm font-semibold text-gray-700">Quantity Available</label>
            <div class="mt-2 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fa-solid fa-coins"></i>
                </span>
                <input type="number" name="quantity_available" id="quantity_available"  placeholder="0" 
                    value="<?= $old['quantity_available'] ?? $product->quantity_available ?>"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
            <p class="mt-1 text-xs text-red-500 font-semibold"><?= $errors['quantity_available'] ?? '' ?></p>
        </div>

        <div>
            <button type="submit"
                class="w-full py-3 text-white font-medium bg-indigo-600 rounded-lg shadow hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Update
            </button>
        </div>

        <p class="text-center text-sm text-gray-600">
            <a href="/products" class="font-medium text-indigo-600 hover:text-indigo-500 transition">Back to Products</a>
        </p>
    </form>
</div>
<?php $content = ob_get_clean(); ?>
<?php include base_path('resources/views/layouts/app.php'); ?>