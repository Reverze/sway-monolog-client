<?php

namespace Sway\MonologClient\Exception\Client;

class LoggerException extends \Exception
{
    /**
     * Throws an exception when logger was not found by channel name
     * @param string $channelName
     * @return \Sway\MonologClient\Exception\Client\LoggerException
     */
    public static function loggerNotFound(string $channelName) : LoggerException
    {
        return (new LoggerException(sprintf("Logger not found by channel name '%s'", $channelName)));
    }
}


?>