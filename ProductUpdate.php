<?php

namespace migrate;

class ProductUpdate
{
    private $db;
    private $schema;

    public function __construct($db)
    {
        $this->db = $db;
        $this->schema = new Schema($db, ['oc_product', 'oc_product_option_value']);
    }

    private function oc_product($table, $row)
    {
        $data = [];

        foreach ($this->schema->prepare($table, $row, ['product_id']) as $key => $value) {
            $data[] = sprintf("`%s`='%s'", $key, $value);
        }

        if ($data = join(', ', $data)) {
            $this->db->query(sprintf("UPDATE `%s`.`%s` SET %s WHERE `product_id`='%s'", DB_DATABASE, $table, $data, $row['product_id']));
        }
    }

    private function oc_product_option_value($table, $row)
    {
        $data = [];

        foreach ($this->schema->prepare($table, $row, ['product_option_value_id']) as $key => $value) {
            $data[] = sprintf("`%s`='%s'", $key, $value);
        }

        if ($data = join(', ', $data)) {
            $this->db->query(sprintf("UPDATE `%s`.`%s` SET %s WHERE `product_option_value_id`='%s'", DB_DATABASE, $table, $data, $row['product_option_value_id']));
        }
    }

    public function apply()
    {
        foreach ($this->schema->getTables() as $table) {
            foreach ($this->db->query(sprintf("SELECT * FROM " . DB_DATABASE_OLD . ".%s", $table))->rows as $row) {
                if (method_exists($this, $table)) {
                    $this->{$table}($table, $row);
                }
            }
        }
    }
}