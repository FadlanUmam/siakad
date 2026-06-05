<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">

    <!-- Judul halaman browser -->
    <title>Cetak KHS - <?php echo htmlspecialchars($mahasiswa['nim']); ?></title>

    <!-- Memanggil CSS SB Admin -->
    <link href="<?php echo base_url('assets/start/css/sb-admin-2.min.css'); ?>" rel="stylesheet">

    <style>
        /* Pengaturan tampilan dasar halaman */
        body {
            background-color: #fff;
            color: #000;
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
        }

        /* Container utama KHS */
        .khs-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        /* Kop surat */
        .header-kop {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        /* Pengaturan saat dicetak */
        @media print {
            .no-print {
                display: none !important;
            }

            tr {
                page-break-inside: avoid;
            }
        }

        /* Judul KHS */
        .khs-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }

        /* Informasi mahasiswa */
        .student-info {
            width: 100%;
            margin-bottom: 25px;
            font-size: 11pt;
            line-height: 1.6;
        }

        .student-info td {
            padding: 4px 0;
            vertical-align: top;
        }

        .student-info td:first-child {
            width: 22%;
        }

        .student-info td:nth-child(2) {
            width: 3%;
        }

        /* Tabel nilai */
        .table-nilai {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 11pt;
        }

        .table-nilai th,
        .table-nilai td {
            border: 1px solid #000;
            padding: 8px 10px;
        }

        .table-nilai th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        /* Pengaturan rata tengah beberapa kolom */
        .table-nilai td:nth-child(1),
        .table-nilai td:nth-child(2),
        .table-nilai td:nth-child(4),
        .table-nilai td:nth-child(5),
        .table-nilai td:nth-child(6),
        .table-nilai th {
            text-align: center;
        }

        /* Area tanda tangan */
        .signature-area {
            width: 100%;
            margin-top: 40px;
            font-size: 11pt;
        }

        .signature-area td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        /* Memberi ruang kosong untuk tanda tangan */
        .space-sig {
            height: 85px;
        }
    </style>
</head>

