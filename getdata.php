<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pgw_acara8";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query data dari tabel penduduk
$sql = "SELECT kecamatan, longitude, latitude, luas, jumlah_penduduk FROM penduduk";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    // Ambil data dan simpan dalam array
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Kembalikan data dalam format JSON
echo json_encode($data);

// Tutup koneksi
$conn->close();
?>
