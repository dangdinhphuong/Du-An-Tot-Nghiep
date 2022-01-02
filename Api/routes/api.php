<?php

use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\DashbordController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\AssetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetRequestController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Staffs\TaskController;
use App\Http\Controllers\Api\Staffs\Receipts_reasonController;
use App\Http\Controllers\Api\Staffs\ReceiptController;
use App\Http\Controllers\Api\Staffs\ProjectServiceController;
use App\Http\Controllers\Api\Staffs\ServiceIndexController;
use App\Http\Controllers\Api\Staffs\ContractController;
use App\Http\Controllers\Api\Staffs\Student_interactController;
use App\Http\Controllers\Api\Students\AuthController as StudentsAuthController;
use App\Http\Controllers\Api\Staffs\AuthController as StaffsAuthController;
use App\Http\Controllers\Api\Staffs\ContractHistoryInvoiceController;
use App\Http\Controllers\Api\Staffs\InvoiceController;
use App\Http\Controllers\Api\Staffs\MaintainController as StaffsMaintainController;
use App\Http\Controllers\Api\Students\ContractController as StudentsContractController;
use App\Http\Controllers\Api\Students\InvoiceController as StudentsInvoiceController;
use Illuminate\Support\Facades\Artisan;

// CRUD projects
Route::group([
    'prefix' => 'projects', 'namespace' => 'Api',
    'middleware' => 'auth.jwt',
], function () {
    Route::get('/', 'ProjectController@index')->name('index')->middleware('can:project-list');
    Route::get('/{project}', 'ProjectController@show')->name('show')->middleware('can:project-detail');
    Route::post('/', 'ProjectController@store')->name('store')->middleware('can:project-add');
    Route::put('/{project}', 'ProjectController@update')->name('update')->middleware('can:project-edit');
    Route::delete('/{project}', 'ProjectController@delete')->name('delete')->middleware('can:project-delete');
});
// CRUD building
Route::group([
    'prefix' => 'buildings', 'namespace' => 'Api',
    'middleware' => 'auth.jwt',
], function () {
    Route::get('/', 'BuildingController@index')->name('index')->middleware('can:building-list');
    Route::get('/{building}', 'BuildingController@show')->name('show')->middleware('can:building-detail');
    Route::post('/', 'BuildingController@store')->name('store')->middleware('can:building-add');
    Route::post('/createOrUpdate', 'BuildingController@storeOrUpdateForm')->name('createOrUpdate');
    Route::put('/{building}', 'BuildingController@update')->name('update')->middleware('can:building-edit');
    Route::delete('/{building}', 'BuildingController@delete')->name('delete')->middleware('can:building-delete');
});
// CRUD floor
Route::group([
    'prefix' => 'floors', 'namespace' => 'Api',
    'middleware' => 'auth.jwt',
], function () {
    Route::get('/', 'FloorController@index')->name('index')->middleware('can:floor-list');
    Route::get('/{floor}', 'FloorController@show')->name('show')->middleware('can:floor-detail');
    Route::post('/', 'FloorController@store')->name('store')->middleware('can:floor-add');
    Route::put('/{floor}', 'FloorController@update')->name('update')->middleware('can:floor-edit');
    Route::delete('/{floor}', 'FloorController@delete')->name('delete')->middleware('can:floor-delete');
});
// CRUD type_announces
Route::group([
    'prefix' => 'type_announces', 'namespace' => 'Api',
    'middleware' => 'auth.jwt',
], function () {
    Route::get('/', 'TypeAnnoucementController@index')->name('index');
    Route::get('/{typeAnnounce}', 'TypeAnnoucementController@show')->name('show');
    Route::post('/', 'TypeAnnoucementController@store')->name('store');
    Route::put('/{typeAnnounce}', 'TypeAnnoucementController@update')->name('update');
    Route::delete('/{typeAnnounce}', 'TypeAnnoucementController@delete')->name('delete');
});
// Contracts 
// CRUD announcements
Route::group([
    'prefix' => 'announcements', 'namespace' => 'Api',
    'middleware' => 'auth.jwt',
], function () {
    Route::get('/', 'AnnouncementController@index')->name('index')->middleware('can:Notify-list');
    Route::get('/{announcement}', 'AnnouncementController@show')->name('show')->middleware('can:Notify-detail');
    Route::post('/', 'AnnouncementController@store')->name('store')->middleware('can:Notify-add');
    Route::put('/{announcement}', 'AnnouncementController@update')->name('update')->middleware('can:Notify-edit');
    Route::delete('/{announcement}', 'AnnouncementController@delete')->name('delete')->middleware('can:Notify-delete');
});
// Contracts 

