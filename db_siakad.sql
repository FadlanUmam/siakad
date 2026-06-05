
-- AKUN LOGIN :
--   Username : superadmin
--   Password : admin123

-- akun login mahasiswa:
--   Username : 220101001
--   Password : mahasiswa123


CREATE DATABASE IF NOT EXISTS db_Siakad;
USE db_Siakad;

-- TABEL 1: Mahasiswa (Data Mahasiswa)
CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    jurusan VARCHAR(100) NOT NULL,
    foto VARCHAR(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- TABEL 2: Mata Kuliah (Data Mata Kuliah)

CREATE TABLE IF NOT EXISTS matakuliah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_mk VARCHAR(10) UNIQUE NOT NULL,
    nama_mk VARCHAR(100) NOT NULL,
    sks INT NOT NULL,
    semester INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- TABEL 3: Nilai Akhir (Data Nilai Mahasiswa)

CREATE TABLE IF NOT EXISTS nilai (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    matakuliah_id INT NOT NULL,
    nilai_angka FLOAT NOT NULL,
    nilai_huruf VARCHAR(2) NOT NULL,
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE,
    FOREIGN KEY (matakuliah_id) REFERENCES matakuliah(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- TABEL 4: Users (Akun Admin & SuperAdmin untuk Login)

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('superadmin', 'admin', 'mahasiswa') NOT NULL DEFAULT 'admin',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- TABEL 5: Audit Log Nilai (History perubahan nilai)

CREATE TABLE IF NOT EXISTS nilai_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nilai_id INT NOT NULL,
    user_id INT NOT NULL,
    aksi ENUM('insert', 'update', 'delete') NOT NULL,
    nilai_angka_lama FLOAT DEFAULT NULL,
    nilai_huruf_lama VARCHAR(2) DEFAULT NULL,
    nilai_angka_baru FLOAT DEFAULT NULL,
    nilai_huruf_baru VARCHAR(2) DEFAULT NULL,
    mahasiswa_nama VARCHAR(100) DEFAULT NULL,
    matakuliah_nama VARCHAR(100) DEFAULT NULL,
    keterangan TEXT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Akun SuperAdmin
-- Username: superadmin | Password: admin123


INSERT IGNORE INTO users (id, username, password, nama_lengkap, role) VALUES
(1, 'superadmin', '$2y$10$LxOnkW3lsTd4mJ.AR3TVJuOMQsgPMjMoxUZBaZu2A.My6.jDAq/QW', 'Super Administrator', 'superadmin'),
(2, '220101001', '$2y$10$q.KPgdMMhYMcD8hp/yFHsuruDARuVvi1eRRncxKBz3Tt3tn2dY7Te', 'Ahmad Dani', 'mahasiswa'),
(3, '220101002', '$2y$10$q.KPgdMMhYMcD8hp/yFHsuruDARuVvi1eRRncxKBz3Tt3tn2dY7Te', 'Budi Santoso', 'mahasiswa'),
(4, '220101003', '$2y$10$q.KPgdMMhYMcD8hp/yFHsuruDARuVvi1eRRncxKBz3Tt3tn2dY7Te', 'Citra Lestari', 'mahasiswa');


-- DATA AWAL: Dummy Data Mahasiswa

INSERT IGNORE INTO mahasiswa (id, nim, nama, jurusan, foto) VALUES
(1, '220101001', 'Ahmad Dani', 'Teknik Informatika', 'default.jpg'),
(2, '220101002', 'Budi Santoso', 'Sistem Informasi', 'default.jpg'),
(3, '220101003', 'Citra Lestari', 'Teknik Informatika', 'default.jpg');

-- DATA AWAL: Dummy Data Mata Kuliah

INSERT IGNORE INTO matakuliah (id, kode_mk, nama_mk, sks, semester) VALUES
(1, 'INF-101', 'Pemrograman Web', 3, 2),
(2, 'INF-102', 'Basis Data', 4, 2),
(3, 'INF-103', 'Struktur Data', 3, 2),
(4, 'INF-201', 'Pemrograman Berorientasi Objek', 3, 3),
(5, 'INF-202', 'Jaringan Komputer', 3, 3);


-- DATA AWAL: Dummy Data Nilai

INSERT IGNORE INTO nilai (id, mahasiswa_id, matakuliah_id, nilai_angka, nilai_huruf) VALUES
(1, 1, 1, 85, 'A'),
(2, 1, 2, 78, 'B'),
(3, 1, 3, 90, 'A'),
(4, 2, 1, 70, 'B'),
(5, 2, 2, 60, 'C'),
(6, 2, 3, 80, 'A');
