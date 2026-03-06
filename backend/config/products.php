<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Supplier Margin
    |--------------------------------------------------------------------------
    |
    | When an admin saves a product without specifying the customer-facing SRP,
    | the system auto-computes it as:
    |
    |   SRP = supplier_price × (1 + default_margin)
    |
    | Set PRODUCT_DEFAULT_MARGIN in your .env to override (decimal, e.g. 0.30
    | for a 30 % markup). Defaults to 25 %.
    |
    */

    'default_margin' => (float) env('PRODUCT_DEFAULT_MARGIN', 0.30),
];
