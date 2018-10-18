<?php

declare(strict_types=1);

namespace ShenerCloud\Mqtt\Protocol;

use ShenerCloud\Mqtt\Internals\ClientInterface;
use ShenerCloud\Mqtt\Internals\ProtocolBase;
use ShenerCloud\Mqtt\Internals\DisconnectCleanup;
use ShenerCloud\Mqtt\Internals\ReadableContentInterface;
use ShenerCloud\Mqtt\Internals\WritableContent;
use ShenerCloud\Mqtt\Internals\WritableContentInterface;

/**
 * The DISCONNECT Packet is the final Control Packet sent from the Client to the Server.
 *
 * It indicates that the Client is disconnecting cleanly.
 */
final class Disconnect extends ProtocolBase implements WritableContentInterface
{
    use WritableContent;

    const CONTROL_PACKET_VALUE = 14;

    public function createVariableHeader(): string
    {
        return '';
    }

    public function createPayload(): string
    {
        return '';
    }

    public function expectAnswer(string $brokerBitStream, ClientInterface $client): ReadableContentInterface
    {
        return new DisconnectCleanup($this->logger);
    }

    /**
     * A disconnect should never expect an answer back
     * @return bool
     */
    public function shouldExpectAnswer(): bool
    {
        return false;
    }
}
