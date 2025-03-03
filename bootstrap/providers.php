<?php

return [
    App\Providers\AppServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    \Modules\Core\src\Providers\ModuleServiceProvider::class,
    \Modules\Core\src\Providers\CoreServiceProvider::class,
    \Modules\Core\src\Providers\ApiServiceProvider::class,


];
