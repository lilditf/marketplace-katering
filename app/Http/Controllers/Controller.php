<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Base controller abstrak — induk semua controller aplikasi.
 */
abstract class Controller
{
    // Trait untuk $this->authorize(...) via Gate/Policy di controller.
    use AuthorizesRequests;
}
