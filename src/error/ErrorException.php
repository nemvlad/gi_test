<?php

namespace Error;

class Error_Exception extends \Exception
{
    /** @var string Exception message */
    protected $_message = null;
    /** @var array Exception parameters */
    private $_params = [];
    /** @var string Extended exception message */
    protected $_extendedMessage = null;

    function __construct($code = null, $params = null)
    {
        $params = func_get_args();
        array_shift($params);
        parent::__construct(null, $code, null);
        $this->setParams($params);
    }

    /**
     * Sets exception message.
     * Exceptions parameters are cleared.
     *
     * @param $message
     * @return Error_Exception
     */
    function setMessage($message)
    {
        $this->_message = $message;
        $this->_params = [];
        return $this;
    }

    /**
     * Sets extended message.
     * Method is variadic.
     *
     * @param null $message
     *
     * @return $this
     */
    function setExtendedMessage($message = null/*, ... */)
    {
        $args = func_get_args();
        array_shift($args);
        if (!empty($args))
            $message = vsprintf($message, $args);

        $this->_extendedMessage = $message;
        return $this;
    }

    function getExtendedMessage() { return $this->_extendedMessage; }

    function getFullMessage() { return "{$this->getMessage()} . {$this->getExtendedMessage()}"; }

    /**
     * Set exception parameters.
     * Also regenerates internal message.
     *
     * @param $params
     * @return Error_Exception
     */
    function setParams($params)
    {
        if (is_array($params))
            $this->_params = $params;
        else
            $this->_params = func_get_args();

        foreach ($this->_params as &$param)
            if (is_array($param))
            {
                $message = $param[0];
                array_shift($param);
                if (!empty($param))
                    $message = vsprintf($message, $param);
                $param = $message;
            }

        unset($param);

        $this->updateMessage();

        return $this;
    }

    /**
     * Set exception code
     *
     * @param $code
     * @return Error_Exception
     */
    function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Regenerate internal string message
     *
     * @return $this
     */
    function updateMessage()
    {
        if ($this->_message !== null)
        {
            $this->message = $this->_message;
            if (!empty($this->_params))
                $this->message = vsprintf($this->message, $this->_params);
        }
        else
            $this->message = Error_Handler::getInstance()->getErrorMessage($this->code, "", $this->_params);

        return $this;
    }
}
