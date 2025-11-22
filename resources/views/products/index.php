<?php 
    $content = ob_get_clean(); 
    ob_start(); 

    $products = $paginator['data'];
    $currentPage = $paginator['current_page'];
    $lastPage = $paginator['last_page'];
    $total = $paginator['total'];
    $from = $paginator['from'];
    $to = $paginator['to'];
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Products</h1>
            <p class="mt-1 text-sm text-gray-500">
                Manage your product inventory from the database.
            </p>
        </div>
        <?php if(is_admin()): ?>
            <a href="/products/create" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Add Product
            </a>
        <?php endif; ?>
    </div>

    <div class="bg-white p-4 rounded-lg shadow mb-6 border border-gray-200">
        <form method="GET" class="flex gap-4 flex-wrap items-end">
            <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
            <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">

            <div class="flex-1 min-w-[200px]">
                <label for="q" class="block text-sm font-medium text-gray-700 mb-1">Search Product</label>
                <div class="relative rounded-md shadow-sm">
                    <input type="text" name="q" id="q" value="<?= htmlspecialchars($search) ?>" 
                           class="block w-full rounded-md border-gray-300 pl-3 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 border" 
                           placeholder="Search by name...">
                </div>
            </div>
            
            <div>
                <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-gray-800 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-700">
                    Filter
                </button>
                <?php if(!empty($search)): ?>
                    <a href="/products" class="ml-2 inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="<?= sort_url('name', $sort, $order, $search) ?>" class="group inline-flex items-center hover:text-gray-700">
                                Product
                                <span class="ml-2 flex-none rounded text-gray-900 group-hover:bg-gray-200">
                                    <?php if ($sort === 'name'): ?>
                                        <?= $order === 'asc' ? '▲' : '▼' ?>
                                    <?php else: ?>
                                        <span class="text-gray-400 opacity-50 group-hover:opacity-100">⇅</span>
                                    <?php endif; ?>
                                </span>
                            </a>
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="<?= sort_url('price', $sort, $order, $search) ?>" class="group inline-flex items-center hover:text-gray-700">
                                Price
                                <span class="ml-2 flex-none rounded text-gray-900 group-hover:bg-gray-200">
                                    <?php if ($sort === 'price'): ?>
                                        <?= $order === 'asc' ? '▲' : '▼' ?>
                                    <?php else: ?>
                                        <span class="text-gray-400 opacity-50 group-hover:opacity-100">⇅</span>
                                    <?php endif; ?>
                                </span>
                            </a>
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="<?= sort_url('stock', $sort, $order, $search) ?>" class="group inline-flex items-center hover:text-gray-700">
                                Stock
                                <span class="ml-2 flex-none rounded text-gray-900 group-hover:bg-gray-200">
                                    <?php if ($sort === 'stock'): ?>
                                        <?= $order === 'asc' ? '▲' : '▼' ?>
                                    <?php else: ?>
                                        <span class="text-gray-400 opacity-50 group-hover:opacity-100">⇅</span>
                                    <?php endif; ?>
                                </span>
                            </a>
                        </th>

                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $product): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($product->name) ?></div>
                                            <div class="text-sm text-gray-500">ID: #<?= $product->id ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    $<?= number_format((float)$product->price, 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($product->quantity_available > 10): ?>
                                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">In Stock (<?= $product->quantity_available ?>)</span>
                                    <?php elseif($product->quantity_available > 0): ?>
                                        <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">Low Stock (<?= $product->quantity_available ?>)</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Out of Stock</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <?php if(is_admin()): ?>
                                        <a href="/products/<?= $product->id ?>/edit" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <form action="/products/<?= $product->id ?>" method="POST" class="inline">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    <?php else: ?>
                                        <a href="/products/<?= $product->id ?>/purchase" class="text-indigo-600 hover:text-indigo-900 mr-3">Purchase</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                No products found matching "<?= htmlspecialchars($search) ?>"
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total > 0): ?>
        <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
            <div class="flex flex-1 justify-between sm:hidden">
                <a href="<?= ($currentPage > 1) ? page_url($currentPage - 1, $search, $sort, $order) : '#' ?>" 
                   class="<?= ($currentPage <= 1) ? 'pointer-events-none opacity-50' : '' ?> relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                
                <a href="<?= ($currentPage < $lastPage) ? page_url($currentPage + 1, $search, $sort, $order) : '#' ?>" 
                   class="<?= ($currentPage >= $lastPage) ? 'pointer-events-none opacity-50' : '' ?> relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
            </div>
            
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium"><?= $from ?></span> to <span class="font-medium"><?= $to ?></span> of <span class="font-medium"><?= $total ?></span> results
                    </p>
                </div>
                <div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <a href="<?= ($currentPage > 1) ? page_url($currentPage - 1, $search, $sort, $order) : '#' ?>" 
                           class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 <?= ($currentPage <= 1) ? 'pointer-events-none opacity-50' : '' ?>">
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>
                        </a>
                        
                        <?php for($i = 1; $i <= $lastPage; $i++): ?>
                            <a href="<?= page_url($i, $search, $sort, $order) ?>" 
                               class="relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 focus:z-20 focus:outline-offset-0 <?= $i === $currentPage ? 'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600' : 'text-gray-900 hover:bg-gray-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <a href="<?= ($currentPage < $lastPage) ? page_url($currentPage + 1, $search, $sort, $order) : '#' ?>" 
                           class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 <?= ($currentPage >= $lastPage) ? 'pointer-events-none opacity-50' : '' ?>">
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php include base_path('resources/views/layouts/app.php'); ?>