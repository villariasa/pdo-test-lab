<?php
class Database {
	private string $host = "localhost";
	private string $port = "5432";
	private string $dbname = "agriculture_db";
	private string $username = "postgres";
	private string $password = "123456";


	public function connect(): PDO {
		try {

				$dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
				$pdo = new PDO($dsn, $this->username, $this->password);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

				return $pdo;

		} catch(PDOException $e) {
			die("Database Error: ". $e->getMessage());
		}
		
	}

}