<?php

namespace App\Enums;

enum PaymentMethodEnum: string
{
    case COD = 'cod';
    case Card = 'card';
    case UPI = 'upi';
    case Wallet = 'wallet';
    case PayPal = 'paypal';
    case NetBanking = 'net_banking';
}
