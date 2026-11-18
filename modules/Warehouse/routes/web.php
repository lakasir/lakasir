<?php

use Illuminate\Support\Facades\Route;
use Modules\Warehouse\Http\Controllers\ModuleController;

Route::get('/', [ModuleController::class, 'index']);
