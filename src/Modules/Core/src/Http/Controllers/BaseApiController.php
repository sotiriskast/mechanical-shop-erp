<?php

namespace Modules\Core\src\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Modules\Core\src\Traits\ApiResponse;

abstract class BaseApiController extends Controller
{
    use AuthorizesRequests, ValidatesRequests, ApiResponse;
}
