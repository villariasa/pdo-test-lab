<?php
require_once "Database.php";
require_once "Planting.php";

$pdo = (new Database())->connect();
$planting = new Planting($pdo);

$status = "growing";
$planting_record_by_status = $planting->getByStatus($status);

// 1. display planting records by status.
echo "PLANTING RECORDS BY STATUS\n";
echo "-----------------------------------------------\n";
if (!$planting_record_by_status) {
    echo "No planting records found.";
} else {
    foreach ($planting_record_by_status as $row) {
        // display data
        echo $row["planting_id"] . "|" . $row["farm_id"] . "|" . $row["crop_id"] . "|" . $row["planting_date"] . "|" . $row["area_planted"] . "|". $row["seed_quantity"] . "|" . $row["expected_harvest_date"] . "|" . $row["status"] . "\n" ;
    }
}

// 2. display planting records by area planted
$area_planted = (int) 1.0;
$planting_record_by_area = $planting->getByAreaPlanted($area_planted);

echo "\n\nPLANTING RECORDS BY AREA PLANTED\n";
echo "-----------------------------------------------\n";
if (!$planting_record_by_area) {
    echo "No planting records found.";
} else {
    foreach ($planting_record_by_area as $row) {
        // display data
        echo $row["planting_id"] . "|" . $row["farm_id"] . "|" . $row["crop_id"] . "|" . $row["planting_date"] . "|" . $row["area_planted"] . "|". $row["seed_quantity"] . "|" . $row["expected_harvest_date"] . "|" . $row["status"] . "\n" ;
    }
}


// 3. display planting records by seed quantity
$seed_quantity = (int) 35.00;
$planting_record_by_seed_qty = $planting->getBySeedQuantity($seed_quantity);

echo "\n\nPLANTING RECORDS BY SEED QUANTITY\n";
echo "-----------------------------------------------\n";
if (!$planting_record_by_seed_qty) {
    echo "No planting records found.";
} else {
    foreach ($planting_record_by_seed_qty as $row) {
        // display data
        echo $row["planting_id"] . "|" . $row["farm_id"] . "|" . $row["crop_id"] . "|" . $row["planting_date"] . "|" . $row["area_planted"] . "|". $row["seed_quantity"] . "|" . $row["expected_harvest_date"] . "|" . $row["status"] . "\n" ;
    }
}


// 4. display planting records with farms and crop information
$status = "Growing";
$area_planted = (int) 1.00;
$seed_quantity = (int) 40.00;
$planting_with_farm_and_crop = $planting->getAllWithFarmAndCrop($status, $area_planted, $seed_quantity);

echo "\n\nPLANTING WITH FARM AND CROP\n";
echo "-----------------------------------------------\n";
if (!$planting_with_farm_and_crop) {
    echo "No planting records found.";
} else {
    foreach ($planting_with_farm_and_crop as $row) {
        // display data
        echo $row["planting_id"] . "|" . $row["farm_id"] . "|" . $row["crop_id"] . "|" . $row["planting_date"] . "|" . $row["area_planted"] . "|". $row["seed_quantity"] . "|" . $row["expected_harvest_date"] . "|" . $row["status"] . "\n" ;
    }
}
