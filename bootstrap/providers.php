<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Providers\JetstreamServiceProvider::class,
    App\Providers\DashboardServiceProvider::class,
    App\Providers\EventServiceProvider::class,

    Spatie\Permission\PermissionServiceProvider::class,

    \Modules\Core\src\Providers\ModuleServiceProvider::class,
    \Modules\Core\src\Providers\CoreServiceProvider::class,
    \Modules\Core\src\Providers\ApiServiceProvider::class,

];
