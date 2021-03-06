<?php

namespace Scheezy;

class Database
{
    private $yaml;

    public function __construct($yaml, \PDO $connection)
    {
        $this->yaml = $yaml;
        $this->connection = $connection;
    }

    public function getTableName()
    {
        return $this->yaml['table'];
    }

    public function getTable()
    {
        $type = $this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME);
        $class = 'Scheezy\\Table\\' . ucfirst($type);
        return new $class($this->getTableName(), $this->connection);
    }

    public function __toString()
    {
        $table = $this->getTable();
        $type = $this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME);

        if ($table->exists($type)) {
            $prefix = 'Scheezy\\Table\\Modifier\\';
        } else {
            $prefix = 'Scheezy\\Table\\Creator\\';
        }

        $class = $prefix . ucfirst($type);
        $modifier = new $class($table, $this->yaml);
        return $modifier->__toString();
    }

    public function synchronize()
    {
        $sql = $this->__toString($this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME));
        if (strlen($sql)) {
            $this->connection->exec($sql);
        }
    }
}
