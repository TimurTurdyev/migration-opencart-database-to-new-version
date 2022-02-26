<?php

namespace migrate;

class CustomerInsertOnDuplicateKeyUpdate extends AbstractInsertOnDuplicateKeyUpdate
{
    protected $tables = [
        'oc_customer' => ['customer_id'],
        'oc_customer_activity' => ['customer_activity_id'],
        'oc_customer_ip' => ['customer_ip_id'],
        'oc_customer_login' => ['customer_login_id'],
        'oc_customer_wishlist' => ['customer_id', 'product_id'],
    ];
}