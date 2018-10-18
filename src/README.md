README
Complete PHP7+ MQTT client with full support for the MQTT version 3.1.1 protocol. This package is an entire rewrite of McFizh/libMQTT.

What is MQTT?
Please read the following wiki page for that :) Don't forget to read the other articles which may contain more useful information: What is QoS?
Difference between QoS and Retainability
What is ClientId?

Capabilities of this package:
This package is able to:

Connect to the broker. You can connect with virtually all optional parameters the protocol supports, including Will Message. The only exception to the rule is the clean session flag. This is not tested and may or may not work as intended.
Publish QoS level 0, 1 and 2 messages. All protocol supported parameters are also supported, such as retained messages and other options.
Subscribe on QoS level 0, 1 and 2 topics. Connection handling will be done automatically, no need to fiddle with PingRequests and alike.
Filters of topics are those used on the protocol itself, which eliminates the likeliness of bugs that may occur from incorrectly parsing such filters.
This package uses sockets to communicate (a)synchronously with the broker. If you don't want this, you are free to create your own client, for which you'll just have to implement an interface.

Examples
This package has many examples detailing the many things you can do with it. Please check the examples directory for more information. In case of questions or doubt, open up an Issue or submit a Pull Request if you feel the need to clarify, correct or add more examples.

References
[mqtt-v3.1.1-plus-errata01]

MQTT Version 3.1.1 Plus Errata 01. Edited by Andrew Banks and Rahul Gupta. 10 December 2015. OASIS Standard Incorporating Approved Errata 01. http://docs.oasis-open.org/mqtt/mqtt/v3.1.1/errata01/os/mqtt-v3.1.1-errata01-os-complete.html. Latest version: http://docs.oasis-open.org/mqtt/mqtt/v3.1.1/mqtt-v3.1.1.html.

Original library that served as inspiration for this one McFizh/libMQTT