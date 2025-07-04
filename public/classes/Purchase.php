    <?php

    class purchase
    {
        public $id;
        public $fname;
        public $lname;
        public $email;
        public $address;
        public $zipcode;
        public $city;
        public $date;
        public $scooter_id;

        public function __construct()
        {
            settype($this->id, 'integer');
            settype($this->scooter_id,'integer');
        }
    }