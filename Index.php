<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Web GIS Kabupaten Sleman</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        #container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        #map {
            width: 100%;
            max-width: 1200px;
            height: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #table-container {
            width: 100%;
            max-width: 1200px;
            margin-top: 20px;
        }

        h1 {
            color: green;
            font-weight: bold;
        }

        h2 {
            color: black;
            margin-top: -10px;
            font-weight: 400;
        }
    </style>
</head>

<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"> <i class="fa-solid fa-map-location-dot" style="color: #162237;"></i>
                Kabupaten Sleman</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="https://geoportal.slemankab.go.id/#/" target="_blank"> <i
                                class="fa-solid fa-layer-group" style="color: #162237;"></i> Sumber Data</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#infoModal"><i
                                class="fa-solid fa-circle-info" style="color: #162237;"></i> Info</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Info Pembuat -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="infoModalLabel">Info Pembuat</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>Nama</th>
                            <td>Muhammad Naufal Hidayat</td>
                        <tr>
                            <th>NIM</th>
                            <td>23/520500/SV/23249</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>PG WEB B</td>
                        </tr>
                        <tr>
                            <th>Github</th>
                            <td><a href="https://github.com/Nflhdyt" target="_blank"
                                    rel="noopener noreferrer">github.com/Nflhdyt</a></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature Modal -->
    <div class="modal fade" id="featureModal" tabindex="-1" aria-labelledby="featureModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="featureModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="featureModalBody">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <h1>Web GIS</h1>
        <h2>Kabupaten Sleman</h2>
    </div>

    <div id="container" class="container">
        <!-- Peta -->
        <div id="map"></div>

        <!-- Tabel Data -->
        <div id="table-container" class="mt-4">
            <?php
            // Koneksi database tetap sama
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "pgw_acara8";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Update dan Delete data tetap sama
            if (isset($_POST['update'])) {
                $kecamatan = $_POST['kecamatan'];
                $longitude = $_POST['longitude'];
                $latitude = $_POST['latitude'];
                $luas = $_POST['luas'];
                $jumlah_penduduk = $_POST['jumlah_penduduk'];

                $update_sql = "UPDATE penduduk SET longitude='$longitude', latitude='$latitude', luas='$luas', jumlah_penduduk='$jumlah_penduduk' WHERE kecamatan='$kecamatan'";
                
                if ($conn->query($update_sql) === TRUE) {
                    echo "<div class='alert alert-success text-center'>Record updated successfully</div>";
                } else {
                    echo "<div class='alert alert-danger text-center'>Error updating record: " . $conn->error . "</div>";
                }
            }

            if (isset($_GET['delete'])) {
                $kecamatan_to_delete = $_GET['delete'];
                $delete_sql = "DELETE FROM penduduk WHERE kecamatan='$kecamatan_to_delete'";
                
                if ($conn->query($delete_sql) === TRUE) {
                    echo "<div class='alert alert-success text-center'>Record deleted successfully</div>";
                } else {
                    echo "<div class='alert alert-danger text-center'>Error deleting record: " . $conn->error . "</div>";
                }
            }

            // Tampilkan data dalam tabel Bootstrap
            $sql = "SELECT kecamatan, longitude, latitude, luas, jumlah_penduduk FROM penduduk";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table class='table table-bordered table-hover'>
                <thead class='table-light'>
                <tr>
                <th>Kecamatan</th>
                <th>Longitude</th>
                <th>Latitude</th>
                <th>Luas</th>
                <th>Jumlah Penduduk</th>
                <th>Action</th>
                </tr>
                </thead>
                <tbody>";
                
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>".$row["kecamatan"]."</td>
                    <td>".$row["longitude"]."</td>
                    <td>".$row["latitude"]."</td>
                    <td>".$row["luas"]."</td>
                    <td align='right'>".$row["jumlah_penduduk"]."</td>
                    <td>
                        <a href='?edit=".$row["kecamatan"]."' class='btn btn-sm btn-warning'>Edit</a> 
                        <button class='btn btn-sm btn-danger' onclick=\"confirmDelete('".$row["kecamatan"]."')\">Delete</button>
                    </td>
                    </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p class='text-center'>No data available</p>";
            }

            if (isset($_GET['edit'])) {
                $kecamatan_to_edit = $_GET['edit'];
                $edit_sql = "SELECT kecamatan, longitude, latitude, luas, jumlah_penduduk FROM penduduk WHERE kecamatan='$kecamatan_to_edit'";
                $edit_result = $conn->query($edit_sql);

                if ($edit_result->num_rows > 0) {
                    $edit_row = $edit_result->fetch_assoc();
                    ?>
                    <h3>Edit Data Kecamatan</h3>
                    <form method="post" action="" class="mt-3">
                        <input type="hidden" name="kecamatan" value="<?php echo $edit_row['kecamatan']; ?>" />
                        <div class="mb-3">
                            <label>Longitude:</label>
                            <input type="text" name="longitude" class="form-control" value="<?php echo $edit_row['longitude']; ?>" />
                        </div>
                        <div class="mb-3">
                            <label>Latitude:</label>
                            <input type="text" name="latitude" class="form-control" value="<?php echo $edit_row['latitude']; ?>" />
                        </div>
                        <div class="mb-3">
                            <label>Luas:</label>
                            <input type="text" name="luas" class="form-control" value="<?php echo $edit_row['luas']; ?>" />
                        </div>
                        <div class="mb-3">
                            <label>Jumlah Penduduk:</label>
                            <input type="text" name="jumlah_penduduk" class="form-control" value="<?php echo $edit_row['jumlah_penduduk']; ?>" />
                        </div>
                        <input type="submit" name="update" value="Update Data" class="btn btn-primary" />
                    </form>
                    <?php
                }
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        var map = L.map("map").setView([-7.7681, 110.2957], 12);

        var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        });

        var Esri_WorldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        });

        var rupabumiindonesia = L.tileLayer('https://geoservices.big.go.id/rbi/rest/services/BASEMAP/Rupabumi_Indonesia/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Badan Informasi Geospasial'
        });

        rupabumiindonesia.addTo(map);
        L.control.scale({ position: "bottomright", imperial: false }).addTo(map);

        // Add markers from database
        <?php
        $result->data_seek(0); // Reset pointer to beginning
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $lat = $row["latitude"];
                $long = $row["longitude"];
                $info = $row["kecamatan"];
                $luas = $row["luas"];
                $jmlPenduduk = $row["jumlah_penduduk"];
                echo "L.marker([$lat, $long]).addTo(map)
                .bindPopup('<b>Kecamatan:</b> $info<br><b>Luas:</b> $luas km²<br><b>Jumlah Penduduk:</b> $jmlPenduduk');\n";
            }
        } else {
            echo "console.log('No data found');";
        }

        ?>
        function confirmDelete(kecamatan) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?delete=' + kecamatan;
                }
            });
        }
    </script>
</body>

</html>
