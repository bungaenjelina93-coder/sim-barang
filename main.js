
"use strict";

// Mengambil elemen HTML
const form = document.getElementById("form-barang");
const daftarBarang = document.getElementById("daftar-barang");
const inputCari = document.getElementById("cari-barang");

// VALIDASI FORMULIR
form.addEventListener("submit", function(event) {

    // Mengambil data dari formulir
    const kode = document.getElementById("kode").value
        .trim().toUpperCase();

    const jumlah = Number(document.getElementById("jumlah").value);

    // Validasi kode barang
    if (!/^BRG-\d+$/.test(kode)) {
        event.preventDefault();
        alert("Kode barang harus menggunakan format BRG-001!");
        return;
    }

    // Validasi jumlah barang
    if (!Number.isFinite(jumlah) || jumlah <= 0) {
        event.preventDefault();
        alert("Jumlah barang harus lebih dari 0!");
        return;
    }

    // Memeriksa kode barang yang sudah terdaftar
    const kodeTerdaftar = Array.from(
        daftarBarang.querySelectorAll("tr td:first-child")
    ).some(function(cell) {
        return cell.textContent.trim() === kode;
    });

    if (kodeTerdaftar) {
        event.preventDefault();
        alert("Kode barang sudah terdaftar!");
        return;
    }

    // Jika valid, formulir akan dikirim ke simpan.php
    // menggunakan metode POST sesuai atribut form di HTML.
});

// FITUR PENCARIAN BARANG REAL-TIME
inputCari.addEventListener("input", function() {

    const kataKunci = inputCari.value.toLowerCase();

    const semuaBaris = daftarBarang.querySelectorAll("tr");

    semuaBaris.forEach(function(baris) {

        const isiBaris = baris.textContent.toLowerCase();

        if (isiBaris.includes(kataKunci)) {
            baris.style.display = "";
        } else {
            baris.style.display = "none";
        }

    });

});
