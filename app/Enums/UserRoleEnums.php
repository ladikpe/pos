<?php

namespace App\Enums;

enum UserRoleEnums:string
{
    case Admin = 'admin';
    case Cashier = 'cashier';
    case SuperAdmin = 'super-admin';
}
