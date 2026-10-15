<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Base controller: menyediakan $this->authorize() (Gate/Policy) untuk semua controller.
 */
abstract class Controller
{
    // Trait untuk $this->authorize(...) via Gate/Policy di controller.
    use AuthorizesRequests;
}
