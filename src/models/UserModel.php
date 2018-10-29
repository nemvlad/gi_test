<?php

namespace App\models;

use App\config\db\User;

class UserModel extends CrudModel {

    function getTableName()
    {
        return User::getTableName();
    }

    function addObjects(array $inputParams)
    {
        /** @var \Slim\PDO\Database $pdo */
        $pdo = $this->container['db'];

        foreach ($inputParams as $params)
        {
            if (!is_array($params))
                throw (new \Exception());

            $row = $pdo->query('SELECT UUID() AS ' . User::uuid);
            $row = $row->fetch();
            $params[User::uuid] = $row[User::uuid];

            $insertStatement = $pdo
                ->insert(array_keys($params))
                ->into($this->getTableName())
                ->values(array_values($params));
            $insertId = $insertStatement->execute(true);

            $addedObject = $this->getObject($insertId);

            if (!$addedObject)
                throw new \Exception($insertId);

            $this->affectedObjects[] = $addedObject;
        }
    }
} 