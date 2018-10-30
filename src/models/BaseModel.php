<?php

namespace App\models;

use Slim\PDO\Statement\SelectStatement;

abstract class BaseModel {


    // filter structure attributes
    const LEFT = 'left';
    const OPERATOR = 'operator';
    const RIGHT = 'right';

    /**
     * This is the results of POST/PUT/DELETE request.
     * For DELETE this is and array of deleted ids, for other methods -
     * array of modified objects.
     *
     * @var array
     */
    public $affectedObjects = [];

    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    /**
     * Returns the generated statement with filter.
     *
     * @return SelectStatement
     */
    private function appendFilter($filter, SelectStatement $selectStatement)
    {
        if(!empty($filter)) {
            foreach ($filter as $key => $value) {
                if(is_array($value)) {
                    if(isset($value[self::OPERATOR]) && isset($value[self::RIGHT])) {
                        if($value[self::OPERATOR] == 'BETWEEN') {
                            $selectStatement->whereBetween($key, $value[self::RIGHT]);
                        } else {
                            $selectStatement->where($key, $value[self::OPERATOR], $value[self::RIGHT]);
                        }

                    } else {
                        $selectStatement->whereIn($key, $value);
                    }
                } else {
                    $selectStatement->where($key, '=', $value);
                }
            }
        }

        return $selectStatement;
    }

    function getObjects($filter = NULL)
    {
        $selectStatement = $this->getSelectQuery();

        if(!empty($filter)) {
            $selectStatement = $this->appendFilter($filter, $selectStatement);
        }

        $stmt = $selectStatement->execute();

        if($stmt->rowCount() > 0)
            $objects = $stmt->fetchAll();
        else
            $objects = [];

        return $objects;
    }

    /**
     * This is where the POST request is handled in the model
     *
     * Default behaviour is throwing an exception that the method is not implemented
     *
     * @param array $inputParams
     * @throws \Exception
     */
    function addObjects(array $inputParams)
    {
        throw new \Exception();
    }

    /**
     * This is where the PUT request is handled in the model
     *
     * Default behaviour is throwing an exception that the method is not implemented
     *
     * @param array $inputParams
     * @throws \Exception
     */
    function updateObjects(array $inputParams)
    {
        throw new \Exception();
    }

    /**
     * This is where the DELETE request is handled in the model
     *
     * Default behaviour is throwing an exception that the method is not implemented
     *
     * @param array $inputParams
     * @throws \Exception
     */
    function deleteObjects(array $inputParams)
    {
        throw new \Exception();
    }

    function getObject($objectId)
    {
        $selectStatement = $this->getSelectQuery();
        $selectStatement = $selectStatement
            ->where($this->getIdName(), '=', $objectId);
        $stmt = $selectStatement->execute();

        if($stmt->rowCount() > 0)
            $object = $stmt->fetch();
        else
            $object = null;

        return $object;
    }

    /**
     * Abstract method that returns the table used by the model
     *
     * All basic models that inherit this class should override it and return proper table name
     *
     * @return NULL|string
     * @throws \Exception
     */
    function getTableName()
    {
        throw new \Exception(__METHOD__ . " method not implemented");
    }

    /**
     * Returns the generated query used to handle GET request.
     *
     * @return SelectStatement
     * @throws \Exception
     */
    public final function getSelectQuery($needCalcFoundRows = false)
    {
        /** @var \Slim\PDO\Database $pdo */
        $pdo = $this->container['db'];

        $tableName = $this->getTableName();
        if (!$tableName)
            throw new \Exception();

        $selectStatement = $pdo
            ->select()
            ->from($tableName);

        return $selectStatement;
    }

    /**
     * Controls case when request object id is used by model internally and
     * is not related to specifying which object id to get
     *
     * @return bool
     */
    function hasCustomRequestObjectIdImplementation() { return false; }

    /**
     * Returns the `id` field of the response object.
     *
     * All objects must have this field unique and set.
     *
     * @return string
     */
    public function getIdName()
    {
        return 'id';
    }


} 