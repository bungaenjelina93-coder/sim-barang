
<?php

class Barang {

    // Property atau atribut barang
    public $kode;
    public $nama;
    public $jumlah;

    // Constructor untuk mengisi data barang
    public function __construct($kode, $nama, $jumlah) {
        $this->kode = $kode;
        $this->nama = $nama;
        $this->jumlah = $jumlah;
    }

    // Method untuk menampilkan informasi barang
    public function tampilkanInfo() {
        return "Kode Barang: " . $this->kode .
               " | Nama Barang: " . $this->nama .
               " | Jumlah: " . $this->jumlah;
    }
}

?>
