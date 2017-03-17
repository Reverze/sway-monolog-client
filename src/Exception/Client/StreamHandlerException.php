<?php

namespace Sway\MonologClient\Exception\Client;

class StreamHandlerException extends \Exception
{
    /**
     * Throws an exception when root directory is not exists
     * @param string $path
     * @return \Sway\MonologClient\Exception\Client\StreamHandlerException
     */
    public static function rootDirectoryNotExists(string $path) : StreamHandlerException
    {
        return (new StreamHandlerException(sprintf("Directory not exists on path '%s'", $path)));
    }
    
    
    
}


?>