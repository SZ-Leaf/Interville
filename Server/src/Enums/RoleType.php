<?php

namespace App\Enums;

enum RoleType: string
{
   case ADMIN = 'admin';
   case MOD   = 'mod';
   case USER  = 'user';
}
