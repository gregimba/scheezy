<?php

namespace Scheezy\Table\Modifier;

class Sqlite extends Mysql
{
    public function combineCommands($modifications, $postCommands)
    {
        $commands = array();

        $commands = array_map(
            function ($line) {
                $line = str_replace(' NOT NULL', '', $line);
                return "ALTER TABLE `{$this->table->name}` $line";
            },
            $modifications
        );

        $sql = implode(";\n", array_merge($commands, $postCommands));

        return $sql;
    }

    public function modifyField($name, $options)
    {
        $this->table->createField($name, $options, false);
        return '';
    }

    public function dropField($name)
    {
        return '';
    }


    public function dropIndex($name)
    {
        return "DROP INDEX `$name`";
    }
}
