<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case ApproveVendor = 'ApproveVendor';
    case BuyProducts = 'BuyProducts';
    case SellProducts = 'SellProducts';
}
