<?php

namespace App\Migration;

class SQLGenerator
{
  public static function generateEnumTypes(array $schemas, string $driver = 'pgsql'): array
  {
    $enums = [];
    if ($driver !== 'pgsql') return $enums;

    foreach ($schemas as $table => $columns) {
      foreach ($columns as $name => $opts) {
        $type = $opts['type'] ?? null;
        if (is_array($type) && $type[0] === 'ENUM') {
          $enumName = $table . '_' . $name . '_enum';
          $values = array_map(fn($v) => "'$v'", $type[1]);
          $enums[$enumName] = "DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = '$enumName') THEN CREATE TYPE $enumName AS ENUM (" . implode(',', $values) . "); END IF; END $$;";
        }
      }
    }
    return array_values($enums);
  }

  public static function generateCreateTable(string $table, array $columns, string $driver = 'pgsql'): string
  {
    $sql = "CREATE TABLE IF NOT EXISTS \"$table\" (\n";
    $defs = [];
    $primary = [];
    $foreigns = [];

    foreach ($columns as $name => $opts) {
      $def = "\"$name\" ";
      $type = $opts['type'];

      // Type ENUM
      if (is_array($type) && $type[0] === 'ENUM') {
        $enumName = $table . '_' . $name . '_enum';
        $def .= $driver === 'pgsql' ? $enumName : 'ENUM(' . implode(',', array_map(fn($v) => "'$v'", $type[1])) . ')';
      } elseif (strtoupper($type) === 'SERIAL' && $driver === 'pgsql') {
        $def .= 'SERIAL';
      } else {
        $def .= $type;
      }

      if (!empty($opts['not_null'])) $def .= ' NOT NULL';
      if (!empty($opts['unique'])) $def .= ' UNIQUE';
      if (isset($opts['default'])) {
        $default = $opts['default'];
        if (is_string($default) && strtoupper($default) !== 'CURRENT_TIMESTAMP') {
          $default = "'" . addslashes($default) . "'";
        }
        $def .= ' DEFAULT ' . $default;
      }
      $defs[] = $def;

      if (!empty($opts['primary'])) $primary[] = "\"$name\"";

      // Foreign key
      if (!empty($opts['foreign']) && is_array($opts['foreign'])) {
        [$refTable, $refCol] = $opts['foreign'];
        $foreigns[] = "FOREIGN KEY (\"$name\") REFERENCES \"$refTable\"(\"$refCol\")";
      }
    }

    if ($primary) {
      $defs[] = 'PRIMARY KEY (' . implode(',', $primary) . ')';
    }
    foreach ($foreigns as $fk) {
      $defs[] = $fk;
    }

    $sql .= implode(",\n  ", $defs) . "\n);";
    return $sql;
  }
}
