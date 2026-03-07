<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/Routes/Main.php';

use App\Core\Core;
use App\Http\Route;

Core::dispatch(Route::routes());