<?php

abstract class Pendaftaran {
    // Properti terenkapsulasi (protected)
    protected $id_pendaftaran;
    protected $nama_calon;
    protected $asal_sekolah;
    protected $nilai_ujian;
    protected $biayaPendaftaranDasar; // Dipetakan dari kolom database: biaya_pendaftaran_dasar

    // Constructor untuk memetakan nilai dari kolom tabel database
    public function __construct($id_pendaftaran, $nama_calon, $asal_sekolah, $nilai_ujian, $biayaPendaftaranDasar) {
        $this->id_pendaftaran = $id_pendaftaran;
        $this->nama_calon = $nama_calon;
        $this->asal_sekolah = $asal_sekolah;
        $this->nilai_ujian = $nilai_ujian;
        $this->biayaPendaftaranDasar = $biayaPendaftaranDasar;
    }

    // Getter untuk mengakses properti dari luar kelas (karena properti bersifat protected)
    public function getIdPendaftaran() {
        return $this->id_pendaftaran;
    }

    public function getNamaCalon() {
        return $this->nama_calon;
    }

    public function getAsalSekolah() {
        return $this->asal_sekolah;
    }

    public function getNilaiUjian() {
        return $this->nilai_ujian;
    }

    public function getBiayaPendaftaranDasar() {
        return $this->biayaPendaftaranDasar;
    }

    // Metode Abstrak (Tanpa Isi/Body)
    abstract public function hitungTotalBiaya();
    abstract public function tampilkanInfoJalur();
}
?>
