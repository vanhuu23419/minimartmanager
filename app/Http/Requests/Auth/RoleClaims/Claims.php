<?php 

  namespace App\Http\Requests\Auth\RoleClaims;

  // This class is an Enum instance where you should define a Claim
  // a Claim will then be assigned to Roles
  abstract class Claims {
    const ProductIndex = 'product.index';
  }
