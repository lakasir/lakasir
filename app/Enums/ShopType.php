<?php

namespace App\Enums;

enum ShopType: string
{
    case Retail = 'retail';

    case Wholesale = 'wholesale';

    case Fnb = 'fnb';

    case Fashion = 'fashion';

    case Pharmacy = 'pharmacy';

    case Other = 'other';
}
