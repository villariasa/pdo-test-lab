# <img src="https://api.iconify.design/heroicons/circle-stack-solid.svg?color=%2310b981" width="34" height="34" align="center" /> PHP PDO PostgreSQL Lab (Agriculture DB)

<p align="left">
  <img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/PostgreSQL-13+-316192?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL" />
  <img src="https://img.shields.io/badge/PDO-Prepared_Statements-38B2AC?style=for-the-badge" alt="PDO" />
  <img src="https://img.shields.io/badge/Icons-Tailwind_Heroicons-38BDF8?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind Icons" />
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License" />
</p>

A PHP Data Objects (PDO) laboratory and practice environment demonstrating secure database operations with PostgreSQL. This project showcases relational database modeling, automated schema migrations, transaction-managed seed scripts, and parameterized queries for an agricultural management domain.

---

## <img src="https://api.iconify.design/heroicons/clipboard-document-check-solid.svg?color=%2310b981" width="24" height="24" align="center" /> Features

- <img src="https://api.iconify.design/heroicons/check-circle-solid.svg?color=%2310b981" width="16" height="16" align="center" /> **Robust PDO Connection**: Configured with `PDO::ERRMODE_EXCEPTION` and `PDO::FETCH_ASSOC` for error handling and clean data handling.
- <img src="https://api.iconify.design/heroicons/shield-check-solid.svg?color=%233b82f6" width="16" height="16" align="center" /> **SQL Injection Prevention**: Implementation of prepared statements (`PDOStatement::prepare` and `execute`) with bound parameters.
- <img src="https://api.iconify.design/heroicons/arrow-path-solid.svg?color=%238b5cf6" width="16" height="16" align="center" /> **Automated Installation & Seeding**: Self-contained `install.php` script that creates the `agriculture_db` database, generates relational tables with constraints and foreign keys, and seeds realistic test data inside atomic transactions.
- <img src="https://api.iconify.design/heroicons/circle-stack-solid.svg?color=%2306b6d4" width="16" height="16" align="center" /> **Relational Domain Model**: Represents an agricultural ecosystem covering Farmers, Farms, Crops, Plantings, and Harvests.
- <img src="https://api.iconify.design/heroicons/command-line-solid.svg?color=%23f59e0b" width="16" height="16" align="center" /> **Query Testing Suite**: CLI test runner (`testPlanting.php`) verifying filtering by status, area planted, seed quantity, and multi-condition criteria.

---

## <img src="https://api.iconify.design/heroicons/table-cells-solid.svg?color=%2306b6d4" width="24" height="24" align="center" /> Database Architecture

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

## <img src="https://api.iconify.design/heroicons/folder-open-solid.svg?color=%233b82f6" width="24" height="24" align="center" /> Project Structure

```text
pdo-test-lab/
├── pdo/
│   ├── Database.php        # PDO connection wrapper & configuration
│   ├── Planting.php        # Planting Data Access Object / Model
│   ├── install.php         # Database migration & seeder script
│   ├── testPlanting.php    # CLI test harness for Planting operations
│   └── test.php            # Connection check sandbox script
├── .gitignore              # Ignored files and patterns
├── LICENSE                 # MIT License
└── README.md               # Project documentation with Tailwind icons
```

---

## <img src="https://api.iconify.design/heroicons/rocket-launch-solid.svg?color=%23f59e0b" width="24" height="24" align="center" /> Getting Started

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

## <img src="https://api.iconify.design/heroicons/code-bracket-solid.svg?color=%238b5cf6" width="24" height="24" align="center" /> Code Example

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

## <img src="https://api.iconify.design/heroicons/shield-check-solid.svg?color=%23ef4444" width="24" height="24" align="center" /> Security Notes

- Always use parameterized queries / prepared statements as implemented in `Planting.php` to prevent SQL injection vulnerabilities.
- For production deployments, move database credentials out of code into environment variables (`.env`).

---

## <img src="https://api.iconify.design/heroicons/document-text-solid.svg?color=%2364748b" width="24" height="24" align="center" /> License

This project is open-source and available under the [MIT License](LICENSE).
