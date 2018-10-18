<?php

declare(strict_types=1);

namespace ShenerCloud\Mqtt\Protocol;

use ShenerCloud\Mqtt\Internals\ProtocolBase;
use ShenerCloud\Mqtt\Internals\WritableContent;
use ShenerCloud\Mqtt\Internals\WritableContentInterface;

/**
 * The PINGREQ Packet is sent from a Client to the Server. It can be used to:
 *
 * - Indicate to the Server that the Client is alive in the absence of any other Control Packets being sent from the
 *   Client to the Server.
 * - Request that the Server responds to confirm that it is alive.
 * - Exercise the network to indicate that the Network Connection is active.
 */
final class PingReq extends ProtocolBase implements WritableContentInterface
{
    use WritableContent;

    const CONTROL_PACKET_VALUE = 12;

    public function createVariableHeader(): string
    {
        return '';
    }

    public function createPayload(): string
    {
        return '';
    }

    public function shouldExpectAnswer(): bool
    {
        return true;
    }
}
