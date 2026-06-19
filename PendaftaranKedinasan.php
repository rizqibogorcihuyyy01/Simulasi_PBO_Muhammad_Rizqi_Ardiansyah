<?php
require_once 'Pendaftaran.php';

class PendaftaranKedinasan extends Pendaftaran {
    protected $skIkatanDinas;
    protected $instansiSponsor;

    public function __construct($id_pendaftaran, $nama_calon, $asal_sekolah, $nilai_ujian, $biayaPendaftaranDasar, $skIkatanDinas, $instansiSponsor) {
        parent::__construct($id_pendaftaran, $nama_calon, $asal_sekolah, $nilai_ujian, $biayaPendaftaranDasar);
        $this->skIkatanDinas = $skIkatanDinas;
        $this->instansiSponsor = $instansiSponsor;
    }

    public function getSkIkatanDinas() {
        return $this->skIkatanDinas;
    }

    public function getInstansiSponsor() {
        return $this->instansiSponsor;
    }

    // Mengimplementasikan metode abstrak
    public function hitungTotalBiaya() {
        // Jalur Kedinasan biaya pendaftarannya ditanggung oleh instansi sponsor (Gratis/0 bagi calon pendaftar)
        return 0;
    }

    public function tampilkanInfoJalur() {
        return "Jalur Kedinasan - SK: " . $this->skIkatanDinas . ", Sponsor: " . $this->instansiSponsor;
    }

    // Metode Query Spesifik
    public static function getDaftarKedinasan($db) {
        $query = "SELECT * FROM tabel_pendaftaran WHERE jalur_pendaftaran = 'Kedinasan'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $daftarKedinasan = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $daftarKedinasan[] = new self(
                $row['id_pendaftaran'],
                $row['nama_calon'],
                $row['asal_sekolah'],
                (double)$row['nilai_ujian'],
                (double)$row['biaya_pendaftaran_dasar'],
                $row['sk_ikatan_dinas'],
                $row['instansi_sponsor']
            );
        }
        return $daftarKedinasan;
    }
}
?>