Route::group([
    'prefix' => 'contracts',
    'middleware' => 'auth.jwt',
], function () {
    Route::get('index', [ContractController::class, 'index'])->middleware('can:hop-dong-list');
    Route::get('create', [ContractController::class, 'create']);
    Route::post('store', [ContractController::class, 'store'])->middleware('can:hop-dong-add');
    Route::get('edit/{id}', [ContractController::class, 'edit'])->middleware('can:hop-dong-detail');
    Route::put('update/{id}', [ContractController::class, 'update'])->middleware('can:hop-dong-edit');
    Route::delete('end-contract/{id}', [ContractController::class, 'endContract'])->middleware('can:hop-dong-delete');
    Route::post('search-student', [ContractController::class, 'filterStudent']);
    Route::put('collect/deposit/{id}', [ContractController::class, 'collectDeposit']);
    Route::get('collect/deposit/{id}', [ContractController::class, 'collectDepositForm'])->middleware('can:hop-dong-collect-deposit');
    Route::get('end-contract/index', [ContractController::class, 'indexEndContract'])->middleware('can:history-contract-list');
    Route::get('end-contract/edit/{id}', [ContractController::class, 'editEndContract'])->middleware('can:history-contract-edit');
    Route::get('change-bed/{id}', [ContractController::class, 'changeBed']);
    Route::put('change-bed/{id}', [ContractController::class, 'moveBed'])->middleware('can:hop-dong-change-bed');
});
// invoice
Route::group([
    'prefix' => 'invoices',
    // 'middleware' => 'auth.jwt',
], function () {
    Route::get('index-based-on-months', [InvoiceController::class, 'indexBasedOnMonth'])->middleware('can:invoice-month-list');
    Route::get('create/invoice/month/{id}', [InvoiceController::class, 'createInvoiceBasedOnMonth'])->middleware('can:invoice-month-add');
    Route::get('show/invoice/month/{id}', [InvoiceController::class, 'showInvoiceBasedOnMonth'])->middleware('can:invoice-month-edit');
    Route::post('store/invoice/month/{id}', [InvoiceController::class, 'storeInvoiceBasedOnMonth'])->middleware('can:invoice-month-add');
    Route::get('index-based-on-contract', [InvoiceController::class, 'indexBasedOnContract'])->middleware('can:invoice-contract-list');
    Route::get('create/invoice/contract/{id}', [InvoiceController::class, 'createInvoiceBasedOnContract'])->middleware('can:invoice-contract-add');
    Route::post('store/invoice/contract/{id}', [InvoiceController::class, 'storeInvoiceBasedOnContract'])->middleware('can:invoice-contract-add');
    Route::get('show/invoice/contract/{id}', [InvoiceController::class, 'showInvoiceBasedOnContract'])->middleware('can:invoice-contract-edit');
    Route::put('update/invoice/status/{id}', [InvoiceController::class, 'updateStatusInvoice'])->middleware('can:invoice-pay');
});
// Project_services
Route::prefix('project-service')->group(function () {
    Route::get('index', [ProjectServiceController::class, 'index'])->middleware('can:project-service-list');
    Route::get('create', [ProjectServiceController::class, 'create'])->middleware('can:project-service-add');
    Route::post('store', [ProjectServiceController::class, 'store'])->middleware('can:project-service-add');
    Route::get('edit/{id}', [ProjectServiceController::class, 'edit'])->middleware('can:project-service-detail');
    Route::put('update', [ProjectServiceController::class, 'update'])->middleware('can:project-service-edit');
    Route::delete('delete/{pid}', [ProjectServiceController::class, 'delete'])->middleware('can:project-service-delete');
});
Route::group([
    'prefix' => 'contract-history-invoice',
    'middleware' => 'auth.jwt',
], function () {
    Route::get('index', [ContractHistoryInvoiceController::class, 'index'])->middleware('can:hop-dong-history-invoice-list');
    Route::get('show/{id}', [ContractHistoryInvoiceController::class, 'show'])->middleware('can:hop-dong-history-invoice-edit');
});
// Service-index
Route::prefix('service-index')->group(function () {
    Route::get('index', [ServiceIndexController::class, 'index'])->middleware('can:electricity-water-list');
    Route::put('update/{id}', [ServiceIndexController::class, 'update'])->middleware('can:electricity-water-add');
    Route::get('show/{id}', [ServiceIndexController::class, 'edit'])->middleware('can:electricity-water-detail');
});
//  Maintain 
Route::group([
    'middleware' => 'auth.jwt',
    'prefix' => 'maintain'
], function ($router) {
    Route::get('index', [StaffsMaintainController::class, 'index'])->middleware('can:repair-list');
    Route::get('create', [StaffsMaintainController::class, 'create'])->middleware('can:repair-add');
    Route::post('store', [StaffsMaintainController::class, 'store'])->middleware('can:repair-add');
    Route::get('edit/{id}', [StaffsMaintainController::class, 'edit'])->middleware('can:repair-edit');
    Route::put('update/{id}', [StaffsMaintainController::class, 'update'])->middleware('can:repair-edit');
    Route::put('update/status/{id}', [StaffsMaintainController::class, 'updateStatus'])->middleware('can:repair-status-edit');
    Route::delete('delete/{id}', [StaffsMaintainController::class, 'destroy'])->middleware('can:repair-delete');
});
// Auth
Route::group([
    // 'middleware' => 'auth.jwt',
    'prefix' => 'auth'
], function ($router) {
    Route::post('student/register', [StudentsAuthController::class, 'studentRegister']);
    Route::post('staff/register', [StaffsAuthController::class, 'staffRegister'])->middleware('can:user-add');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/change-pass', [AuthController::class, 'changePassWord']);
    Route::put('/user-update-profile', [AuthController::class, 'userUpdateProfile']);
});
//  Role 
Route::group([
    'middleware' => 'auth.jwt',
    'prefix' => 'role'
], function ($router) {
    Route::get('show', [RoleController::class, 'index']);
    Route::get('create', [RoleController::class, 'create']);
    Route::post('store', [RoleController::class, 'store']);
    Route::get('edit/{id}', [RoleController::class, 'edit']);
    Route::put('update/{id}', [RoleController::class, 'update']);
    Route::delete('delete/{id}', [RoleController::class, 'destroy'])->middleware('can:role-delete');
    Route::get('detail_role_by_id/{id}', [RoleController::class, 'getRoleById']);
    Route::get('get_role_by_id/{id}', [RoleController::class, 'getRoleById'])->middleware('can:permission-detail');
    Route::get('get_role_all', [RoleController::class, 'getRoleAll'])->middleware('can:role-list');
    Route::get('get_permissions_all', [RoleController::class, 'getPermissionAll'])->middleware('can:permission-list');
    Route::post('add_Permission_To_Role', [RoleController::class, 'addPermissionToRole'])->middleware('can:role-add');
    Route::put('update_Permission_To_Role', [RoleController::class, 'updatePermissionToRole'])->middleware('can:permission-add');
});

