<?php

declare(strict_types=1);

namespace ShenerCloud\Mqtt\Protocol;

use ShenerCloud\Mqtt\Internals\PacketIdentifierFunctionality;
use ShenerCloud\Mqtt\Internals\ProtocolBase;
use ShenerCloud\Mqtt\Internals\TopicFilterFunctionality;
use ShenerCloud\Mqtt\Internals\WritableContent;
use ShenerCloud\Mqtt\Internals\WritableContentInterface;

/**
 * An UNSUBSCRIBE Packet is sent by the Client to the Server, to unsubscribe from topics.
 */
final class Unsubscribe extends ProtocolBase implements WritableContentInterface
{
    use WritableContent, PacketIdentifierFunctionality, TopicFilterFunctionality;

    const CONTROL_PACKET_VALUE = 10;

    protected function initializeObject(): ProtocolBase
    {
        $this->topics = new \SplQueue();
        return parent::initializeObject();
    }

    /**
     * @return string
     * @throws \ShenerCloud\Mqtt\Exceptions\MustContainTopic
     * @throws \OutOfRangeException
     */
    public function createVariableHeader(): string
    {
        // Unsubscribe must always send a 2 flag
        $this->specialFlags = 2;

        return $this->getPacketIdentifierBinaryRepresentation();
    }

    /**
     * @return string
     * @throws \ShenerCloud\Mqtt\Exceptions\MustContainTopic
     * @throws \OutOfRangeException
     */
    public function createPayload(): string
    {
        $output = '';
        foreach ($this->getTopics() as $topic) {
            // chr on QoS level is safe because it will create an 8-bit flag where the first 6 are only 0's
            $output .= $this->createUTF8String($topic->getTopicFilter());
        }

        return $output;
    }

    /**
     * When the Server receives a SUBSCRIBE Packet from a Client, the Server MUST respond with a SUBACK Packet
     *
     * This can however not be in the same order, as we may be able to receive PUBLISH packets before getting a SUBACK
     * back
     *
     * @see http://docs.oasis-open.org/mqtt/mqtt/v3.1.1/os/mqtt-v3.1.1-os.html#_Toc398718134 (MQTT-3.8.4-1)
     * @return bool
     */
    public function shouldExpectAnswer(): bool
    {
        return true;
    }
}
