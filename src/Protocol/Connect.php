<?php

declare(strict_types=1);

namespace ShenerCloud\Mqtt\Protocol;

use ShenerCloud\Mqtt\Exceptions\Connect\IdentifierRejected;
use ShenerCloud\Mqtt\Exceptions\Connect\NoConnectionParametersDefined;
use ShenerCloud\Mqtt\Exceptions\MustProvideUsername;
use ShenerCloud\Mqtt\Internals\ClientInterface;
use ShenerCloud\Mqtt\Internals\EventManager;
use ShenerCloud\Mqtt\Internals\ProtocolBase;
use ShenerCloud\Mqtt\Internals\ReadableContentInterface;
use ShenerCloud\Mqtt\Internals\WritableContent;
use ShenerCloud\Mqtt\Internals\WritableContentInterface;
use ShenerCloud\Mqtt\Protocol\Connect\Parameters;
use ShenerCloud\Mqtt\Utilities;

/**
 * After a Network Connection is established by a Client to a Server, the first Packet sent from the Client to the
 * Server MUST be a CONNECT Packet
 */
final class Connect extends ProtocolBase implements WritableContentInterface
{
    use WritableContent;

    const CONTROL_PACKET_VALUE = 1;

    /**
     * @var Parameters
     */
    private $connectionParameters;

    /**
     * Saves the mandatory connection parameters onto this object
     * @param Parameters $connectionParameters
     *
     * @return Connect
     */
    public function setConnectionParameters(Parameters $connectionParameters): self
    {
        $this->connectionParameters = $connectionParameters;
        return $this;
    }

    /**
     * Get the connection parameters from the private object
     *
     * @return Parameters
     * @throws \ShenerCloud\Mqtt\Exceptions\Connect\NoConnectionParametersDefined
     */
    public function getConnectionParameters(): Parameters
    {
        if ($this->connectionParameters === null) {
            throw new NoConnectionParametersDefined('You must pass on the connection parameters before connecting');
        }

        return $this->connectionParameters;
    }

    /**
     * @return string
     * @throws \OutOfRangeException
     */
    public function createVariableHeader(): string
    {
        $bitString = $this->createUTF8String('MQTT'); // Connect MUST begin with MQTT
        $bitString .= $this->connectionParameters->getProtocolVersionBinaryRepresentation(); // Protocol level
        $bitString .= \chr($this->connectionParameters->getFlags());
        $bitString .= Utilities::convertNumberToBinaryString($this->connectionParameters->getKeepAlivePeriod());
        return $bitString;
    }

    /**
     * @return string
     * @throws \ShenerCloud\Mqtt\Exceptions\MustProvideUsername
     * @throws \OutOfRangeException
     */
    public function createPayload(): string
    {
        // The order in a connect string is clientId first
        $output = $this->createUTF8String((string)$this->connectionParameters->getClientId());

        // Then the willTopic if it is set
        $output .= $this->createUTF8String($this->connectionParameters->getWillTopic());

        // The willMessage will come next
        $output .= $this->createUTF8String($this->connectionParameters->getWillMessage());

        // If the username is set, it will come next
        $output .= $this->createUTF8String($this->connectionParameters->getUsername());

        // And finally the password as last parameter
        if ($this->connectionParameters->getPassword() !== '') {
            if ($this->connectionParameters->getUsername() === '') {
                throw new MustProvideUsername('A password can not be set without a username! Please set username');
            }
            $output .= $this->createUTF8String($this->connectionParameters->getPassword());
        }

        return $output;
    }

    public function shouldExpectAnswer(): bool
    {
        return true;
    }

    /**
     * Special handling of the ConnAck object: be able to inject more information into the object before throwing it
     *
     * @param string $brokerBitStream
     * @param ClientInterface $client
     *
     * @return ReadableContentInterface
     * @throws \DomainException
     * @throws \ShenerCloud\Mqtt\Exceptions\Connect\IdentifierRejected
     */
    public function expectAnswer(string $brokerBitStream, ClientInterface $client): ReadableContentInterface
    {
        $this->logger->info('String of incoming data confirmed, returning new object', ['callee' => \get_class($this)]);

        $eventManager = new EventManager($this->logger);
        try {
            $connAck = $eventManager->analyzeHeaders($brokerBitStream, $client);
        } catch (IdentifierRejected $e) {
            $possibleReasons = '';
            foreach ($this->connectionParameters->getClientId()->performStrictValidationCheck() as $errorMessage) {
                $possibleReasons .= $errorMessage . PHP_EOL;
            }

            $e->fillPossibleReason($possibleReasons);
            // Re-throw the exception with all information filled in
            throw $e;
        }

        return $connAck;
    }
}
