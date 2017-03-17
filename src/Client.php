<?php

namespace Sway\MonologClient;


class Client
{
    /**
     * Root directory for stream handler
     * @var string 
     */
    private $streamHandlerRootPath = null;
    
    /**
     * If this option is set as true, it will try to create directory for stream handler
     * @var boolean
     */
    private $streamhandlerAutoMkdir = false;
    
    /**
     * Loggers container
     * @var \Sway\MonologClient\Logger[]
     */
    private $loggers = array();
    
    public function __construct(array $options, array $loggers)
    {
        $this->initializeOptions($options);
        $this->initializeLoggers($loggers);
    }
    
    /**
     * Initialize client options
     * @param array $options
     * @throws \Sway\MonologClient\Exception\Client\StreamHandlerException
     */
    protected function initializeOptions(array $options)
    {
        if (array_key_exists('stream.mkdir.auto', $options)){
            $this->streamhandlerAutoMkdir = (bool) $options['stream.mkdir.auto'];
        }
        
        if (array_key_exists('stream.root', $options)){
            if (!is_dir($options['stream.root'])){
                if (!$this->streamhandlerAutoMkdir){
                    throw Exception\Client\StreamHandlerException::rootDirectoryNotExists($options['stream.root']);
                }
                else{
                    mkdir($options['stream.root']);
                }
            }
            
            $this->streamHandlerRootPath = $options['stream.root'];    
        }
    }
    
    protected function initializeLoggers(array $loggers)
    {
        foreach ($loggers as $loggerParameters){
            /**
             * Channel name is required. If is not specified, throws an exception
             */
            $channelName = $loggerParameters['channel'] ?? null;
            
            if (empty($channelName) || !strlen($channelName)){
                throw Exception\Client\LogChannelException::emptyLogChannelName();
            }
            
            /**
             * At least one handler is required. If there is none specified, throws an exception
             */
            $handlers = $loggerParameters['handlers'] ?? null;
            
            if (empty($handlers) || !sizeof($handlers)){
                throw Exception\Client\HandlerException::notDefinedHandlers($channelName);
            }
            
            /**
             * Stores prepared handlers to use
             */
            $preparedHandlers = array();
            
            /**
             * Lookup for each handlers
             */
            foreach ($handlers as $handlerType => $handlerParameters){
                /**
                 * If is stream handler, prefixes path with root directory path
                 */
                if (strtolower($handlerType) === 'stream'){
                    $parameters = null;
                    
                    /** If only one file is specified */
                    if (isset($handlerParameters['container']) && isset($handlerParameters['type'])){
                        $parameters = array();
                        $parameters['container'] = sprintf("%s/%s", $this->streamHandlerRootPath, $handlerParameters['container']);
                        $parameters['type'] = $handlerParameters['type'];
                    }
                    
                    /** If array with files are given */
                    if (is_array($handlerParameters) && !isset($handlerParameters['container']) && !isset($handlerParameters['type'])){
                        /** Creates empty array to store list of files (absolute path) */
                        $parameters = array();
                        
                        foreach ($handlerParameters as $fileRel){
                            $params = array();
                            
                            $params['container'] = sprintf("%s/%s", $this->streamHandlerRootPath, $fileRel['container']);
                            $params['type'] = $fileRel['type'];
                            array_push($parameters, $params);    
                        }
                    }
                    
                    $preparedHandlers['stream'] = $parameters;
                }
                
            }
            
            $logger = new Logger($channelName, $preparedHandlers);
            array_push($this->loggers, $logger);
        }
    }
    
    /**
     * Gets logger by channel name
     * @param string $channelName
     * @return \Sway\MonologClient\Logger
     * @throws \Sway\MonologClient\Exception\Client\LoggerException
     */
    public function getLogger(string $channelName) : Logger
    {
        foreach ($this->loggers as $logger){
            if ($logger->getChannelName() === $channelName){
                return $logger;
            }
        }
        
        throw Exception\Client\LoggerException::loggerNotFound($channelName);
    }
    
    /**
     * Adds a log at DEBUG level
     * @param string $channelName
     * @param string $message
     * @param array $context
     */
    public function debug(string $channelName, string $message, array $context = array())
    {
        $this->getLogger($channelName)->debug($message, $context);
    }
    
    /**
     * Adds a log at INFO level
     * @param string $channelName
     * @param string $message
     * @param array $context
     */
    public function info(string $channelName, string $message, array $context = array())
    {
        $this->getLogger($channelName)->info($message, $context);
    }
    
    /**
     * Adds a log at NOTICE level
     * @param string $channelName
     * @param string $message
     * @param array $context
     */
    public function notice(string $channelName, string $message, array $context = array())
    {
        $this->getLogger($channelName)->notice($message, $context);
    }
    
    /**
     * Adds a log at WARNING level
     * @param string $channelName
     * @param string $message
     * @param array $context
     */
    public function warning(string $channelName, string $message, array $context = array())
    {
        $this->getLogger($channelName)->warning($message, $context);
    }
    
    /**
     * Adds a log at ERROR level
     * @param string $channelName
     * @param string $message
     * @param array $context
     */
    public function error(string $channelName, string $message, array $context = array())
    {
        $this->getLogger($channelName)->error($message, $context);
    }
    
    /**
     * Adds a log at CRITICAL level
     * @param string $channelName
     * @param string $message
     * @param array $context
     */
    public function critical(string $channelName, string $message, array $context = array())
    {
        $this->getLogger($channelName)->critical($message, $context);
    }
    
    /**
     * Adds a log at ALERT level
     * @param string $channelName
     * @param string $message
     * @param array $context
     */
    public function alert(string $channelName, string $message, array $context = array())
    {
        $this->getLogger($channelName)->alert($message, $context);
    }
    
    /**
     * Adds a log at EMERGENCY level
     * @param string $channelName
     * @param string $message
     * @param array $context
     */
    public function emergency(string $channelName, string $message, array $context = array())
    {
        $this->getLogger($channelName)->alert($message, $context);
    }
}

?>