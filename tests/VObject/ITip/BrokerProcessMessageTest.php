<?php

namespace Sabre\VObject\ITip;

class BrokerProcessMessageTest extends BrokerTester {

    function testRequestNew() {

        $itip = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
METHOD:REQUEST
BEGIN:VEVENT
SEQUENCE:1
UID:foobar
END:VEVENT
END:VCALENDAR
ICS;

        $expected = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
BEGIN:VEVENT
SEQUENCE:1
UID:foobar
END:VEVENT
END:VCALENDAR
ICS;

        $result = $this->process($itip, null, $expected);

    }

    function testRequestUpdate() {

        $itip = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
METHOD:REQUEST
BEGIN:VEVENT
SEQUENCE:2
UID:foobar
END:VEVENT
END:VCALENDAR
ICS;

        $old = <<<ICS
BEGIN:VCALENDAR
BEGIN:VEVENT
SEQUENCE:1
UID:foobar
END:VEVENT
END:VCALENDAR
ICS;

        $expected = <<<ICS
BEGIN:VCALENDAR
BEGIN:VEVENT
SEQUENCE:2
UID:foobar
END:VEVENT
END:VCALENDAR
ICS;

        $result = $this->process($itip, $old, $expected);

    }

    function testCancel() {

        $itip = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
METHOD:CANCEL
BEGIN:VEVENT
SEQUENCE:2
UID:foobar
END:VEVENT
END:VCALENDAR
ICS;

        $old = <<<ICS
BEGIN:VCALENDAR
BEGIN:VEVENT
SEQUENCE:1
UID:foobar
END:VEVENT
END:VCALENDAR
ICS;

        $expected = <<<ICS
BEGIN:VCALENDAR
BEGIN:VEVENT
UID:foobar
STATUS:CANCELLED
SEQUENCE:2
END:VEVENT
END:VCALENDAR
ICS;

        $result = $this->process($itip, $old, $expected);

    }

    function testCancelNoExistingEvent() {

        $itip = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
METHOD:CANCEL
BEGIN:VEVENT
SEQUENCE:2
UID:foobar
END:VEVENT
END:VCALENDAR
ICS;

        $old = null;
        $expected = null;

        $result = $this->process($itip, $old, $expected);

    }

    function testUnsupportedComponent() {

        $itip = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
BEGIN:VTODO
SEQUENCE:2
UID:foobar
END:VTODO
END:VCALENDAR
ICS;

        $old = null;
        $expected = null;

        $result = $this->process($itip, $old, $expected);

    }

    function testUnsupportedMethod() {

        $itip = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
METHOD:PUBLISH
BEGIN:VEVENT
SEQUENCE:2
UID:foobar
END:VEVENT
END:VCALENDAR
ICS;

        $old = null;
        $expected = null;

        $result = $this->process($itip, $old, $expected);

    }

    function testRequestNewWithAlarm() {

        $itip = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
METHOD:REQUEST
BEGIN:VEVENT
SEQUENCE:1
UID:foobar
BEGIN:VALARM
TRIGGER:-PT30M
REPEAT:2
DURATION:PT15M
ACTION:DISPLAY
END:VALARM
END:VEVENT
END:VCALENDAR
ICS;

        $expected = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
BEGIN:VEVENT
SEQUENCE:1
UID:foobar
END:VEVENT
END:VCALENDAR
ICS;

        $result = $this->process($itip, null, $expected);
    }

}
