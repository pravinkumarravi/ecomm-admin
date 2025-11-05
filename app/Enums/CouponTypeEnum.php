<?php

namespace App\Enums;

enum CouponTypeEnum: string
{
    case Fixed = 'fixed';
    case Percentage = 'percentage';
}
