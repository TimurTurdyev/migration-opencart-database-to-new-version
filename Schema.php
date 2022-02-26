<?php

namespace migrate;

class Schema
{
    private array $tables = [];

    public function __construct($db, $tables = [])
    {
        foreach ($tables as $table) {
            foreach ($db->query(sprintf('SHOW COLUMNS FROM %s.%s', DB_DATABASE, $table))->rows as $row) {
                $this->tables[$table][$row['Field']] = $row;
            }
        }
    }

    public function getTables()
    {
        return array_keys($this->tables);
    }

    public function prepare(string $table, array $data = [], $exclude_keys = [])
    {
        $rows = [];

        if (isset($this->tables[$table])) {
            foreach ($data as $key => $value) {
                if (isset($this->tables[$table][$key])) {
                    if ($exclude_keys && in_array($key, $exclude_keys)) {
                        continue;
                    }
                    $rows[$key] = $value;
                }
            }
        }

        return $rows;
    }
}