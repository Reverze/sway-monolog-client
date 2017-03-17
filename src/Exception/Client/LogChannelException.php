<?php

namespace Sway\MonologClient\Exception\Client;


class LogChannelException extends \Exception
{
    /**
     * Throws an exception when channel name is empty or is null
     * @return \Sway\MonologClient\Exception\Client\LogChannelException
     */
    public static function emptyLogChannelName() : LogChannelException
    {
        return (new LogChannelException(sprintf("Log channel name is empty!")));
    }
}


?>