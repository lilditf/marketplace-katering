<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    // Trait untuk $this->authorize(...) via Gate/Policy di controller.
    use AuthorizesRequests;
}
