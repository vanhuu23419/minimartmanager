<?php

namespace App\Http\Requests\Auth\RoleClaims;

use App\Models\User;
use App\Models\UserRole;

class RoleClaims
{
  /**
   * All roles with it's claims is assigned here
   *
   */
  protected static $roleClaims = [
    Roles::Admin => [
      Claims::ProductIndex,
      Claims::ProductEdit,
      Claims::ProductStore,
      Claims::ProductDestroy,

      Claims::CategoryIndex,
      Claims::CategoryEdit,
      Claims::CategoryStore,
      Claims::CategoryDestroy,

      Claims::SellIndex,
      Claims::SellProducts,
      Claims::SellAddToReceipt,
      Claims::SellSaveReceipt,
      Claims::SellPrintReceipt,
  
      Claims::ReceiptIndex,
      Claims::ReceiptDestroy,
  
      Claims::ReportIndex,
      Claims::ReportChartReport,
    ],
    Roles::Cashier => [

      Claims::SellIndex,
      Claims::SellProducts,
      Claims::SellAddToReceipt,
      Claims::SellSaveReceipt,
      Claims::SellPrintReceipt,
    ]
  ];

  /**
   * Check if a ability is claimed by any User's roles
   * 
   * @var User authenticated user
   * @var string ability to check ( should be declared in App\Http\Requests\Auth\RoleClaims\Claim )
   */
  public static function isClaimed(User $user, string $ability)
  {
    $roles = 
      UserRole::selectRaw('
        user_id,
        GROUP_CONCAT(role) as roles
      ')
      ->where('user_id', '=', $user->id)
      ->groupBy(['user_id'])
      ->first()->roles;
    $roles = explode(',', $roles);

    foreach ($roles as $role) {
      if (in_array($ability, RoleClaims::$roleClaims[$role])) {
        return true;
      }
    }
    return false;
  }
}
