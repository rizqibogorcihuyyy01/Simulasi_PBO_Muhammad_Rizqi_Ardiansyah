<?php
class Database {
    private $host = "localhost";
    private $dbname = "db_pendaftaran";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Koneksi ke host terlebih dahulu, tanpa dbname agar tidak gagal jika DB belum ada
            $this->conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Buat database jika belum ada
            $this->conn->exec("CREATE DATABASE IF NOT EXISTS `" . $this->dbname . "`");
            
            // Gunakan database tersebut
            $this->conn->exec("USE `" . $this->dbname . "`");
            
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Cek apakah tabel_pendaftaran sudah ada
            $tableCheck = $this->conn->query("SHOW TABLES LIKE 'tabel_pendaftaran'")->rowCount();
            if ($tableCheck == 0) {
                // Jika tabel belum ada, otomatis jalankan isi file SQL untuk import skema & data
                $sqlPath = __DIR__ . '/../database/DB_SIMULASI_PBO_TRPL1A_RizqiArdiansyah.sql';
                if (file_exists($sqlPath)) {
                    $sqlContent = file_get_contents($sqlPath);
                    $this->conn->exec($sqlContent);
                }
            }
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
