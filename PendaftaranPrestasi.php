<?php
require_once 'Pendaftaran.php';

class PendaftaranPrestasi extends Pendaftaran {
    protected $jenisPrestasi;
    protected $tingkatPrestasi;

    public function __construct($id_pendaftaran, $nama_calon, $asal_sekolah, $nilai_ujian, $biayaPendaftaranDasar, $jenisPrestasi, $tingkatPrestasi) {
        parent::__construct($id_pendaftaran, $nama_calon, $asal_sekolah, $nilai_ujian, $biayaPendaftaranDasar);
        $this->jenisPrestasi = $jenisPrestasi;
        $this->tingkatPrestasi = $tingkatPrestasi;
    }

    public function getJenisPrestasi() {
        return $this->jenisPrestasi;
    }

    public function getTingkatPrestasi() {
        return $this->tingkatPrestasi;
    }

    // Mengimplementasikan metode abstrak
    public function hitungTotalBiaya() {
        // Mendapatkan potongan/insentif apresiasi prestasi sebesar Rp50.000 dari biaya dasar
        return $this->biayaPendaftaranDasar - 50000;
    }

    public function tampilkanInfoJalur() {
        return "Jalur Prestasi - Jenis: " . $this->jenisPrestasi . " (" . $this->tingkatPrestasi . ")";
    }

    // Metode Query Spesifik
    public static function getDaftarPrestasi($db) {
        $query = "SELECT * FROM tabel_pendaftaran WHERE jalur_pendaftaran = 'Prestasi'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $daftarPrestasi = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $daftarPrestasi[] = new self(
                $row['id_pendaftaran'],
                $row['nama_calon'],
                $row['asal_sekolah'],
                (double)$row['nilai_ujian'],
                (double)$row['biaya_pendaftaran_dasar'],
                $row['jenis_prestasi'],
                $row['tingkat_prestasi']
            );
        }
        return $daftarPrestasi;
    }
}
?>
