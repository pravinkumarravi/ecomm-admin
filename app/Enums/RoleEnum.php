<?php

namespace App\Enums;

enum RoleEnum: string
{
    case Admin = 'admin';
    case Seller = 'seller';
    case Customer = 'customer';
}