// users
Route::group([
    'middleware' => 'auth.jwt',
    'prefix' => 'user'
], function ($router) {
    Route::get('getUserStudent', [UserController::class, 'getUserStudent'])->middleware('can:tenant-list');
    Route::get('getUserStaff', [UserController::class, 'getUserStaff'])->middleware('can:user-list');
    Route::get('create', [UserController::class, 'showRole']);
    Route::get('findBy/{id}', [UserController::class, 'getUserId']);
    Route::get('staff/edit/{id}', [UserController::class, 'adminEditUser'])->middleware('can:user-edit');
    Route::put('student/update/{requestId}', [UserController::class, 'studentUpdateUser'])->middleware('can:tenant-edit');
    Route::put('staff/update/{id}', [UserController::class, 'adminUpdateUser'])->middleware('can:user-edit');
    Route::get('StudentSearch', [StudentsAuthController::class, 'getStudentSearch'])->middleware('can:user-list');
    Route::get('getActive/{id}', [UserController::class, 'getActive'])->middleware('can:user-edit');
    Route::put('userActive/{id}', [UserController::class, 'userActive'])->middleware('can:user-edit');
    Route::post('staff/registerStudent', [StaffsAuthController::class, 'studentRegister'])->middleware('can:tenant-add');
});

// Unit_asset,asset,producer,type_asset
Route::middleware([
    'middleware' => 'auth.jwt',
])->group(function ($router) {
    $router->resource('unit_assets', \Api\Unit_assetController::class);
    $router->resource('type_assets', \Api\Asset_typeController::class);
    $router->resource('producers', \Api\ProducerController::class);
    $router->get('data_to_asset', [AssetController::class, 'getDataToAsset']);
    $router->resource('assets', \Api\AssetController::class);
    $router->get('prefix_data_asset', [AssetController::class, 'prefixData']);
    $router->resource('rooms', \Api\RoomController::class);
    $router->resource('room_types', \Api\Room_typeController::class);
    $router->resource('beds', \Api\BedController::class);
    // $router->resource('bed_histories', \Api\Bed_historiesController::class);

});
// Task
Route::group([
    'middleware' => 'auth.jwt',
    'prefix' => 'task'
], function () {
    Route::get('index', [TaskController::class, 'index'])->middleware('can:task-list');
    Route::get('show/detail/{id}', [TaskController::class, 'detail'])->middleware('can:task-detail');
    Route::get('create', [TaskController::class, 'create'])->middleware('can:task-add');
    Route::post('store', [TaskController::class, 'store'])->middleware('can:task-add');
    Route::get('edit/{id}', [TaskController::class, 'edit'])->middleware('can:task-edit');
    Route::put('update/{id}', [TaskController::class, 'update'])->middleware('can:task-edit');
    Route::delete('delete/{id}', [TaskController::class, 'delete'])->middleware('can:task-delete');
    Route::put('getStatus/{id}', [TaskController::class, 'getStatus'])->middleware('can:task-status-edit');
});

