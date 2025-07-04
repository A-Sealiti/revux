<?php
class user
{
    public $id;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $role;

    public function __construct()
    {
        settype(   $this->id, type: 'integer');
    }

}
?>