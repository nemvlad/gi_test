<?php

namespace App\controllers;

use Error\ErrorAbstract;
use Slim\Http\Request;
use Slim\Http\Response;
use App\models\BaseModel;

abstract class BaseController {

    // access method names
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /** @var string Method used when calling service. GET, POST, PUT or DELETE */
    protected $method = null;

    /** @var string[] List of access methods that current instance of service is capable to handle. */
    public $supportedMethods = array(
        self::METHOD_POST,
        self::METHOD_PUT,
        self::METHOD_GET,
        self::METHOD_DELETE,
    );

    /** @var array Contains actual data - response objects */
    protected $response = array();

    /** @var BaseModel */
    private $model = NULL;

    public function __invoke(Request $request, Response $response, $next)
    {
        $this->method = $request->getMethod();
        $this->model = $this->constructModel();

        $getData = $request->getQueryParams();
        $postData = $request->getParsedBody();

        switch($this->method)
        {
            case self::METHOD_GET:
                try
                {
                    $this->processGetRequest();
                }
                catch (\Exception $ex)
                {
                    throw (new \Exception());
                }
                break;
            case self::METHOD_POST:
                try
                {
                    $this->processPostRequest();
                }
                catch (\Exception $ex)
                {
                    throw (new \Exception());
                }
                break;
            case self::METHOD_PUT:
                try
                {
                    $this->processPutRequest();
                }
                catch (\Exception $ex)
                {
                    throw (new \Exception());
                }
                break;
            case self::METHOD_DELETE:
                try
                {
                    $this->processDeleteRequest();
                }
                catch (\Exception $ex)
                {
                    throw (new \Exception());
                }
                break;
            default:
                throw new \Exception(ErrorAbstract::_getErrorMessage(ErrorAbstract::SVC_METHOD_NOT_SUPPORTED));
        }

    }

    protected function processGetRequest() {
        $response = $this->model->getObjects();
    }

    protected function processPostRequest() {
        $this->model->addObjects([]);
        $response = $this->model->affectedObjects;
    }

    protected function processPutRequest() {
        $this->model->updateObjects([]);
        $response = $this->model->affectedObjects;
    }

    protected function processDeleteRequest() {
        $this->model->deleteObjects([]);
        $response = $this->model->affectedObjects;
    }

    /**
     * Model object getter
     *
     * @return BaseModel
     */
    static function getModelName() { throw new \Exception(); }

    /**
     * Constructs model instance from a model name. Can be overriden to implement more complex logic.
     *
     * @return  BaseModel
     * @throws \Exception
     */
    protected function constructModel()
    {
        $modelName = static::getModelName();
        return new $modelName;
    }
} 