// receipt_reasons
Route::group([
    'middleware' => 'auth.jwt',
    'prefix' => 'receipt_reasons'
], function () {
    Route::get('index', [Receipts_reasonController::class, 'index'])->middleware('can:reasons-list');
    Route::get('detail/{id}', [Receipts_reasonController::class, 'detail'])->middleware('can:reasons-detail');
    Route::post('store', [Receipts_reasonController::class, 'store'])->middleware('can:reasons-add');
    Route::put('update/{id}', [Receipts_reasonController::class, 'update'])->middleware('can:reasons-edit');
    Route::delete('delete/{id}', [Receipts_reasonController::class, 'destroy'])->middleware('can:reasons-delete');
});

// receipt
Route::group([
    'middleware' => 'auth.jwt',
    'prefix' => 'receipt'
], function () {
    Route::get('index', [ReceiptController::class, 'index'])->middleware('can:receipts-list');
    Route::get('show/{id}', [ReceiptController::class, 'show'])->middleware('can:receipts-edit');
    Route::post('store', [ReceiptController::class, 'store'])->middleware('can:Receipt-add');
});

// Student Interact
Route::group([
    'middleware' => 'auth.jwt',
    'prefix' => 'studentInteract'
], function () {
    Route::get('index', [Student_interactContrStudent_interactControlleroller::class, 'index'])->middleware('can:Interactive-list');
    Route::get('detail/{id}', [Student_interactController::class, 'detail'])->middleware('can:Interactive-detail');
    Route::get('create', [Student_interactController::class, 'create'])->middleware('can:Interactive-add');
    Route::post('store', [Student_interactController::class, 'store'])->middleware('can:Interactive-add');
    Route::get('edit/{id}', [Student_interactController::class, 'edit'])->middleware('can:Interactive-edit');
    Route::put('update/{id}', [Student_interactController::class, 'update'])->middleware('can:Interactive-edit');
    Route::delete('delete/{id}', [Student_interactController::class, 'delete'])->middleware('can:Interactive-delete');
});

Route::group([
    'middleware' => 'auth.jwt',
    'prefix' => 'dashboards'
], function () {
    Route::get('/', [DashbordController::class, 'index'])->middleware('can:dash-board-list');
    Route::get('/list-bed', [DashbordController::class, 'totalBed'])->middleware('can:dash-board-total-bed');
    Route::get('/month-user', [DashbordController::class, 'monthUser'])->middleware('can:dash-board-month-user');
});

Route::group([
    'middleware' => 'auth.jwt',
    'prefix' => 'reports'
], function () {
    Route::get('/rental-status', [ReportController::class, 'contractStatus'])->middleware('can:rental-status');
    Route::get('/student-info', [ReportController::class, 'studentRent'])->middleware('can:tenants');
    Route::get('/history-contract', [ReportController::class, 'historyContract'])->middleware('can:contract-history');
    Route::get('/report-receipt', [ReportController::class, 'reportReceipt'])->middleware('can:report-all');
    Route::get('/report-service-index', [ReportController::class, 'reportServiceIndex']);
    Route::get('/report-project-service', [ReportController::class, 'reportProjectService'])->middleware('can:report-project-service');
    Route::get('/report-asset', [ReportController::class, 'reportAsset'])->middleware('can:asset-inventory');
    Route::get('/report-maintence', [ReportController::class, 'reportMaintenace'])->middleware('can:maintenance-repair');
});

Route::group([
    'middleware' => 'auth.jwt',
    'prefix' => 'student'
], function () {
    Route::get('/contract', [StudentsContractController::class, 'index'])->middleware('can:hop-dong-detail');
    Route::get('/invoice', [StudentsInvoiceController::class, 'index'])->middleware('can:invoice-contract-list');
    Route::get('/invoice/{id}', [StudentsInvoiceController::class, 'edit'])->middleware('can:receipts-edit');
    Route::get('/notify', [AnnouncementController::class, 'index'])->middleware('can:Notify-list');
    Route::get('/notify/{id}', [AnnouncementController::class, 'show'])->middleware('can:Notify-detail');
});
Route::get('/config-cache', function () {
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('migrate:fresh --seed');
    return 'Config cache cleared';
});

Route::post('forgot_password', [PasswordResetRequestController::class, 'sendPasswordResetEmail']);
Route::post('forgot_new_password', [PasswordResetRequestController::class, 'changePassWords']);
