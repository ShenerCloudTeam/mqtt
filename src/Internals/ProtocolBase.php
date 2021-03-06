<?php

declare(strict_types=1);

namespace ShenerCloud\Mqtt\Internals;

use Psr\Log\LoggerInterface;
use ShenerCloud\Logger;

abstract class ProtocolBase
{
    /**
     * The actual logger object
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Base constructor for all protocol stuff
     * @param LoggerInterface|null $logger
     */
    final public function __construct(LoggerInterface $logger = null)
    {
        if ($logger === null) {
            $logger = new Logger();
        }

        // Insert name of class within the logger
        $this->logger = $logger->withName(str_replace('unreal4u\\MQTT\\', '', \get_class($this)));

        $this->initializeObject();
    }

    /**
     * Should any method have any abnormal default behaviour, we can do so here
     *
     * @return ProtocolBase
     */
    protected function initializeObject(): ProtocolBase
    {
        return $this;
    }
}
