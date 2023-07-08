<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ReceiptsController;
use App\Http\Controllers\DistrictsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\ChecklistsController;
use App\Http\Controllers\SettlementsController;
use App\Http\Controllers\Auth\UserAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// authentication
Route::post('register', [UserAuthController::class,'register']);
Route::post('login', [UserAuthController::class,'login']);


Route::middleware('auth:api')->group(function (){
    Route::get('phoneList',[CustomersController::class,'getPhoneList']);


    Route::get('product_code_autocomplete', [ProductsController::class,'autocomplete']);
    Route::get('customer_code_autocomplete', [CustomersController::class,'autocomplete']);
    Route::get('phone_autocomplete', [CustomersController::class,'phoneAutocomplete']);
    Route::get('supplier_code_autocomplete', [SuppliersController::class,'autocomplete']);


    Route::get('customers/{id}', [CustomersController::class,'getByID']);
    Route::post('customers', [CustomersController::class,'save']);

    Route::get('categories/{id}', [CategoriesController::class,'getByID']);
    Route::get('categories/{id}/category-values', [CategoriesController::class,'getCategoryValueByCategoryID']);
    Route::get('category-values/{id}', [CategoriesController::class,'getCategoryValueByID']);
    Route::post('categories', [CategoriesController::class,'save']);
    Route::post('category-order', [CategoriesController::class,'saveCategoryOrder']);
    Route::post('category-values', [CategoriesController::class,'saveCategoryValue']);

    Route::get('districts/{id}', [DistrictsController::class,'getByID']);
    Route::get('districts/{id}/delivery-order', [CustomersController::class,'getDeliveryOrderByDistrictID']);
    Route::post('districts', [DistrictsController::class,'save']);
    Route::post('districts/{id}/delivery-order', [CustomersController::class,'saveDeliveryOrder']);
    Route::get('products/{id}', [ProductsController::class,'getByID']);
    Route::post('products', [ProductsController::class,'save']);
    Route::get('suppliers/{id}', [SuppliersController::class,'getByID']);
    Route::post('suppliers', [SuppliersController::class,'save']);
    Route::post('invoice-settings', [InvoicesController::class,'saveSetting']);

    Route::get('customer-info', [CustomersController::class,'getCustomer']);
    Route::get('product-info', [ProductsController::class,'getProduct']);
    Route::post('invoices', [InvoicesController::class,'save']);
    Route::get('invoices/next', [InvoicesController::class,'getNext']);
    Route::get('invoices/prev', [InvoicesController::class,'getPrev']);
    Route::put('invoices/void', [InvoicesController::class,'void']);
    Route::get('customers/products/prev-five-record', [ProductsController::class,'getProductLast5Order']);
    Route::get('invoices/delivery-date',[InvoicesController::class,'getDeliveryDateFromRequest']);
    Route::get('check-duplicate-product',[ProductsController::class,'checkDuplicateProductOnSameDeliveryDate']);

    Route::post('invoices/search', [InvoicesController::class,'getSearchResult']);

    Route::get('supplier-info', [SuppliersController::class,'getSupplier']);
    Route::post('receipts', [ReceiptsController::class,'save']);
    Route::get('receipts/next', [ReceiptsController::class,'getNext']);
    Route::get('receipts/prev', [ReceiptsController::class,'getPrev']);
        //Route::post('voidReceipt', [ReceiptsController::class,'void']);
    Route::post('receipts/search', [ReceiptsController::class,'getSearchResult']);



    Route::get('checklist',[ChecklistsController::class,'getByDate']);
    Route::post('checklist/status',[ChecklistsController::class,'changeStatus']);

    Route::get('preparation-list',[ProductsController::class,'genPreparationList']);

    Route::post('daily-settlement',[SettlementsController::class,'updateDailySettlement']);
    Route::get('monthly-statement',[SettlementsController::class,'monthlyStatements']);

    Route::get('invoices/{invoice_code}', [InvoicesController::class,'show']);
    Route::get('receipts/{receipt_code}', [ReceiptsController::class,'show']);


    Route::get('customers', [CustomersController::class,'index']);
    Route::get('districts', [DistrictsController::class,'index']);
    Route::get('categories', [CategoriesController::class,'index']);
    Route::get('products', [ProductsController::class,'index']);
    Route::get('suppliers', [SuppliersController::class,'index']);
    Route::get('invoices-settings', [InvoicesController::class,'index']);



});

Route::get('/redirect', function (Request $request) {
            // $request->session()->put('state', $state = Str::random(40));
         
            // $query = http_build_query([
            //     'client_id' => 'client-id',
            //     'redirect_uri' => 'http://third-party-app.com/callback',
            //     'response_type' => 'code',
            //     'scope' => '',
            //     'state' => $state,
            //     // 'prompt' => '', // "none", "consent", or "login"
            // ]);
         
            // return redirect('http://passport-app.test/oauth/authorize?'.$query);
            return redirect(config('app.login_url'));
        })->name('login');

    //not yet handled
    Route::get('printInvoice/{check_all}/{invoice_list?}/{invoice_code?}/{invoice_date?}/{delivery_date?}/{customer_code?}/{customer_name?}/{district_code?}/{car_no?}', [InvoicesController::class,'print']);



