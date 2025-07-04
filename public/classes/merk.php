<?php

class Merk
{
    public $id;
    public $naam;
    private $db;

    // Constructor met databaseverbinding en merk_id
    public function __construct($db, $id)
    {
        $this->db = $db; // De databaseverbinding
        $this->id = (int) $id;  // Merk ID als integer
        $this->loadMerk(); // Laad merknaam op basis van id
    }

    // Haal de naam van het merk op
    private function loadMerk()
    {
        $query = $this->db->prepare("SELECT naam FROM merk WHERE id = :id");
        $query->bindParam(':id', $this->id, PDO::PARAM_INT);
        $query->execute();
        $merkData = $query->fetch(PDO::FETCH_ASSOC);

        if ($merkData) {
            $this->naam = $merkData['naam'];
        } else {
            throw new Exception("Merk niet gevonden!");
        }
    }

    // Geef de naam van het merk terug
    public function getNaam()
    {
        return $this->naam;
    }

    // Haal scooters op die bij dit merk horen
    public function getScooters()
    {
        $query = $this->db->prepare("SELECT * FROM scooters WHERE merk_id = :merk_id");
        $query->bindParam(':merk_id', $this->id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
