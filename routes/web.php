<?php
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
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome']);
// });

// Route::get('/dashboard', function () {
//     return view('dashboard']);
// })->middleware(['auth', 'verified'])->name('dashboard']);

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit']);
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update']);
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy']);
// });

Route::group(['middleware' => 'auth'], function() {
    //Route::group(array('before' => 'auth'), function() {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/', [OrdersController::class, 'index']);
        Route::get('order', [OrdersController::class, 'index']);
        Route::get('order/{invoice_code}', [InvoicesController::class,'show']);
        Route::get('searchInvoice', function () {
            return view('search.searchInvoice',['districts' => App\Models\District::all()]);
        });
        Route::get('searchReceipt', function () {
            return view('search.searchReceipt');
        });
    
        Route::get('receipt', [ReceiptsController::class,'index']);
        Route::get('receipt/{receipt_code}', [ReceiptsController::class,'show']);
    
        Route::get('inventory', function () {
            return view('pages.inventory');
        });
    
        Route::get('stat', function () {
            return view('pages.stat');
        });
    
        Route::get('settings', function () {
            return view('pages.settings');
        });
        Route::get('customerSettings', [CustomersController::class,'index']);
        Route::get('districtSettings', [DistrictsController::class,'index']);
        Route::get('categorySettings', [CategoriesController::class,'index']);
        Route::get('productSettings', [ProductsController::class,'index']);
        Route::get('supplierSettings', [SuppliersController::class,'index']);
        Route::get('invoiceSettings', [InvoicesController::class,'index']);
    
        Route::get('invoiceResult', [InvoicesController::class,'getSearchResult']);
        Route::get('receiptResult', [ReceiptsController::class,'getSearchResult']);
        Route::get('printInvoice/{check_all}/{invoice_list?}/{invoice_code?}/{invoice_date?}/{delivery_date?}/{customer_code?}/{customer_name?}/{district_code?}/{car_no?}', [InvoicesController::class,'print']);
    
        Route::get('checklist',[ChecklistsController::class,'getByDate']);
        Route::get('preparationlist',function () {
            return view('pages.preparationlist',['districts' => App\District::all(),'delivery_date' => date('Y-m-d')]);
        });
    
        Route::get('dailySettlement',[SettlementsController::class,'getDailyByDate']);
        Route::get('monthlyStatement',[SettlementsController::class,'showMonthlyStatementPage']);
    
        Route::get('phoneList',[CustomersController::class,'getPhoneList']);
    
        Route::get('product_code_autocomplete', [ProductsController::class,'autocomplete']);
        Route::get('customer_code_autocomplete', [CustomersController::class,'autocomplete']);
        Route::get('phone_autocomplete', [CustomersController::class,'phoneAutocomplete']);
        Route::get('supplier_code_autocomplete', [SuppliersController::class,'autocomplete']);


        Route::post('getCustomerByID', [CustomersController::class,'getByID']);
        Route::post('saveCustomer', [CustomersController::class,'save']);
        Route::post('getCategoryByID', [CategoriesController::class,'getByID']);
        Route::post('getCategoryValueByCategoryID', [CategoriesController::class,'getCategoryValueByCategoryID']);
        Route::post('getCategoryValueByID', [CategoriesController::class,'getCategoryValueByID']);
        Route::post('saveCategory', [CategoriesController::class,'save']);
        Route::post('saveCategoryOrder', [CategoriesController::class,'saveCategoryOrder']);
        Route::post('saveCategoryValue', [CategoriesController::class,'saveCategoryValue']);
        Route::post('getDistrictByID', [DistrictsController::class,'getByID']);
        Route::post('getCustomerDeliveryOrderByDistrictID', [CustomersController::class,'getDeliveryOrderByDistrictID']);
        Route::post('saveDistrict', [DistrictsController::class,'save']);
        Route::post('saveCustomerDeliveryOrder', [CustomersController::class,'saveDeliveryOrder']);
        Route::post('getProductByID', [ProductsController::class,'getByID']);
        Route::post('saveProduct', [ProductsController::class,'save']);
        Route::post('getSupplierByID', [SuppliersController::class,'getByID']);
        Route::post('saveSupplier', [SuppliersController::class,'save']);
        Route::post('saveInvoiceSetting', [InvoicesController::class,'saveSetting']);

        Route::post('getcust', [CustomersController::class,'getCustomer']);
        Route::post('getprod', [ProductsController::class,'getProduct']);
        Route::post('saveinvoice', [InvoicesController::class,'save']);
        Route::post('nextinvoice', [InvoicesController::class,'getNext']);
        Route::post('previnvoice', [InvoicesController::class,'getPrev']);
        Route::post('voidInvoice', [InvoicesController::class,'void']);
        Route::post('getprodlastorderdate', [ProductsController::class,'getProductLast5Order']);
        Route::post('getDeliveryDate',[InvoicesController::class,'getDeliveryDateFromRequest']);
        Route::post('checkDuplicateProductOnSameDeliveryDate',[ProductsController::class,'checkDuplicateProductOnSameDeliveryDate']);

        Route::post('invoiceResult', [InvoicesController::class,'getSearchResult']);

        Route::post('getSupplier', [SuppliersController::class,'getSupplier']);
        Route::post('saveReceipt', [ReceiptsController::class,'save']);
        Route::post('nextReceipt', [ReceiptsController::class,'getNext']);
        Route::post('prevReceipt', [ReceiptsController::class,'getPrev']);
        //Route::post('voidReceipt', [ReceiptsController::class,'void']);
        Route::post('receiptResult', [ReceiptsController::class,'getSearchResult']);



        Route::post('checklist',[ChecklistsController::class,'getByDate']);
        Route::post('checklistChangeStatus',[ChecklistsController::class,'changeStatus']);
        Route::post('preparationlist',[ProductsController::class,'genPreparationList']);

        Route::post('updateDailySettlement',[SettlementsController::class,'updateDailySettlement']);
        Route::post('monthlyStatement',[SettlementsController::class,'monthlyStatements']);
                
    
    });

require __DIR__.'/auth.php';
