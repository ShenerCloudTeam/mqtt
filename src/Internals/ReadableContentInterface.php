<?php

declare(strict_types=1);

namespace ShenerCloud\Mqtt\Internals;

use Psr\Log\LoggerInterface;
use ShenerCloud\Mqtt\Exceptions\NonMatchingPacketIdentifiers;

interface ReadableContentInterface
{
    public function __construct(LoggerInterface $logger = null);

    /**
     * Populates the object and performs some basic checks on everything
     *
     * @param string $rawMQTTHeaders
     * @param ClientInterface $client
     * @return bool
     */
    public function instantiateObject(string $rawMQTTHeaders, ClientInterface $client): bool;

    /**
     * Will perform sanity checks and fill in the Readable object with data
     * @param string $rawMQTTHeaders
     * @param ClientInterface $client
     * @return ReadableContentInterface
     */
    public function fillObject(string $rawMQTTHeaders, ClientInterface $client): ReadableContentInterface;

    /**
     * Some operations require setting some things in the client or perform some checks, this hook will allow just that
     *
     * @param ClientInterface $client
     * @param WritableContentInterface $originalRequest Will be used to validate stuff such as packetIdentifier
     *
     * @return bool
     * @throws NonMatchingPacketIdentifiers
     */
    public function performSpecialActions(ClientInterface $client, WritableContentInterface $originalRequest): bool;

    /**
     * Returns the origin control packet that will issue this type of object
     *
     * This strange construct is better explained with an example:
     * a ConnAck will always come from a Connect packet.
     * a SubAck will always come from a Subscribe packet.
     * a PingResp will always come from a PingReq packet.
     *
     * However, given the nature of the MQTT protocol, not every package will arrive in the same order that they were
     * issued. This will happen most often with retained messages, QoS levels >0 and Subscribe packets: the SubAck
     * packet will arrive just in between the traffic generated by a QoS level 2 packet, disrupting the flow.
     *
     * This is why each ReadableContent must return the origin packet identifier that may issue this type of packet, in
     * order to identify where it might have come from.
     *
     * @return int
     */
    public function getOriginControlPacket(): int;
}
