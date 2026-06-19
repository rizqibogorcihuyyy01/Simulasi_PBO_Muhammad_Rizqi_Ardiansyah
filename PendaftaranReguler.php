<?php
require_once 'Pendaftaran.php';

class PendaftaranReguler extends Pendaftaran {
    protected $pilihanProdi;
    protected $lokasiKampus;

    public function __construct($id_pendaftaran, $nama_calon, $asal_sekolah, $nilai_ujian, $biayaPendaftaranDasar, $pilihanProdi, $lokasiKampus) {
        parent::__construct($id_pendaftaran, $nama_calon, $asal_sekolah, $nilai_ujian, $biayaPendaftaranDasar);
        $this->pilihanProdi = $pilihanProdi;
        $this->lokasiKampus = $lokasiKampus;
    }

    public function getPilihanProdi() {
        return $this->pilihanProdi;
    }

    public function getLokasiKampus() {
        return $this->lokasiKampus;
    }

    // Mengimplementasikan metode abstrak
    public function hitungTotalBiaya() {
        // Jalur Reguler membayar biaya pendaftaran dasar secara penuh
        return $this->biayaPendaftaranDasar;
    }

    public function tampilkanInfoJalur() {
        return "Jalur Reguler - Pilihan Prodi: " . $this->pilihanProdi . ", Lokasi Kampus: " . $this->lokasiKampus;
    }

    // Metode Query Spesifik
    public static function getDaftarReguler($db) {
        $query = "SELECT * FROM tabel_pendaftaran WHERE jalur_pendaftaran = 'Reguler'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $daftarReguler = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $daftarReguler[] = new self(
                $row['id_pendaftaran'],
                $row['nama_calon'],
                $row['asal_sekolah'],
                (double)$row['nilai_ujian'],
                (double)$row['biaya_pendaftaran_dasar'],
                $row['pilihan_prodi'],
                $row['lokasi_kampus']
            );
        }
        return $daftarReguler;
    }
}
?>
