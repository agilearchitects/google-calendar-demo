<?php namespace App\Google;

use Google_Service_Calendar;

class Event {
  public string $id;
  public string $title;
  public string $when;
  public function __construct($id, $title, $when) {
    $this->id = $id;
    $this->title = $title;
    $this->when = $when;
  }
}

class AddEvent {
  public string $title;
  public string $when;
}

class UpdateEvent {
  public string $id;
  public string $title;
  public string $when;
}

class Calendar {
  private $service;

  public function __construct($service) {
    $this->service = $service;
  }

  public function show(int $year, int $week) {
    $date = new \DateTime();
    // Set year and week
    $date->setISODate($year,$week);

    $events = $this->service->events->listEvents("primary", [
      "timeMin" => $date->format("c"),
      "timeMax" => $date->modify("+1 week")->format("c"),
      "orderBy" => "startTime",
      "singleEvents" => true
    ]);

    $returnArray = [];
    foreach($events as $event) {
      if($event->start->dateTime === null) {
        $date = new \DateTime($event->start->date);
      } else {
        $date = new \DateTime($event->start->dateTime);
      }
      $returnArray[] = new Event($event->id, $event->summary, $date->format("Y-m-d H:i:s"));
    }

    return $returnArray;
  }

  public function add(AddEvent $addEvent) {
    
  } 
  public function update(UpdateEvent $updateEvent) {

  }
  public function delete(int $eventId) {

  }
}