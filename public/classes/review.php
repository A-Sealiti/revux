<?php

class review
{
    public $id;
    public $naam;
    public $review_tekst;
    public $datum;
    public $sterren;
    public $scooter_id;
    public function __construct()
    {
        settype($this->id, 'integer');
        settype($this->scooter_id,'integer');
    }
}