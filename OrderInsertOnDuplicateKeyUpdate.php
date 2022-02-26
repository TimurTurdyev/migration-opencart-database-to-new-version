<?php

namespace migrate;

class OrderInsertOnDuplicateKeyUpdate extends AbstractInsertOnDuplicateKeyUpdate
{
    protected $tables = [
        'oc_order' => ['order_id'],
        'oc_order_history' => ['order_history_id'],
        'oc_order_option' => ['order_option_id'],
        'oc_order_product' => ['order_product_id'],
        'oc_order_total' => ['order_total_id'],
    ];
}