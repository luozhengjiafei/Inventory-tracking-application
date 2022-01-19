<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\InventoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Inventory app home page
Route::get('/', function () {
    $inventory_items = DB::table('inventory')->get();

    return view('inventory/inventory', [
        'inventory_items' => $inventory_items
    ]);
});

// Insert new item into database inside InventoryController
Route::post('/insert', [InventoryController::class, 'insert'])->name('inventory');
// Delete a item in database inside InventoryController
Route::delete('/delete/{id}', [InventoryController::class, 'delete']);
// Update a item
Route::put('/update/{id}', [InventoryController::class, 'update']);
// Export the table into a CSV file
Route::get('/export' , [InventoryController::class, 'export']);
