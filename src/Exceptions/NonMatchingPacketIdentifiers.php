<?php

declare(strict_types=1);

namespace ShenerCloud\Mqtt\Exceptions;

use ShenerCloud\Mqtt\DataTypes\PacketIdentifier;

class NonMatchingPacketIdentifiers extends \LogicException
{
    /**
     * @var PacketIdentifier
     */
    private $originPacketIdentifier;

    /**
     * @var PacketIdentifier
     */
    private $returnedPacketIdentifier;

    public function setOriginPacketIdentifier(PacketIdentifier $originPacketIdentifier): self
    {
        $this->originPacketIdentifier = $originPacketIdentifier;
        return $this;
    }

    public function setReturnedPacketIdentifier(PacketIdentifier $returnedPacketIdentifier): self
    {
        $this->returnedPacketIdentifier = $returnedPacketIdentifier;
        return $this;
    }

    public function getOriginPacketIdentifierValue(): int
    {
        return $this->originPacketIdentifier->getPacketIdentifierValue();
    }

    public function getReturnedPacketIdentifierValue(): int
    {
        return $this->returnedPacketIdentifier->getPacketIdentifierValue();
    }
}
