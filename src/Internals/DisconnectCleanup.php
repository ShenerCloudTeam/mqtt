<?php

declare(strict_types=1);

namespace ShenerCloud\Mqtt\Internals;

/**
 * Performs some cleanup on the socket after disconnecting, this class is NOT a part of the MQTT protocol
 */
final class DisconnectCleanup extends ProtocolBase implements ReadableContentInterface
{
    use ReadableContent;

    /**
     * @inheritdoc
     */
    public function performSpecialActions(ClientInterface $client, WritableContentInterface $originalRequest): bool
    {
        $successFullyClosed = $client->shutdownConnection();
        $this->logger->info('Sent shutdown signal to socket', ['successFullyClosed' => $successFullyClosed]);
        $client->setConnected(false);
        return true;
    }

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
