<?php

namespace ShenerCloud\Mqtt\Exceptions;

class InvalidResponseType extends \InvalidArgumentException
{
    public $expectedResponse = 0;

    public $actualResponse = 0;
}
