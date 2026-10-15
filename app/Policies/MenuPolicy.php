<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;

/**
 * Policy otomatis ditemukan Laravel (App\Policies\MenuPolicy untuk App\Models\Menu).
 * Integritas data: merchant hanya boleh mengelola menu miliknya sendiri.
 */
class MenuPolicy
{
    public function update(User $user, Menu $menu): bool
    {
        return $user->isMerchant()
            && $user->merchantProfile !== null
            && $user->merchantProfile->id === $menu->merchant_profile_id;
    }

    public function delete(User $user, Menu $menu): bool
    {
        return $this->update($user, $menu);
    }
}
