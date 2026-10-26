<?php

$host = "localhost";
$port = "5432";
$dbname = "agriculture_db";
$username = "postgres";
$password = "123456";

try {

    // Connect to default postgres database
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=postgres",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );


    // Check if database exists
    $stmt = $pdo->prepare("
        SELECT 1
        FROM pg_database
        WHERE datname = ?
    ");

    $stmt->execute([$dbname]);


    if (!$stmt->fetch()) {

        $pdo->exec(
            'CREATE DATABASE "' . $dbname . '"'
        );

        echo "Database created successfully." . PHP_EOL;

    } else {

        echo "Database already exists." . PHP_EOL;
    }


    $pdo = null;


    // Connect to agriculture_db
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    echo "Connected to $dbname successfully." . PHP_EOL;
    echo PHP_EOL;


    // =====================================================
    // CREATE TABLES
    // =====================================================

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS farmer (
            farmer_id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            contact_number VARCHAR(20),
            address VARCHAR(150),
            date_registered DATE DEFAULT CURRENT_DATE
        );
    ");


    $pdo->exec("
        CREATE TABLE IF NOT EXISTS farm (
            farm_id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            farmer_id INT NOT NULL,
            farm_name VARCHAR(100) NOT NULL,
            location VARCHAR(150) NOT NULL,

            area_hectares NUMERIC(8,2)
                CHECK (area_hectares > 0),

            soil_type VARCHAR(50),

            irrigation_type VARCHAR(50),

            CONSTRAINT fk_farm_farmer
                FOREIGN KEY (farmer_id)
                REFERENCES farmer(farmer_id)
                ON DELETE CASCADE
        );
    ");


    $pdo->exec("
        CREATE TABLE IF NOT EXISTS crop (
            crop_id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            crop_name VARCHAR(80) NOT NULL,
            variety VARCHAR(80),
            crop_category VARCHAR(50),

            growing_period_days INT
                CHECK (growing_period_days > 0),

            recommended_season VARCHAR(30)
        );
    ");


    $pdo->exec("
        CREATE TABLE IF NOT EXISTS planting (
            planting_id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            farm_id INT NOT NULL,
            crop_id INT NOT NULL,
            planting_date DATE NOT NULL,

            area_planted NUMERIC(8,2)
                CHECK (area_planted > 0),

            seed_quantity NUMERIC(8,2)
                CHECK (seed_quantity >= 0),

            expected_harvest_date DATE,

            status VARCHAR(30)
                DEFAULT 'Planted',

            CONSTRAINT fk_planting_farm
                FOREIGN KEY (farm_id)
                REFERENCES farm(farm_id)
                ON DELETE CASCADE,

            CONSTRAINT fk_planting_crop
                FOREIGN KEY (crop_id)
                REFERENCES crop(crop_id),

            CONSTRAINT chk_harvest_date
                CHECK (
                    expected_harvest_date IS NULL
                    OR expected_harvest_date >= planting_date
                )
        );
    ");


    $pdo->exec("
        CREATE TABLE IF NOT EXISTS harvest (
            harvest_id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            planting_id INT NOT NULL,
            harvest_date DATE NOT NULL,

            quantity_kg NUMERIC(10,2)
                CHECK (quantity_kg >= 0),

            selling_price_per_kg NUMERIC(10,2)
                CHECK (selling_price_per_kg >= 0),

            quality_grade VARCHAR(30),
            remarks VARCHAR(200),

            CONSTRAINT fk_harvest_planting
                FOREIGN KEY (planting_id)
                REFERENCES planting(planting_id)
                ON DELETE CASCADE
        );
    ");


    echo "Tables created successfully." . PHP_EOL;
    echo PHP_EOL;


    // =====================================================
    // START TRANSACTION
    // =====================================================

    $pdo->beginTransaction();


    // Clear old records
    $pdo->exec("
        TRUNCATE TABLE
            harvest,
            planting,
            farm,
            crop,
            farmer
        RESTART IDENTITY CASCADE;
    ");


    // =====================================================
    // 15 FARMERS
    // =====================================================

    $farmers = [

        ["Juan", "Dela Cruz", "09171234567", "Cebu City"],
        ["Maria", "Santos", "09181234567", "Carcar City, Cebu"],
        ["Pedro", "Reyes", "09191234567", "Danao City, Cebu"],
        ["Ana", "Garcia", "09201234567", "Argao, Cebu"],
        ["Jose", "Mendoza", "09211234567", "Bogo City, Cebu"],

        ["Ramon", "Flores", "09221234567", "Toledo City, Cebu"],
        ["Elena", "Cruz", "09231234567", "Naga City, Cebu"],
        ["Antonio", "Lim", "09241234567", "Minglanilla, Cebu"],
        ["Rosa", "Fernandez", "09251234567", "Liloan, Cebu"],
        ["Mario", "Villanueva", "09261234567", "Compostela, Cebu"],

        ["Teresa", "Navarro", "09271234567", "Talisay City, Cebu"],
        ["Carlos", "Ramos", "09281234567", "Consolacion, Cebu"],
        ["Liza", "Torres", "09291234567", "Dalaguete, Cebu"],
        ["Roberto", "Castillo", "09301234567", "Barili, Cebu"],
        ["Susan", "Aquino", "09311234567", "Sibonga, Cebu"]

    ];


    $stmt = $pdo->prepare("
        INSERT INTO farmer (
            first_name,
            last_name,
            contact_number,
            address
        )
        VALUES (?, ?, ?, ?)
        RETURNING farmer_id
    ");


    $farmerIds = [];


    foreach ($farmers as $farmer) {

        $stmt->execute($farmer);

        $farmerIds[] = $stmt->fetchColumn();
    }


    echo "15 farmer records inserted." . PHP_EOL;


    // =====================================================
    // 15 FARMS
    // =====================================================

    $farms = [

        ["Dela Cruz Farm", "Cebu City", 2.50, "Loam", "Rainfed"],

        ["Santos Rice Farm", "Carcar City, Cebu", 4.20,
            "Clay Loam", "Irrigated"],

        ["Reyes Agricultural Farm", "Danao City, Cebu", 3.80,
            "Sandy Loam", "Rainfed"],

        ["Garcia Vegetable Farm", "Argao, Cebu", 1.75,
            "Loam", "Drip Irrigation"],

        ["Mendoza Farm", "Bogo City, Cebu", 5.00,
            "Clay", "Irrigated"],

        ["Flores Corn Farm", "Toledo City, Cebu", 3.40,
            "Sandy Loam", "Rainfed"],

        ["Cruz Family Farm", "Naga City, Cebu", 2.80,
            "Loam", "Irrigated"],

        ["Lim Agricultural Farm", "Minglanilla, Cebu", 2.25,
            "Clay Loam", "Drip Irrigation"],

        ["Fernandez Farm", "Liloan, Cebu", 3.10,
            "Sandy Loam", "Rainfed"],

        ["Villanueva Green Farm", "Compostela, Cebu", 4.50,
            "Loam", "Irrigated"],

        ["Navarro Rice Field", "Talisay City, Cebu", 3.75,
            "Clay", "Irrigated"],

        ["Ramos Farm", "Consolacion, Cebu", 2.60,
            "Loam", "Rainfed"],

        ["Torres Vegetable Farm", "Dalaguete, Cebu", 1.80,
            "Sandy Loam", "Drip Irrigation"],

        ["Castillo Agricultural Farm", "Barili, Cebu", 5.50,
            "Clay Loam", "Irrigated"],

        ["Aquino Farm", "Sibonga, Cebu", 3.20,
            "Loam", "Rainfed"]

    ];


    $stmt = $pdo->prepare("
        INSERT INTO farm (
            farmer_id,
            farm_name,
            location,
            area_hectares,
            soil_type,
            irrigation_type
        )
        VALUES (?, ?, ?, ?, ?, ?)
        RETURNING farm_id
    ");


    $farmIds = [];


    foreach ($farms as $index => $farm) {

        $stmt->execute([

            $farmerIds[$index],

            $farm[0],
            $farm[1],
            $farm[2],
            $farm[3],
            $farm[4]

        ]);

        $farmIds[] = $stmt->fetchColumn();
    }


    echo "15 farm records inserted." . PHP_EOL;


    // =====================================================
    // 10 CROPS
    // =====================================================

    $crops = [

        ["Rice", "NSIC Rc 222", "Cereal", 115, "Wet Season"],
        ["Corn", "Yellow Corn", "Cereal", 100, "Dry Season"],

        ["Tomato", "Diamante Max", "Vegetable", 90,
            "Dry Season"],

        ["Eggplant", "Morena", "Vegetable", 100,
            "Year Round"],

        ["Banana", "Lakatan", "Fruit", 300,
            "Year Round"],

        ["Mango", "Carabao", "Fruit", 150,
            "Dry Season"],

        ["Peanut", "BPI Pn 9", "Legume", 110,
            "Dry Season"],

        ["Cassava", "Golden Yellow", "Root Crop", 240,
            "Year Round"],

        ["Sweet Potato", "NSIC Sp 30", "Root Crop", 120,
            "Year Round"],

        ["Watermelon", "Sweet 16", "Fruit", 85,
            "Dry Season"]

    ];


    $stmt = $pdo->prepare("
        INSERT INTO crop (
            crop_name,
            variety,
            crop_category,
            growing_period_days,
            recommended_season
        )
        VALUES (?, ?, ?, ?, ?)
        RETURNING crop_id
    ");


    $cropIds = [];


    foreach ($crops as $crop) {

        $stmt->execute($crop);

        $cropIds[] = $stmt->fetchColumn();
    }


    echo "10 crop records inserted." . PHP_EOL;


    // =====================================================
    // 50 PLANTING RECORDS
    // =====================================================

    $stmt = $pdo->prepare("
        INSERT INTO planting (
            farm_id,
            crop_id,
            planting_date,
            area_planted,
            seed_quantity,
            expected_harvest_date,
            status
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)
        RETURNING planting_id
    ");


    $plantingIds = [];

    $startDate = new DateTimeImmutable("2026-01-10");


    for ($i = 0; $i < 50; $i++) {

        $farmIndex = $i % 15;
        $cropIndex = $i % 10;


        $plantingDate =
            $startDate->modify(
                "+" . ($i * 3) . " days"
            );


        $growingDays =
            $crops[$cropIndex][3];


        $expectedHarvest =
            $plantingDate->modify(
                "+" . $growingDays . " days"
            );


        if ($i < 20) {

            $status = "Harvested";

        } else {

            $status = "Growing";
        }


        $areaPlanted =
            0.50 + (($i % 10) * 0.10);


        $seedQuantity =
            10 + (($i % 8) * 5);


        $stmt->execute([

            $farmIds[$farmIndex],

            $cropIds[$cropIndex],

            $plantingDate->format("Y-m-d"),

            $areaPlanted,

            $seedQuantity,

            $expectedHarvest->format("Y-m-d"),

            $status

        ]);


        $plantingIds[] =
            $stmt->fetchColumn();
    }


    echo "50 planting records inserted." . PHP_EOL;


    // =====================================================
    // 20 HARVEST RECORDS
    // =====================================================

    $stmt = $pdo->prepare("
        INSERT INTO harvest (
            planting_id,
            harvest_date,
            quantity_kg,
            selling_price_per_kg,
            quality_grade,
            remarks
        )
        VALUES (?, ?, ?, ?, ?, ?)
    ");


    for ($i = 0; $i < 20; $i++) {

        $cropIndex = $i % 10;


        $plantingDate =
            $startDate->modify(
                "+" . ($i * 3) . " days"
            );


        $growingDays =
            $crops[$cropIndex][3];


        $harvestDate =
            $plantingDate
                ->modify("+" . $growingDays . " days")
                ->modify("+2 days");


        $quantity =
            900 + ($i * 125);


        $price =
            22 + (($i % 8) * 3.50);


        if ($i % 3 == 0) {

            $grade = "Grade A";
            $remarks = "High quality harvest";

        } elseif ($i % 3 == 1) {

            $grade = "Grade B";
            $remarks = "Good harvest";

        } else {

            $grade = "Grade C";
            $remarks = "Acceptable quality";
        }


        $stmt->execute([

            $plantingIds[$i],

            $harvestDate->format("Y-m-d"),

            $quantity,

            $price,

            $grade,

            $remarks

        ]);
    }


    echo "20 harvest records inserted." . PHP_EOL;


    // Commit transaction
    $pdo->commit();


    // =====================================================
    // RECORD SUMMARY
    // =====================================================

    echo PHP_EOL;
    echo "==============================" . PHP_EOL;
    echo "RECORD SUMMARY" . PHP_EOL;
    echo "==============================" . PHP_EOL;


    $tables = [
        "crop",
        "farmer",
        "farm",
        "planting",
        "harvest"
    ];


    foreach ($tables as $table) {

        $count = $pdo
            ->query("SELECT COUNT(*) FROM $table")
            ->fetchColumn();

        echo ucfirst($table)
            . ": "
            . $count
            . " records"
            . PHP_EOL;
    }


    echo "==============================" . PHP_EOL;
    echo "All records inserted successfully!" . PHP_EOL;


} catch (PDOException $e) {

    if (isset($pdo) && $pdo->inTransaction()) {

        $pdo->rollBack();
    }

    echo "Database Error: "
        . $e->getMessage()
        . PHP_EOL;
}

?>