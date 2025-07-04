    <?php

    class scooter
    {
        public $id;
        public $naam;
        public $model;
        public $jaar;

        public $kleur;

        public $afbeelding;


        public $beschrijving;
        public $prijs;

        public $merk_id;

        public function __construct()
        {
            settype($this->id, 'integer');
            settype($this->merk_id, 'integer');
        }
    }