<?php

declare(strict_types=1);

namespace ShenerCloud\Mqtt\Application;

use ShenerCloud\Mqtt\Internals\ProtocolBase;
use ShenerCloud\Mqtt\Internals\WritableContent;
use ShenerCloud\Mqtt\Internals\WritableContentInterface;

/**
 * This is an example of a payload class that performs some processing on the data
 *
 * This particular case will prepend a datetime to the message itself. It will json_encode() it into the payload and
 * when retrieving it will set the public property $originalPublishDateTime.
 */
final class EmptyWritableResponse extends ProtocolBase implements WritableContentInterface
{
    use WritableContent;

    const CONTROL_PACKET_VALUE = 0;

    /**
     * Creates the variable header that each method has
     * @return string
     */
    public function createVariableHeader(): string
    {
        return '';
    }

    /**
     * Creates the actual payload to be sent
     * @return string
     */
    public function createPayload(): string
    {
        return '';
    }

    /**
     * Some responses won't expect an answer back, others do in some situations
     * @return bool
     */
    public function shouldExpectAnswer(): bool
    {
        return false;
    }
}
