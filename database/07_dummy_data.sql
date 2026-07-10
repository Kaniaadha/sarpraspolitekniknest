/*
=================================================
DUMMY DATA
SISARPRAS POLITEKNIK NEST

File : 07_dummy_data.sql
=================================================
*/

USE db_sisarpras;


INSERT INTO lokasi
(kode_lokasi, nama_lokasi, alamat, deskripsi, status)
VALUES
('LOC001','Basement','Gedung POLINEST','Area Basement','Aktif'),
('LOC002','Gedung A','Gedung POLINEST','Gedung A','Aktif'),
('LOC003','Gedung B','Gedung POLINEST','Gedung B','Aktif');

INSERT INTO lantai
(id_lokasi, kode_lantai, nama_lantai, nomor_lantai, deskripsi, status)
VALUES
(1,'LNT001','Basement',0,'Area Basement','Aktif'),

(2,'LNT002','Lantai Dasar',0,'Gedung A','Aktif'),
(2,'LNT003','Lantai 1',1,'Gedung A','Aktif'),
(2,'LNT004','Lantai 2',2,'Gedung A','Aktif'),

(3,'LNT005','Lantai Dasar',0,'Gedung B','Aktif'),
(3,'LNT006','Lantai 1',1,'Gedung B','Aktif'),
(3,'LNT007','Lantai 2',2,'Gedung B','Aktif');

INSERT INTO ruangan
(id_lantai, kode_ruangan, nama_ruangan, luas, kapasitas, deskripsi, status)
VALUES

(1,'RNG001','Ruang Dosen',120,20,'Ruang dosen basement','Aktif'),
(1,'RNG002','Mushola Putra',50,30,'Mushola','Aktif'),
(1,'RNG003','Mushola Putri',50,30,'Mushola','Aktif'),
(1,'RNG004','Ruang Security',30,6,'Pos Security','Aktif'),
(1,'RNG005','Lab Praktikum Pastry',200,40,'Lab Praktikum','Aktif'),

(2,'RNG006','Rasa Nusa Restaurant',180,80,'Teaching Restaurant','Aktif'),
(2,'RNG007','Hot Kitchen',150,25,'Kitchen','Aktif'),
(2,'RNG008','Guest Room',40,10,'Ruang tamu','Aktif'),

(3,'RNG009','Receptionist Hotel',60,10,'Reception Hotel','Aktif'),
(3,'RNG010','Hotel Room 101',35,2,'Kamar Hotel','Aktif');

INSERT INTO public_space
(id_lokasi, kode_public_space, nama_public_space, luas, deskripsi, status)
VALUES
(1,'PS001','Parkir Motor Basement',400,'Area parkir motor','Aktif'),
(1,'PS002','Parkir Mobil Basement',500,'Area parkir mobil','Aktif'),
(1,'PS003','Lapangan Padel',350,'Lapangan olahraga','Aktif'),
(1,'PS004','Foodcourt',250,'Area makan','Aktif'),
(2,'PS005','Lobby POLINEST',180,'Lobby utama','Aktif'),
(2,'PS006','Area Expo Mahasiswa',200,'Area pameran','Aktif'),
(3,'PS007','Lobby Gedung B',150,'Lobby Gedung B','Aktif');