<body>

    <!-- Tombol cetak dan tutup halaman (tidak ikut tercetak) -->
    <div class="container text-center my-3 no-print">

        <!-- Tombol cetak -->
        <button onclick="window.print()" class="btn btn-primary btn-sm px-4 shadow">
            <i class="fas fa-print"></i> Klik Di Sini untuk Cetak / Save ke PDF
        </button>

        <!-- Tombol tutup halaman -->
        <button onclick="window.close()" class="btn btn-secondary btn-sm ml-2">
            Tutup Halaman
        </button>

        <hr>
    </div>

    <div class="khs-container">

        <!-- ========================= -->
        <!-- KOP SURAT UNIVERSITAS -->
        <!-- ========================= -->
        <table class="w-100 header-kop">
            <tr>

                <!-- Logo universitas -->
                <td style="width: 15%; text-align: center;">
                    <i class="fas fa-university fa-4x text-gray-900"></i>
                </td>

                <!-- Informasi universitas -->
                <td style="width: 85%; text-align: center;">
                    <h2>KEMENTERIAN PENDIDIKAN ILMU PENGETAHUAN DAN TEKNOLOGI</h2>
                    <h2 class="font-weight-bold">UNIVERSITAS HORIZON INDONESIA</h2>
                    <p>Fakultas Informatika - Program Studi Informatika</p>
                    <p>Jl. Raya DEPAN GANG No.99</p>
                </td>
            </tr>
        </table>

        <!-- Judul dokumen -->
        <div class="khs-title">
            KARTU HASIL STUDI (KHS)
        </div>

        <!-- ========================= -->
        <!-- DATA MAHASISWA -->
        <!-- ========================= -->
        <table class="student-info">
            <tr>
                <td>Nama Mahasiswa</td>
                <td>:</td>

                <!-- Menampilkan nama mahasiswa -->
                <td><?php echo htmlspecialchars($mahasiswa['nama']); ?></td>
            </tr>

            <tr>
                <td>NIM</td>
                <td>:</td>

                <!-- Menampilkan NIM -->
                <td><?php echo htmlspecialchars($mahasiswa['nim']); ?></td>
            </tr>

            <tr>
                <td>Program Studi</td>
                <td>:</td>

                <!-- Menampilkan jurusan/prodi -->
                <td><?php echo htmlspecialchars($mahasiswa['jurusan']); ?></td>
            </tr>

            <tr>
                <td>Semester / Angkatan</td>
                <td>:</td>

                <!-- Menampilkan semester yang dipilih -->
                <td>Semester <?php echo (int)$selected_semester; ?></td>
            </tr>
        </table>

        <!-- ========================= -->
        <!-- TABEL NILAI -->
        <!-- ========================= -->
        <table class="table-nilai">

            <!-- Header tabel -->
            <thead>
                <tr>
                    <th>NO</th>
                    <th>KODE MK</th>
                    <th>MATA KULIAH</th>
                    <th>SKS</th>
                    <th>NILAI</th>
                    <th>BOBOT x SKS</th>
                </tr>
            </thead>

            <tbody>

                <!-- Jika data nilai tersedia -->
                <?php if (!empty($khs_list)) : ?>

                    <?php
                    $no = 1;

                    // Perulangan untuk menampilkan semua mata kuliah
                    foreach ($khs_list as $khs) :
                    ?>

                        <tr>

                            <!-- Nomor urut -->
                            <td><?php echo $no++; ?></td>

                            <!-- Kode mata kuliah -->
                            <td><?php echo htmlspecialchars($khs['kode_mk']); ?></td>

                            <!-- Nama mata kuliah -->
                            <td><?php echo htmlspecialchars($khs['nama_mk']); ?></td>

                            <!-- Jumlah SKS -->
                            <td><?php echo (int)$khs['sks']; ?></td>

                            <!-- Nilai huruf -->
                            <td><?php echo htmlspecialchars($khs['nilai_huruf']); ?></td>

                            <!-- Nilai mutu -->
                            <td><?php echo number_format($khs['nilai_mutu'], 2); ?></td>
                        </tr>

                    <?php endforeach; ?>

                <?php else : ?>

                    <!-- Jika belum ada nilai -->
                    <tr>
                        <td colspan="6" class="text-center">
                            Belum ada data nilai di semester ini.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

            <!-- Footer tabel -->
            <tfoot>

                <!-- Total SKS semester -->
                <tr>
                    <th colspan="3" class="text-right">
                        TOTAL SKS SEMESTER :
                    </th>

                    <th>
                        <?php echo $total_sks; ?> SKS
                    </th>
                </tr>

                <!-- IPS -->
                <tr>
                    <th colspan="3" class="text-right">
                        INDEKS PRESTASI SEMESTER (IPS)
                    </th>

                    <th colspan="3">
                        IPS = <?php echo $ip; ?>
                    </th>
                </tr>

                <!-- Total SKS kumulatif -->
                <tr>
                    <th colspan="3" class="text-right">
                        TOTAL SKS KUMULATIF
                    </th>

                    <th>
                        <?php echo $total_sks_kumulatif; ?> SKS
                    </th>
                </tr>

                <!-- IPK -->
                <tr>
                    <th colspan="3" class="text-right">
                        INDEKS PRESTASI KUMULATIF (IPK)
                    </th>

                    <th colspan="3">
                        IPK = <?php echo $ipk; ?>
                    </th>
                </tr>

            </tfoot>

        </table>

        <!-- ========================= -->
        <!-- TANDA TANGAN -->
        <!-- ========================= -->
        <table class="signature-area">
            <tr>

                <!-- Ketua Program Studi -->
                <td>
                    <p>Ketua Program Studi</p>

                    <!-- Ruang tanda tangan -->
                    <div class="space-sig"></div>

                    <p>Dr. Ir. Prasetio, M.T.</p>
                </td>

                <!-- Dosen Wali -->
                <td>
                    <p>Karawang, <?php echo date('d F Y'); ?></p>
                    <p>Dosen Wali Akademik</p>

                    <!-- Ruang tanda tangan -->
                    <div class="space-sig"></div>

                    <p>Rijal, M.Kom.</p>
                </td>

            </tr>
        </table>

    </div>

    <script>

        // Ketika halaman selesai dimuat
        window.onload = function() {

            // Otomatis membuka dialog print setelah 0.5 detik
            setTimeout(function() {
                window.print();
            }, 500);

        }

    </script>

</body>
</html>
