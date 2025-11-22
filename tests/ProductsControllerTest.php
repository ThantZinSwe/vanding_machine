<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Controller\ProductsController;
use App\Models\User;
use App\Services\ProductService;
use Core\Session;
use PHPUnit\Framework\TestCase;

class ProductsControllerTest extends TestCase
{
    protected function setUp(): void
    {
        Session::init();

        $admin = new User();
        $admin->role = 'admin';

        Session::flash('auth', $admin);
    }

    protected function getControllerWithMockedView($service)
    {
        $controller = $this->getMockBuilder(ProductsController::class)
            ->setConstructorArgs([$service])
            ->onlyMethods(['view', 'back', 'redirect', 'abort'])
            ->getMock();
        
        return $controller;
    }

    public function test_index_method()
    {
        $mockService = $this->createMock(ProductService::class);
        
        $mockService->expects($this->once())
            ->method('getWithPagination')
            ->willReturn(['data' => [], 'total' => 0]);

        $controller = $this->getControllerWithMockedView($mockService);

        $controller->expects($this->once())
            ->method('view')
            ->with($this->equalTo('products/index'));

        $controller->index();
    }

    public function test_create_method()
    {
        $mockService = $this->createMock(ProductService::class);
        $controller = $this->getControllerWithMockedView($mockService);

        $controller->expects($this->once())
            ->method('view')
            ->with($this->equalTo('products/create'));

        $controller->create();
    }

    public function test_store_method()
    {
        $_POST = [
            'name' => 'Test Product',
            'price' => 100,
            'quantity_available' => 10
        ];

        $mockService = $this->createMock(ProductService::class);

        $mockService->expects($this->once())
            ->method('create')
            ->with($_POST)
            ->willReturn((object)['id' => 1]);

        $controller = $this->getMockBuilder(ProductsController::class)
                ->setConstructorArgs([$mockService])
                ->onlyMethods(['back'])
                ->getMock();

         $controller->expects($this->once())
            ->method('back');

        $controller->store();
    }

    public function test_edit_method()
    {
        $product = (object)[
            'id' => 1,
            'name' => 'Test'
        ];

        $mockService = $this->createMock(ProductService::class);

        $mockService->expects($this->once())
            ->method('findById')
            ->with('1')
            ->willReturn($product);

        $controller = $this->getMockBuilder(ProductsController::class)
            ->setConstructorArgs([$mockService])
            ->onlyMethods(['view'])
            ->getMock();

        $controller->expects($this->once())
            ->method('view')
            ->with(
                $this->equalTo('products/edit'),
                $this->equalTo(['product' => $product])
            );

        $controller->edit('1');
    }

    public function test_update_method()
    {
        $_POST = [
            'name' => 'Updated Product',
            'price' => 200,
            'quantity_available' => 5
        ];

        $mockService = $this->createMock(ProductService::class);

        $mockService->expects($this->once())
            ->method('update')
            ->with('1', $_POST)
            ->willReturn((object)['id' => 1]);

        $controller = $this->getMockBuilder(ProductsController::class)
            ->setConstructorArgs([$mockService])
            ->onlyMethods(['back'])
            ->getMock();

        $controller->expects($this->once())
            ->method('back');

        $controller->update('1');
    }

    public function test_destroy_method()
    {
        $mockService = $this->createMock(ProductService::class);

        $mockService->expects($this->once())
            ->method('delete')
            ->with('1');

        $controller = $this->getMockBuilder(ProductsController::class)
            ->setConstructorArgs([$mockService])
            ->onlyMethods(['redirect'])
            ->getMock();

        $controller->expects($this->once())
            ->method('redirect')
            ->with('/products');

        $controller->destroy('1');
    }
}