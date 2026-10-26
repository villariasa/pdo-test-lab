# PHP PDO PostgreSQL Lab (Agriculture Database)

A PHP Data Objects (PDO) laboratory and practice environment demonstrating secure database operations with PostgreSQL. This project showcases relational database modeling, automated schema migrations, transaction-managed seed scripts, and parameterized queries for an agricultural management domain.

---

## 📋 Features

- **Robust PDO Connection**: Configured with `PDO::ERRMODE_EXCEPTION` and `PDO::FETCH_ASSOC` for error handling and clean data handling.
- **SQL Injection Prevention**: Implementation of prepared statements (`PDOStatement::prepare` and `execute`) with bound parameters.
- **Automated Installation & Seeding**: Self-contained `install.php` script that creates the `agriculture_db` database, generates relational tables with constraints and foreign keys, and seeds realistic test data inside atomic transactions.
- **Relational Domain Model**: Represents an agricultural ecosystem covering Farmers, Farms, Crops, Plantings, and Harvests.
- **Query Testing Suite**: CLI test runner (`testPlanting.php`) verifying filtering by status, area planted, seed quantity, and multi-condition criteria.

---

## 🗄️ Database Architecture

The schema represents a 5-table relational structure with cascading deletes and integrity constraints:

```
+----------------+       1:N       +---------------+
|     farmer     | <------------> |     farm      |
|----------------|                |---------------|
| farmer_id (PK) |                | farm_id (PK)  |
| first_name     |                | farmer_id(FK) |
| last_name      |                | farm_name     |
| contact_number |                | location      |
| address        |                | area_hectares |
| date_registered|                | soil_type     |
+----------------+                | irrigation    |
                                  +---------------+
                                          |
                                          | 1:N
                                          v
+----------------+       1:N       +-----------------------+
|      crop      | <------------> |       planting        |
|----------------|                |-----------------------|
| crop_id (PK)   |                | planting_id (PK)      |
| crop_name      |                | farm_id (FK)          |
| variety        |                | crop_id (FK)          |
| crop_category  |                | planting_date         |
| growing_period |                | area_planted          |
| season         |                | seed_quantity         |
+----------------+                | expected_harvest_date |
                                  | status                |
                                  +-----------------------+
                                              |
                                              | 1:N
                                              v
                                  +-----------------------+
                                  |        harvest        |
                                  |-----------------------|
                                  | harvest_id (PK)       |
                                  | planting_id (FK)      |
                                  | harvest_date          |
                                  | quantity_kg           |
                                  | selling_price_per_kg  |
                                  | quality_grade         |
                                  | remarks               |
                                  +-----------------------+
```

### Table Specifications

1. **`farmer`**: Stores farmer personal information and registration date.
2. **`farm`**: Tracks farm properties, total acreage, soil classification, and irrigation systems linked to a farmer.
3. **`crop`**: Catalog of crop types, categories, recommended seasons, and expected growing periods.
4. **`planting`**: Core operational entity tracking planting dates, hectares planted, seed volumes, and status (`Planted`, `Growing`, `Harvested`).
5. **`harvest`**: Records yield outputs, harvest dates, market pricing, and quality grading.

---

## 📂 Project Structure

```text
pdo-test-lab/
├── pdo/
│   ├── Database.php        # PDO connection wrapper & configuration
│   ├── Planting.php        # Planting Data Access Object / Model
│   ├── install.php         # Database migration & seeder script
│   ├── testPlanting.php    # CLI test harness for Planting operations
│   └── test.php            # Sandbox script
├── .gitignore              # Ignored files and patterns
└── README.md               # Project documentation
```

---

## 🚀 Getting Started

### Prerequisites

- **PHP 8.0+** with `pdo_pgsql` extension enabled.
- **PostgreSQL 13+** server running locally or accessible via network.

Verify that PHP has the PostgreSQL PDO driver enabled:
```bash
php -m | grep pdo_pgsql
```

### 1. Database Configuration

Open `pdo/Database.php` and `pdo/install.php` to adjust your PostgreSQL connection credentials if different from default:

```php
private string $host = "localhost";
private string $port = "5432";
private string $dbname = "agriculture_db";
private string $username = "postgres";
private string $password = "your_postgres_password";
```

### 2. Run Database Migration & Seeding

Execute `install.php` from the terminal to create the database, tables, and test records:

```bash
cd pdo
php install.php
```

Expected output:
```text
Connected to agriculture_db successfully.
Tables created successfully.
Database setup and seeding completed successfully!
```

### 3. Run Query Tests

Run `testPlanting.php` to verify database connectivity and model methods:

```bash
php testPlanting.php
```

---

## 💻 Code Example

### Querying with Prepared Statements

```php
require_once "Database.php";
require_once "Planting.php";

$pdo = (new Database())->connect();
$planting = new Planting($pdo);

// Fetch growing crops
$records = $planting->getByStatus("growing");

foreach ($records as $row) {
    echo "Planting #{$row['planting_id']} - Farm: {$row['farm_id']}, Area: {$row['area_planted']} ha\n";
}
```

---

## 🛡️ Security Notes

- Always use parameterized queries / prepared statements as implemented in `Planting.php` to prevent SQL injection vulnerabilities.
- For production deployments, move database credentials out of code into environment variables (`.env`).

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).
