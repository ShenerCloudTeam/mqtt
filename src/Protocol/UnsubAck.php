<?php

declare(strict_types=1);

namespace ShenerCloud\Mqtt\Protocol;

use ShenerCloud\Mqtt\Internals\ClientInterface;
use ShenerCloud\Mqtt\Internals\PacketIdentifierFunctionality;
use ShenerCloud\Mqtt\Internals\ProtocolBase;
use ShenerCloud\Mqtt\Internals\ReadableContent;
use ShenerCloud\Mqtt\Internals\ReadableContentInterface;
use ShenerCloud\Mqtt\Internals\WritableContentInterface;

/**
 * The UNSUBACK Packet is sent by the Server to the Client to confirm receipt of an UNSUBSCRIBE Packet.
 */
final class UnsubAck extends ProtocolBase implements ReadableContentInterface
{
    use ReadableContent, PacketIdentifierFunctionality;

    const CONTROL_PACKET_VALUE = 11;

    /**
     * @param string $rawMQTTHeaders
     * @param ClientInterface $client
     * @return ReadableContentInterface
     * @throws \OutOfRangeException
     */
    public function fillObject(string $rawMQTTHeaders, ClientInterface $client): ReadableContentInterface
    {
        // Read the rest of the request out should only 1 byte have come in
        if (\strlen($rawMQTTHeaders) === 1) {
            $rawMQTTHeaders .= $client->readBrokerData(3);
        }

        $this->setPacketIdentifierFromRawHeaders($rawMQTTHeaders);
        return $this;
    }

    /**
     * @inheritdoc
     * @throws \LogicException
     */
    public function performSpecialActions(ClientInterface $client, WritableContentInterface $originalRequest): bool
    {
        $this->controlPacketIdentifiers($originalRequest);
        $client->updateLastCommunication();
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getOriginControlPacket(): int
    {
        return Unsubscribe::getControlPacketValue();
    }
}
