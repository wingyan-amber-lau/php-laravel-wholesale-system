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

// Route::group(['middleware' => 'auth'], function() {
//     //Route::group(array('before' => 'auth'), function() {
//         Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//         Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//         Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//         Route::get('/', [OrdersController::class, 'index']);
//         Route::get('order', [OrdersController::class, 'index']);
//         Route::get('order/{invoice_code}', [InvoicesController::class,'show']);
//         Route::get('searchInvoice', function () {
//             return view('search.searchInvoice',['districts' => App\Models\District::all()]);
//         });
//         Route::get('searchReceipt', function () {
//             return view('search.searchReceipt');
//         });
    
//         Route::get('receipt', [ReceiptsController::class,'index']);
//         Route::get('receipt/{receipt_code}', [ReceiptsController::class,'show']);
    
//         Route::get('inventory', function () {
//             return view('pages.inventory');
//         });
    
//         Route::get('stat', function () {
//             return view('pages.stat');
//         });
    
//         Route::get('settings', function () {
//             return view('pages.settings');
//         });
//         Route::get('customerSettings', [CustomersController::class,'index']);
//         Route::get('districtSettings', [DistrictsController::class,'index']);
//         Route::get('categorySettings', [CategoriesController::class,'index']);
//         Route::get('productSettings', [ProductsController::class,'index']);
//         Route::get('supplierSettings', [SuppliersController::class,'index']);
//         Route::get('invoiceSettings', [InvoicesController::class,'index']);
    
//         Route::get('invoiceResult', [InvoicesController::class,'getSearchResult']);
//         Route::get('receiptResult', [ReceiptsController::class,'getSearchResult']);
//         Route::get('printInvoice/{check_all}/{invoice_list?}/{invoice_code?}/{invoice_date?}/{delivery_date?}/{customer_code?}/{customer_name?}/{district_code?}/{car_no?}', [InvoicesController::class,'print']);
    
//         Route::get('checklist',[ChecklistsController::class,'getByDate']);
//         Route::get('preparationlist',function () {
//             return view('pages.preparationlist',['districts' => App\Models\District::all(),'delivery_date' => date('Y-m-d')]);
//         });
    
//         Route::get('dailySettlement',[SettlementsController::class,'getDailyByDate']);
//         Route::get('monthlyStatement',[SettlementsController::class,'showMonthlyStatementPage']);

        
//     });

// require __DIR__.'/auth.php';
