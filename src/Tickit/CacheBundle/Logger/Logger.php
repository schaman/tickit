<?php

namespace Tickit\CacheBundle\Logger;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Logger class for the cache engines and options classes. This implements the
 * PSR-3 LoggerAwareInterface to symbolise that it should accept a PSR-3 ready logger, so it
 * basically serves as a wrapper for such an instance. This allows us to encapsulate all of our
 * logic for logging inside the cache
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */

class Logger implements LoggerAwareInterface, LoggerInterface
{
    /**
     * Stores an instance to the PSR-3 logger instance
     *
     * @var
     */
    protected $logger;

    /**
     * Class constructor, optionally allows for constructor injection of the PSR-3 logger instance
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * Sets a logger instance on the object
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $message The log message
     * @param array  $context An array of context options
     *
     * @return void
     */
    public function emergency($message, array $context = array())
    {
        $message = $this->prepareMessage($message, __METHOD__, $context);
        $this->logger->emergency($message, $context);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $message The log message
     * @param array  $context An array of context options
     *
     * @return void
     */
    public function alert($message, array $context = array())
    {
        $message = $this->prepareMessage($message, __METHOD__, $context);
        $this->logger->alert($message, $context);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $message The log message
     * @param array  $context An array of context options
     *
     * @return void
     */
    public function critical($message, array $context = array())
    {
        $message = $this->prepareMessage($message, __METHOD__, $context);
        $this->logger->critical($message, $context);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $message The log message
     * @param array  $context An array of context options
     *
     * @return void
     */
    public function error($message, array $context = array())
    {
        $message = $this->prepareMessage($message, __METHOD__, $context);
        $this->logger->error($message, $context);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $message The log message
     * @param array  $context An array of context options
     *
     * @return void
     */
    public function warning($message, array $context = array())
    {
        $message = $this->prepareMessage($message, __METHOD__, $context);
        $this->logger->warning($message, $context);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $message The log message
     * @param array  $context An array of context options
     *
     * @return void
     */
    public function notice($message, array $context = array())
    {
        $message = $this->prepareMessage($message, __METHOD__, $context);
        $this->logger->notice($message, $context);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $message The log message
     * @param array  $context An array of context options
     *
     * @return void
     */
    public function info($message, array $context = array())
    {
        $message = $this->prepareMessage($message, __METHOD__, $context);
        $this->logger->info($message, $context);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $message The log message
     * @param array  $context An array of context options
     *
     * @return void
     */
    public function debug($message, array $context = array())
    {
        $message = $this->prepareMessage($message, __METHOD__, $context);
        $this->logger->debug($message, $context);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $message The log message
     * @param array  $context An array of context options
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        $message = $this->prepareMessage($message, __METHOD__, $context);
        $this->logger->log($message, $context);
    }

    /**
     * Prepares a message for logging
     *
     * @param string $message The raw message to prepare
     * @param string $level   The severity level of the log message
     * @param array  $context * The context of the message, currently supports:<br/>
     *                          - "class" The cache
     *
     * @return string
     */
    protected function prepareMessage($message, $level = 'info', array &$context = array())
    {
        $engine = 'Cache';
        if (isset($context['engine'])) {
            $engine = $context['engine'];
            unset($context['engine']);
        }

        $message = sprintf('[%s] %s : %s', strtoupper($level), $engine, $message);

        return $message;
    }
}