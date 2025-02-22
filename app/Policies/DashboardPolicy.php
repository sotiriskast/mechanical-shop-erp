<?php
namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DashboardPolicy
{
    use HandlesAuthorization;

    public function viewDashboard(User $user): bool
    {
        return $user->hasAnyPermission([
            'view-dashboard',
            'view-customers',
            'view-vehicles',
            'view-work-orders',
            'view-invoices'
        ]);
    }

    public function viewReports(User $user): bool
    {
        return $user->hasPermissionTo('view-reports');
    }
}
