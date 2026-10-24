
"use strict";

// Mengambil elemen HTML
const form = document.getElementById("form-barang");
const daftarBarang = document.getElementById("daftar-barang");
const inputCari = document.getElementById("cari-barang");

// Membuat elemen notifikasi toast
const toast = document.createElement("div");

toast.style.cssText = `
    position: fixed;
    bottom: 25px;
    right: 25px;
    background: #198754;
    color: white;
    padding: 15px 25px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    display: none;
    z-index: 9999;
`;

toast.setAttribute("role", "status");
toast.setAttribute("aria-live", "polite");
document.body.appendChild(toast);

let timerToast;

// Fungsi menampilkan notifikasi
function tampilkanToast(pesan) {
    toast.textContent = pesan;
    toast.style.display = "block";

    clearTimeout(timerToast);

    timerToast = setTimeout(function() {
        toast.style.display = "none";
    }, 3000);
}

// FITUR 1: VALIDASI DAN PENAMBAHAN BARANG
form.addEventListener("submit", function(event) {
    event.preventDefault();

    // Mengambil nilai formulir
    const kode = document.getElementById("kode").value
        .trim().toUpperCase();

    const nama = document.getElementById("nama").value.trim();
    const kategori = document.getElementById("kategori").value;
    const jumlah = Number(document.getElementById("jumlah").value);
    const kondisi = document.getElementById("kondisi").value;

    // Validasi kode barang
    if (!/^BRG-\d+$/.test(kode)) {
        alert("Kode barang harus menggunakan format BRG-001!");
        return;
    }

    // Validasi jumlah barang
    if (!Number.isFinite(jumlah) || jumlah <= 0) {
        alert("Jumlah barang harus lebih dari 0!");
        return;
    }

    // Memeriksa kode yang sudah terdaftar
    const kodeTerdaftar = Array.from(
        daftarBarang.querySelectorAll("tr td:first-child")
    ).some(function(cell) {
        return cell.textContent.trim() === kode;
    });

    if (kodeTerdaftar) {
        alert("Kode barang sudah terdaftar!");
        return;
    }

    // Membuat baris baru
    const baris = document.createElement("tr");

    const dataBarang = [
        kode,
        nama,
        kategori,
        jumlah,
        kondisi
    ];

    dataBarang.forEach(function(data) {
        const kolom = document.createElement("td");
        kolom.textContent = data;
        baris.appendChild(kolom);
    });

    // Menambahkan barang ke tabel
    daftarBarang.appendChild(baris);

    // Mengosongkan formulir
    form.reset();

    // Memperbarui hasil pencarian
    inputCari.dispatchEvent(new Event("input"));

    // Menampilkan notifikasi
    tampilkanToast("Data barang berhasil ditambahkan!");

    // Mengarahkan tampilan ke tabel
    document.getElementById("inventaris").scrollIntoView({
        behavior: "smooth"
    });
});

// FITUR 2: PENCARIAN BARANG REAL-TIME
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
