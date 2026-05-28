<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Warehouse;

class WarehousePolicy
{
    /**
     * Any authenticated admin or warehouse employee may list warehouses.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isWarehouseEmployee();
    }

    /**
     * Admin may view any warehouse. WE may only view ones they created.
     */
    public function view(User $user, Warehouse $warehouse): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isWarehouseEmployee() && $warehouse->created_by === $user->id;
    }

    /**
     * Admin and WE may create warehouses.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isWarehouseEmployee();
    }

    /**
     * Admin may update any warehouse. WE may only update ones they own.
     */
    public function update(User $user, Warehouse $warehouse): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isWarehouseEmployee() && $warehouse->created_by === $user->id;
    }

    /**
     * Admin may delete any warehouse. WE may only delete ones they own.
     * Whether cargo references block the deletion is enforced in the controller,
     * not here — this policy is pure auth.
     */
    public function delete(User $user, Warehouse $warehouse): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isWarehouseEmployee() && $warehouse->created_by === $user->id;
    }
}
