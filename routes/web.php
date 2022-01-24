<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect(\route('dashboard'));
});

Auth::routes();

Route::get('/dashboard', [\App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function (){
    Route::resource('owners',\App\Http\Controllers\Staycation\OwnerController::class);

    //users//
    Route::resource('users',\App\Http\Controllers\Users\UserController::class);
    Route::get('/all-users',[\App\Http\Controllers\Users\UserController::class,'allUsers'])->name('all.users.list');
    //end users//

    Route::get('/all-owners',[\App\Http\Controllers\Staycation\OwnerController::class,'allOwners'])->name('all.owners.list');
    Route::get('/all-trashed-owners',[\App\Http\Controllers\Staycation\OwnerController::class,'allTrashedOwners'])->name('all.trashed.owners');
    Route::get('/display-all-trashed-owners',[\App\Http\Controllers\Staycation\OwnerController::class,'displayAllTrashed'])->name('display.all.trashed.owners');
    Route::post('/restore-all-trashed-owners',[\App\Http\Controllers\Staycation\OwnerController::class,'restoreAllTrashed'])->name('restore.all.trashed.owners');
    Route::post('/restore-trashed-owners/{owner}',[\App\Http\Controllers\Staycation\OwnerController::class,'restoreOwner'])->name('restore.trashed.owner');
    Route::delete('/permanent-trashed-owners',[\App\Http\Controllers\Staycation\OwnerController::class,'deletePermanentOwner'])->name('permanent.trashed.owner');
    Route::delete('/permanent-trashed-owners/{owner}',[\App\Http\Controllers\Staycation\OwnerController::class,'permanentlyDeleteSpecificUser'])->name('permanent.trashed.owner.specific');


    Route::resource('permissions',\App\Http\Controllers\Settings\PermissionController::class);
    Route::get('/all-permissions',[\App\Http\Controllers\Settings\PermissionController::class,'allPermissions'])->name('all.permissions.list');

    Route::resource('/roles',\App\Http\Controllers\Settings\RolesController::class);
    Route::get('/all-roles',[\App\Http\Controllers\Settings\RolesController::class,'allRoles'])->name('all.roles.list');

    //staycations//
    Route::resource('staycations',\App\Http\Controllers\Staycation\StaycationController::class);
    Route::get('/all-staycation/{owner}',[\App\Http\Controllers\Staycation\StaycationController::class,'allStayCationByOwner'])->name('owner.staycation.list');
    Route::get('/staycation-stored-info/{stayCationId}',[\App\Http\Controllers\Staycation\StaycationController::class,'getStoredInfo'])->name('staycation.stored.info');
    //end staycations//

    //assigned staycations//
    Route::resource('assigned-staycations',\App\Http\Controllers\Staycation\AssignedStayCationController::class);
    //end assigned staycations//

    //packages//
    Route::resource('/packages',\App\Http\Controllers\Staycation\PackageController::class);
    Route::get('/all-packages/{stayCationId}',[\App\Http\Controllers\Staycation\PackageController::class,'allPackages'])->name('owner.package.list');
    Route::get('/get-selected-staycations',[\App\Http\Controllers\Users\UserController::class,'get_staycation_details'])->name('get.selected.staycations');
    Route::post('/assign-users-to-staycation',[\App\Http\Controllers\Users\UserController::class,'assign_stayCation'])->name('assign.user.staycations');
    Route::get('/get-assigned-staycations/{user}',[\App\Http\Controllers\Users\UserController::class,'all_assigned_staycation'])->name('get.assigned.user.staycations');
    Route::delete('/remove-assigned-staycations/{staycation}/{user}',[\App\Http\Controllers\Users\UserController::class,'remove_assigned_staycation'])->name('remove.assigned.user.staycations');
    Route::get('/package-details/{package}',[\App\Http\Controllers\Staycation\PackageController::class,'getPackageById'])->name('get.package.by.id');
    //end packages//

    //bookings//
    Route::resource('bookings',\App\Http\Controllers\Staycation\BookingsController::class);
    Route::get('/booking-details/{booking}',[\App\Http\Controllers\Staycation\BookingsController::class,'getBookings'])->name('bookings.details');
    Route::get('/blocked-dates/{staycation}',[\App\Http\Controllers\Staycation\BookingsController::class,'blockedDates'])->name('bookings.blocked.dates');
    //end bookings//

    //customer//
    Route::resource('customers',\App\Http\Controllers\Staycation\CustomerController::class);
    Route::get('/all-customers',[\App\Http\Controllers\Staycation\CustomerController::class,'allCustomers'])->name('all.customers.list');
    //end customer //
});

Route::group(['middleware' => ['auth']], function(){
    Route::get('/provinces/{region}',[\App\Http\Controllers\AddressController::class,'getProvince'])->name('get.provinces');
    Route::get('/cities/{province}',[\App\Http\Controllers\AddressController::class,'getCities'])->name('get.cities');
    Route::get('/barangays/{city}',[\App\Http\Controllers\AddressController::class,'getBarangays'])->name('get.barangays');
});

Route::get('/test',function(){
    phpinfo();
});

