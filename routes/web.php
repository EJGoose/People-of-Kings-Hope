<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeopleApiController;

Route::get('/', [PeopleApiController::class, 'getData'])->name('main');

?>