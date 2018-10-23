<?php

namespace App\models;

abstract class BaseModel {

    /**
     * This is the results of POST/PUT/DELETE request.
     * For DELETE this is and array of deleted ids, for other methods -
     * array of modified objects.
     *
     * @var array
     */
    public $affectedObjects = [];

    function getObjects($filter = NULL)
    {
        $result = [];
        return $result;
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

    function getTableName()
    {
        throw new \Exception(__METHOD__ . " method not implemented");
    }
    //function getTableName() { return $this->orm()->getTableName(); }

} 