<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AdminContactMessagesController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NosotrosController as ControllersNosotrosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\ProductListController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\NosotrosController;
use App\Http\Controllers\ContactanosController;
use App\Http\Controllers\HistoriasController;
use App\Http\Controllers\EditableContentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//user rotues

Route::get('/', [UserController::class,'index'])->name('home');
//Hero section 
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/hero/update', [UserController::class, 'update'])->name('hero.update');
    Route::delete('/hero/image/{id}', [UserController::class, 'deleteHeroImage'])->name('hero.deleteImage');
});



Route::get('/dashboard',[DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::put('/orders/{order}/shipping-status', [DashboardController::class, 'updateShippingStatus'])
    ->middleware(['auth', 'verified'])->name('orders.update-status');


//About us
Route::get('/nosotros', [NosotrosController::class, 'index'])->name('nosotros');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::post('/nosotros', [NosotrosController::class, 'store'])->name('nosotros.store');
        Route::put('/nosotros/{content}', [NosotrosController::class, 'update'])->name('nosotros.update');
        Route::delete('/nosotros/{content}', [NosotrosController::class, 'destroy'])->name('nosotros.destroy');
        Route::delete('/nosotros/images/{image}', [NosotrosController::class, 'destroyImage'])->name('nosotros.images.destroy');
    });
});


//contact
Route::get('/contactanos',[ContactanosController::class, 'index'])->name('contactanos');
Route::post('/contactanos', [ContactanosController::class, 'store'])->name('contactanos.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::post('/contactanos/content', [ContactanosController::class, 'createContent'])->name('contactanos.content.create');
        Route::put('/contactanos/{content}', [ContactanosController::class, 'update'])->name('contactanos.update');
        Route::delete('/contactanos/{content}', [ContactanosController::class, 'destroy'])->name('contactanos.destroy');
        Route::delete('/contactanos/images/{image}', [ContactanosController::class, 'destroyImage'])->name('contactanos.images.destroy');

            // Rutas para mensajes de contacto en Panel de Administrador 
        Route::get('/contact-messages', [AdminContactMessagesController::class, 'index'])->name('admin.contact-messages.index');
        Route::patch('/contact-messages/{id}/read', [AdminContactMessagesController::class, 'markAsRead'])->name('admin.contact-messages.read');
        Route::patch('/contact-messages/{id}/status', [AdminContactMessagesController::class, 'updateStatus'])->name('admin.contact-messages.status');
        Route::delete('/contact-messages/{id}', [AdminContactMessagesController::class, 'destroy'])->name('admin.contact-messages.destroy');
    });
});


// Stories section
Route::get('/historias',[HistoriasController::class, 'index'])->name('historias');

Route::post('/historias', [HistoriasController::class, 'store'])->name('historias.store');
Route::put('/historias/{producer}', [HistoriasController::class, 'update'])->name('historias.update');
Route::delete('/historias/{producer}', [HistoriasController::class, 'destroy'])->name('historias.destroy');
Route::delete('/historias/image/{id}', [HistoriasController::class, 'deleteImage'])->name('historias.deleteImage');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');






    //chekcout 
    Route::prefix('checkout')->controller(CheckoutController::class)->group((function()  {
        Route::post('order','store')->name('checkout.store');
        Route::get('success','success')->name('checkout.success');
        Route::get('cancel','cancel')->name('checkout.cancel');
    }));

});

//add to cart 

Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('view','view')->name('cart.view');
    Route::post('store/{product}','store')->name('cart.store');
    Route::patch('update/{product}','update')->name('cart.update');
    Route::delete('delete/{product}','delete')->name('cart.delete');
});

//routes for products list and filter 
Route::prefix('products')->controller(ProductListController::class)->group(function ()  {
    Route::get('/','index')->name('products.index');
    

    Route::get('/{id}/details', 'show')->name('products.details');
});



//end

//admin routs

Route::group(['prefix' => 'admin', 'middleware' => 'redirectAdmin'], function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/monthly-sales', [AdminController::class, 'getMonthlySales'])->name('admin.monthly-sales');

    //Rutas para manejo de Usurios
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    //products routes 
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products/store',[ProductController::class,'store'])->name('admin.products.store');
    Route::put('/products/update/{id}',[ProductController::class,'update'])->name('admin.products.update');
    Route::delete('/products/image/{id}',[ProductController::class,'deleteImage'])->name('admin.products.image.delete');
    Route::delete('/products/destory/{id}',[ProductController::class,'destory'])->name('admin.products.destory');

        // Rutas para brands
        Route::get('/brands', [BrandController::class, 'index'])->name('admin.brands.index');
        Route::post('/brands/store', [BrandController::class, 'store'])->name('admin.brands.store');
        Route::put('/brands/update/{id}', [BrandController::class, 'update'])->name('admin.brands.update');
        Route::delete('/brands/destroy/{id}', [BrandController::class, 'destroy'])->name('admin.brands.destroy');

            // Rutas para categorías
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/update/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/destroy/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    
        // Rutas de órdenes
        Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::put('/orders/{id}/shipping-status', [OrderController::class, 'updateShippingStatus'])
                ->name('admin.orders.shipping.update');
});

Route::get('/storage-link', function () {
    $targetFolder = storage_path( 'app/public' ); // path to upload directory
    $linkFolder= $_SERVER['DOCUMENT_ROOT'].'/storage';
    symlink( $targetFolder, $linkFolder);
    });

//end

require __DIR__ . '/auth.php';
