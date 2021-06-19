<?php 
/**
 * @property-read string name
 * @property-read int event_id
 * @property-read \DateTimeInterface begin
 * @property-read \DateTimeInterface end
 * @note better typehinting for your IDE
 */
class FaqModel extends AbstractModel {
    // // you can define realtions
    // public function getParticipants(): GroupedSelection {
    //     return $this->related('participant', 'event_id');
    // }
    // // you can define own metods
    // public function __toArray(): array {
    //     return [
    //         'eventId' => $this->event_id,          
    //         'begin' => $this->begin ? $this->begin->format('c') : null,
    //         'end' => $this->end ? $this->end->format('c') : null,          
    //         'name' => $this->name,
    //     ];
    // }
}