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

    const PARAM_ITEM_HANDLER = 'itemHandler';
    const PARAM_GET_DATA = 'getData';
    const PARAM_POST_DATA = 'postData';

    const Get_FilterContent = 'filter';

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

    /** @var array */
    protected $params = false;

    protected $_itemHandler = null;

    /** @var array|null Raw structure of filter expression that is used to filter request response */
    public $filter = null;

    protected $container;

    public function __construct( $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->method = $request->getMethod();
        $this->model = $this->constructModel();

        if(isset($args[self::PARAM_ITEM_HANDLER]))
            $this->_itemHandler = $args[self::PARAM_ITEM_HANDLER];

        $this->params[self::PARAM_GET_DATA] = $request->getQueryParams();
        $this->params[self::PARAM_POST_DATA] = $request->getParsedBody();

        $this->setFilter();

        switch($this->method)
        {
            case self::METHOD_GET:
                try
                {
                    $this->processGetRequest();
                }
                catch (\Exception $ex)
                {
                    throw (new \Exception($ex->getMessage()));
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

        return $response->withJson($this->response);
    }

    protected function processGetRequest() {


        if ($this->_itemHandler && !$this->model->hasCustomRequestObjectIdImplementation())
        {
            if (is_scalar($this->_itemHandler))
            {
                $objectId = $this->_itemHandler;

                $object = $this->model->getObject($objectId);
                if (!$object)
                    throw new \Exception(print_r($objectId));

                $response[] = $object;
            }
            else
                throw new \Exception(ErrorAbstract::SVC_INVALID_ITEM_HANDLER_SPECIFIED);

        }
        else
        {
            $response = $this->model->getObjects($this->filter);
        }

        $this->response = $response;
    }

    protected function processPostRequest() {
        $inputParams = $this->getVerifiedInput();
        $this->model->addObjects($inputParams);
        $this->response = $this->model->affectedObjects;
    }

    protected function processPutRequest() {
        $inputParams = $this->getVerifiedInput();
        $this->model->updateObjects($inputParams);
        $this->response = $this->model->affectedObjects;
    }

    protected function processDeleteRequest() {
        $inputParams = $this->getVerifiedInput();
        $this->model->deleteObjects($inputParams);
        $this->response = $this->model->affectedObjects;
    }

    protected function getVerifiedInput()
    {
        $inputParams = $this->params['postData']['content'];

        if (!is_array($inputParams))
        {
            throw (new \Exception());
        }

        foreach ($inputParams as &$params)
        {
            if (!is_array($params))
                throw (new \Exception());

        }
        unset($params);

        return $inputParams;
    }

    /**
     * Extract and validate filter.
     * Filter can be passed either in POST or GET parameters, POST is preferred
     *
     * @throws \Exception
     */
    protected function setFilter()
    {
        $getData = $this->params[self::PARAM_GET_DATA];
        $postData = $this->params[self::PARAM_POST_DATA];
        if (isset($getData[[self::Get_FilterContent]]) &&
            $filter = $getData[[self::Get_FilterContent]])
        {
            if (!is_array($filter))
                throw new \Exception(self::Get_FilterContent);
            $this->filter = $filter;
        }
        else if (isset($postData[[self::Get_FilterContent]]) && $filter = $postData[[self::Get_FilterContent]])
        {
            if (is_array($filter))
                throw new \Exception(self::Get_FilterContent);
            $this->filter = json_decode($filter, true);
        }
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
        return new $modelName($this->container);
    }
} 