<?php

declare(strict_types=1);

namespace ShenerCloud\Mqtt\Application;

use ShenerCloud\Mqtt\Internals\ClientInterface;
use ShenerCloud\Mqtt\Internals\ProtocolBase;
use ShenerCloud\Mqtt\Internals\ReadableContent;
use ShenerCloud\Mqtt\Internals\ReadableContentInterface;

/**
 * This is an example of a payload class that performs some processing on the data
 *
 * This particular case will prepend a datetime to the message itself. It will json_encode() it into the payload and
 * when retrieving it will set the public property $originalPublishDateTime.
 */
final class EmptyReadableResponse extends ProtocolBase implements ReadableContentInterface
{
    use ReadableContent;

    /**
     * @inheritdoc
     */
    public function getOriginControlPacket(): int
    {
        return 0;
    }

    public function fillObject(string $rawMQTTHeaders, ClientInterface $client): ReadableContentInterface
    {
        return $this;
    }
}
