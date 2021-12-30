<?php

namespace App\Http\Requests\Auth\RoleClaims;

use App\Models\User;

class RoleClaims
{
  /**
   * All roles with it's claims is assigned here
   *
   */
  protected static $roleClaims = [
    Roles::Admin => [
      Claims::ProductIndex,
    ],
    Roles::Cashier => []
  ];

  /**
   * Check if a ability is claimed by any User's roles
   * 
   * @var User authenticated user
   * @var string ability to check ( should be declared in App\Http\Requests\Auth\RoleClaims\Claim )
   */
  public static function isClaimed(User $user, string $ability)
  {
    $roles = $user->roles;
    foreach ($roles as $role) {
      if (in_array($ability, RoleClaims::$roleClaims[$role->role])) {
        return true;
      }
    }
    return false;
  }
}
