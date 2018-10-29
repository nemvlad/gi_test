<?php

namespace App\models;

use App\config\db\Gift;

class CrudModel extends BaseModel
{
    function addObjects(array $inputParams)
    {
        /** @var \Slim\PDO\Database $pdo */
        $pdo = $this->container['db'];

        foreach ($inputParams as $params)
        {
            if (!is_array($params))
                throw (new \Exception());

            unset($params[Gift::id]);

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

    function  updateObjects(array $inputParams) {
        /** @var \Slim\PDO\Database $pdo */
        $pdo = $this->container['db'];

        foreach ($inputParams as $params)
        {
            if (!is_array($params))
                throw (new \Exception());

            $id = $params[$this->getIdName()];
            unset($params[$this->getIdName()]);
            $oldObjectParams = $this->getObject($id);
            if (!$oldObjectParams)
                throw new \Exception($id);

            $updateStatement = $pdo->update($params)
                ->table($this->getTableName())
                ->where($this->getIdName(), '=', $id);

            $updateStatement->execute();

            $affectedRows = $this->getObject($id);

            $this->affectedObjects[] = $affectedRows;
        }
    }

    function deleteObjects(array $inputParams){
        /** @var \Slim\PDO\Database $pdo */
        $pdo = $this->container['db'];

        foreach ($inputParams as $params)
        {
            if (!is_array($params))
                throw (new \Exception());

            $id = $params[$this->getIdName()];

            $oldObject = $this->getObject($id);

            if (!$oldObject)
                throw new \Exception($id);

            $deleteStatement = $pdo->delete()
                ->from($this->getTableName())
                ->where($this->getIdName(), '=', $id);

            $deleteStatement->execute();
            $this->affectedObjects[] = $id;
        }
    }
}