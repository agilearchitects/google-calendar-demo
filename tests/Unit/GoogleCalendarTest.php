<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class GoogleCalendarTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testShow()
    {
        // Mocking service for calendar services
        $service = new class() {
            public $events;
            public function __construct() { $this->events = new class {
                public function listEvents() { return []; }
            }; }
        };

        // Create service
        $googleCalendar = new \App\Google\Calendar($service);

        // Get events
        $events = $googleCalendar->show("2010", "01");

        $this->assertIsArray($events);
    }
}
