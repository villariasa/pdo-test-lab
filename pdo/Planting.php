<?php

class Planting {
    private PDO $pdo;

    // construct
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }


    // display planting records by status
    public function getByStatus(string $status) {
        $sql = "SELECT * FROM PLANTING WHERE STATUS = :status";
        $stmt = $this->pdo->prepare($sql);
        #return
        $stmt->execute(["status" => $status]);
        return $stmt->fetchAll();
    }

    // display planting records by area planted
    public function getByAreaPlanted(int $area_planted) {
        $sql = "SELECT * FROM PLANTING WHERE AREA_PLANTED = :area_planted";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["area_planted" => $area_planted]);
        #return
        return $stmt->fetchAll();
    }

    // display planting records by area planted
    public function getBySeedQuantity(int $seed_quantity) {
        $sql = "SELECT * FROM PLANTING WHERE SEED_QUANTITY = :seed_quantity";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["seed_quantity" => $seed_quantity]);
        #return
        return $stmt->fetchAll();
    }

    // display planting records with farm and crop information
    public function getAllWithFarmAndCrop(string $status , int $area_planted, int $seed_quantity) {
        $sql = "SELECT * FROM PLANTING WHERE STATUS = :status AND AREA_PLANTED >= :area_planted AND SEED_QUANTITY >= :seed_quantity";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            "status" => $status,
            "area_planted" => $area_planted,
            "seed_quantity" => $seed_quantity
        ]);
        #return
        return $stmt->fetchAll();
    }
}
