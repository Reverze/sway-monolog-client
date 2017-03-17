<?php

namespace Sway\MonologClient;

use Monolog\Handler\StreamHandler;


class Logger
{
    /**
     * Logger channel name
     * @var string
     */
    private $channelName = null;
    
    /**
     * Handlers configuration
     * @var array 
     */
    private $handlers = null;
    
    
    /**
     * Monolog Logger instance
     * @var \Monolog\Logger
     */
    private $logger = null;
    
    public function __construct(string $channelName, array $handlers)
    {
        if (empty($this->channelName)){
            $this->channelName = $channelName;
        }
        
        if (empty($this->handlers)){
            $this->handlers = $handlers;
        }
        
        $this->initialize();
    }
    
    /**
     * Initializes Logger instance
     */
    private function initialize()
    {
        /**
         * Creates an new instance of Logger
         */
        $this->logger = new \Monolog\Logger($this->channelName);
        
        if (array_key_exists('stream', $this->handlers)){
            $streamHandlerParameters = $this->handlers['stream'];
            
            if (isset($streamHandlerParameters['container']) && isset($streamHandlerParameters['type'])){
                $this->logger->pushHandler(new StreamHandler($streamHandlerParameters['container'], 
                        $this->getLoggerTypeCode($streamHandlerParameters['type']), true)
                );
            }
            
            if (is_array($streamHandlerParameters) && !isset($streamHandlerParameters['container']) && !isset($streamHandlerParameters['type'])){
                foreach ($streamHandlerParameters as $stream){
                    $this->logger->pushHandler(new StreamHandler($stream['container'],
                            $this->getLoggerTypeCode($stream['type']), true)
                    );  
                }
            }
        } 
        
        
        
    }
    
    /**
     * Adds a log record at DEBUG level
     * @param string $message
     * @param array $context
     */
    public function debug(string $message, array $context = array())
    {
        $this->logger->debug($message, $context);
    }
    
    /**
     * Adds a log record at INFO level
     * @param string $message
     * @param array $context
     */
    public function info(string $message, array $context = array())
    {
        $this->logger->info($message, $context);
    }
    
    /**
     * Adds a log record at NOTICE level
     * @param string $message
     * @param array $context
     */
    public function notice(string $message, array $context = array())
    {
        $this->logger->notice($message, $context);
    }
    
    /**
     * Adds a log record at WARNING level
     * @param string $message
     * @param array $context
     */
    public function warning(string $message, array $context = array())
    {
        $this->logger->warning($message, $context);
    }
    
    /**
     * Adds a log record at ERROR level
     * @param string $message
     * @param array $context
     */
    public function error(string $message, array $context = array())
    {
        $this->logger->error($message, $context);
    }
    
    /**
     * Adds a log record at CRITICAL level
     * @param string $message
     * @param array $context
     */
    public function critical(string $message, array $context = array())
    {
        $this->logger->critical($message, $context);
    }
    
    /**
     * Adds a log record at ALERT level
     */
    public function alert(string $message, array $context = array())
    {
        $this->logger->alert($message, $context);
    }
    
    /**
     * Adds a log record at EMERGENCY level
     * @param string $message
     * @param array $context
     */
    public function emergency(string $message, array $context = array())
    {
        $this->logger->emergency($message, $context);
    }
    
    private function getLoggerTypeCode(string $loggerType) : int
    {
        switch (strtolower($loggerType)){
            case 'debug':
                return \Monolog\Logger::DEBUG;
                break;
            case 'info':
                return \Monolog\Logger::INFO;
                break;
            case 'notice':
                return \Monolog\Logger::NOTICE;
                break;
            case 'warning':
                return \Monolog\Logger::WARNING;
                break;
            case 'critical':
                return \Monolog\Logger::CRITICAL;
                break;
            case 'alert':
                return \Monolog\Logger::ALERT;
                break;
            case 'emergency':
                return \Monolog\Logger::EMERGENCY;
                break;
            case 'error':
                return \Monolog\Logger::ERROR;
                break;
            default:
                return \Monolog\Logger::WARNING;
        }
    }
    
    /**
     * Gets channel name
     * @return string
     */
    public function getChannelName() : string
    {
        return $this->channelName;
    }
}


?>