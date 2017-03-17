<?php

namespace Sway\MonologClient\Exception\Client;

class HandlerException extends \Exception
{
    /**
     * Throws an exception when none handlers are defined for logger instance
     * @param string $channelName
     * @return \Sway\MonologClient\Exception\Client\HandlerException
     */
    public static function notDefinedHandlers(string $channelName) : HandlerException
    {
        return (new HandlerException(sprintf("There is none defined handler for logger '%s'", $channelName)));
    }
}


?>