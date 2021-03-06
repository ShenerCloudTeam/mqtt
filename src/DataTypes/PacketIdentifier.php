<?php

declare(strict_types=1);

namespace ShenerCloud\Mqtt\DataTypes;

use ShenerCloud\Mqtt\Utilities;

/**
 * This Value Object will always contain a valid Packet Identifier
 */
final class PacketIdentifier
{
    /**
     * This field indicates the level of assurance for delivery of an Application Message. Can be 0, 1 or 2
     *
     * 0: At most once delivery (default)
     * 1: At least once delivery
     * 2: Exactly once delivery
     *
     * @var int
     */
    private $packetIdentifier;

    /**
     * QoSLevel constructor.
     *
     * @param int $packetIdentifier
     * @throws \OutOfRangeException
     */
    public function __construct(int $packetIdentifier)
    {
        if ($packetIdentifier > 65535 || $packetIdentifier < 1) {
            throw new \OutOfRangeException(sprintf(
                'The provided packet identifier is invalid. Valid values are 1-65535 (Provided: %d)',
                $packetIdentifier
            ));
        }

        $this->packetIdentifier = $packetIdentifier;
    }

    /**
     * Gets the current packet identifier value
     *
     * @return int
     */
    public function getPacketIdentifierValue(): int
    {
        return $this->packetIdentifier;
    }

    public function __toString(): string
    {
        // Save to ignore this inspection here because this exception is already handled by the VO itself
        /** @noinspection MagicMethodsValidityInspection */
        return Utilities::convertNumberToBinaryString($this->packetIdentifier);
    }
}
