<?php
// Scooter.php
class detail
{
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllScooters() {
        $query = $this->db->query("SELECT * FROM scooter_brands");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getScooterById($scooterId) {
        $query = $this->db->prepare("SELECT * FROM scooter_brands WHERE id = :id");
        $query->execute(['id' => $scooterId]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getScootersByMerkId($merkId) {
        $query = $this->db->prepare("SELECT * FROM scooter_brands WHERE merk_id = :merk_id");
        $query->execute(['merk_id' => $merkId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>