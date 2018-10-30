<?php

namespace App\models;

use App\config\db\Gift;

class GiftModel extends CrudModel {

    const STATUS_NOT_TAKEN = 0;
    const STATUS_TAKEN = 1;

    function getTableName()
    {
        return Gift::getTableName();
    }

    function addObjects(array $inputParams)
    {
        /** @var \Slim\PDO\Database $pdo */
        $pdo = $this->container['db'];

        foreach ($inputParams as $params)
        {
            if (!is_array($params))
                throw (new \Exception());

            //TODO: needed check users exist!

            unset($params[Gift::id]);

            $curTime = (new \DateTime())->getTimestamp();

            $beginOfDay = strtotime("midnight", $curTime);
            $endOfDay   = strtotime("tomorrow", $beginOfDay) - 1;

            $objects = $this->getObjects(
                [
                    Gift::donationTime => [
                        BaseModel::OPERATOR => 'BETWEEN',
                        BaseModel::RIGHT => [$beginOfDay, $endOfDay],
                    ],
                    Gift::giver => $params[Gift::giver]
                ]
            );

            if(!empty($objects))
                throw new \Exception('You can\'t send more than one gift a day' );


            $params[Gift::donationTime] = $curTime;
            $params[Gift::isTaken] = self::STATUS_NOT_TAKEN;

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

            $oldObjectParams = $this->getObject($id);

            if (!$oldObjectParams)
                throw new \Exception($id);


            if($oldObjectParams[Gift::recipient] != $params[Gift::recipient]) {
                throw new \Exception('');
            }

            if($oldObjectParams[Gift::isTaken] == self::STATUS_TAKEN)
                throw new \Exception('Gift is already taken');

            $params = [];
            $params[Gift::isTaken] = self::STATUS_TAKEN;

            $updateStatement = $pdo->update($params)
                ->table($this->getTableName())
                ->where($this->getIdName(), '=', $id);

            $updateStatement->execute();

            $affectedRows = $this->getObject($id);

            $this->affectedObjects[] = $affectedRows;
        }
    }

    function deleteObjects(array $inputParams)
    {
        throw new \Exception('Method not supported');
    }
} 