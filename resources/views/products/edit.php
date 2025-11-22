<?php $content = ob_get_clean(); ?>
<?php ob_start(); ?>
<form class="max-w-xl mx-auto space-y-6 bg-white p-6 rounded-xl shadow-md" action="/products/<?= $product->id ?>" method="POST">
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
<?php $content = ob_get_clean(); ?>
<?php include base_path('resources/views/layouts/app.php'); ?>