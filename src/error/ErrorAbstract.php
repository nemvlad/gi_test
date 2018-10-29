<?php

namespace Error;

abstract class ErrorAbstract
{
    const ERROR_OK = 0;
    const INT_INTERNAL_ERROR = 1;

    const SVC_METHOD_NOT_SUPPORTED = 2;
    const SVC_INVALID_ITEM_HANDLER_SPECIFIED = 3;

    /**
     * Returns error message by error code
     *
     * @param $errorCode
     *
     * @return mixed|null
     */
    public static function _getErrorMessage($errorCode)
    {
        if (!array_key_exists($errorCode, static::getErrorMessages()))
            return null;

        return static::getErrorMessages()[$errorCode];
    }

    /**
     * Returns a mapping of errorCode => errorMessage.
     *
     * Uses lazy initialization.
     *
     * @return array|null
     */
    protected static function getErrorMessages()
    {
        static $errorMessages = null;

        if (!$errorMessages) {
            $errorMessages = [
                self::INT_INTERNAL_ERROR => "Internal error",
                self::SVC_METHOD_NOT_SUPPORTED => "Method not supported",
                self::SVC_INVALID_ITEM_HANDLER_SPECIFIED => "Invalid item handler specified",
            ];
        }

        return $errorMessages;
    }
}