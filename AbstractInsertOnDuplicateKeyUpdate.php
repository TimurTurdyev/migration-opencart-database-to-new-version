<?php

namespace migrate;

abstract class AbstractInsertOnDuplicateKeyUpdate
{
    private $db;
    private $schema;

    protected $tables = [];

    public function __construct($db)
    {
        $this->db = $db;
        $this->schema = new Schema($db, array_keys($this->tables));
    }

    public function apply()
    {
        foreach ($this->schema->getTables() as $table) {
            foreach ($this->db->query(sprintf("SELECT * FROM " . DB_DATABASE_OLD . ".%s", $table))->rows as $row) {
                if ($data = $this->schema->prepare($table, $row)) {
                    try {
                        $query = $this->prepareQuery($table, $data);
                        $this->db->query($query);
                    } catch (\Exception $exception) {
                        echo '<pre>';
                        echo $exception->getMessage();
                        echo '</pre>';
                        die();
                    }
                }
            }
        }
    }

    public function prepareQuery($table, $data)
    {
        $columns = [];
        $values = [];

        foreach ($data as $key => $val) {
            $columns[] = sprintf("`%s`", $key);
            $values[] = sprintf("'%s'", $this->db->escape($val));
        }

        $duplicate_keys = join(', ', array_map(function ($val) {
            return sprintf("`%s`=`%s`", $val, $val);
        }, $this->tables[$table]));

        return sprintf("INSERT INTO `%s`.`%s` (%s) VALUES(%s) ON DUPLICATE KEY UPDATE %s",
            DB_DATABASE,
            $table,
            join(', ', $columns),
            join(', ', $values),
            $duplicate_keys
        );
    }
}