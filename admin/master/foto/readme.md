# 📸 Modul Gallery Foto
## Sistem Informasi Sarana dan Prasarana (SISARPRAS) Politeknik NEST

---

## Deskripsi

Modul **Foto** merupakan modul reusable yang digunakan untuk mengelola gallery foto pada beberapa Master Data.

Modul ini digunakan oleh:

- Lokasi
- Ruangan
- Public Space

Sehingga proses upload, penghapusan, perubahan cover, dan pengambilan gallery tidak perlu dibuat berulang pada setiap modul.

---

# Struktur Folder

```
foto/

├── config.php
├── helper.php
├── service.php
├── index.php
├── upload.php
├── cover.php
└── hapus.php
```

---

# Penjelasan File

## config.php

Berisi seluruh konfigurasi upload.

Contoh konfigurasi:

- Maksimal ukuran upload
- Maksimal jumlah foto
- Format file yang diperbolehkan
- Folder upload

---

## helper.php

Berisi fungsi-fungsi umum yang digunakan oleh seluruh file pada modul foto.

Contoh fungsi:

- Menghitung jumlah foto
- Mengambil cover
- Mengambil seluruh gallery
- Validasi upload
- Menghapus file fisik

---

## service.php

Berfungsi sebagai pusat mapping seluruh modul.

File ini menentukan:

- Nama tabel
- Foreign Key
- Folder upload
- Nama Primary Key

berdasarkan parameter:

```
tipe
```

Contoh:

```
lokasi
ruangan
public_space
```

Sehingga file lain tidak perlu mengetahui nama tabel secara langsung.

---

## index.php

Menampilkan gallery foto.

Digunakan sebagai isi Modal Gallery.

Menampilkan:

- Daftar foto
- Cover
- Tombol upload
- Tombol hapus
- Tombol jadikan cover

---

## upload.php

Memproses upload foto.

Melakukan:

- Validasi ukuran file
- Validasi format file
- Validasi jumlah maksimal foto
- Menyimpan file
- Menyimpan data ke database

---

## cover.php

Mengubah status cover.

Proses:

- Menghapus status cover sebelumnya
- Menjadikan foto baru sebagai cover

---

## hapus.php

Menghapus foto.

Proses:

- Validasi apakah foto merupakan cover
- Menghapus file dari folder upload
- Menghapus data dari database

---

# Modul yang Menggunakan

| Modul | Gallery |
|--------|----------|
| Lokasi | ✅ |
| Ruangan | ✅ |
| Public Space | ✅ |
| Inventaris | ❌ |

Inventaris hanya menggunakan satu foto sehingga tidak memakai modul ini.

---

# Parameter

Seluruh file pada modul ini menggunakan parameter berikut.

| Parameter | Keterangan |
|-----------|------------|
| tipe | Jenis modul |
| id | Primary Key data utama |

Contoh:

```
tipe=lokasi&id=5
```

```
tipe=ruangan&id=12
```

```
tipe=public_space&id=4
```

---

# Mapping Modul

| Tipe | Tabel Foto | Foreign Key | Folder Upload |
|------|------------|-------------|---------------|
| lokasi | foto_lokasi | id_lokasi | assets/uploads/lokasi |
| ruangan | foto_ruangan | id_ruangan | assets/uploads/ruangan |
| public_space | foto_public_space | id_public_space | assets/uploads/public_space |

---

# Business Rules

## Upload

- Maksimal 10 foto
- Maksimal ukuran 5 MB
- Format:
    - JPG
    - JPEG
    - PNG
    - WEBP

---

## Cover

- Hanya boleh terdapat 1 cover pada setiap data.
- Upload pertama otomatis menjadi cover.
- Cover tidak dapat dihapus sebelum ada cover pengganti.

---

## Gallery

Gallery diurutkan menggunakan field:

```
urutan
```

secara ascending.

---

# Alur Upload

```
Admin

↓

Klik ikon Gallery

↓

Modal Gallery

↓

Upload Foto

↓

Validasi

↓

Upload Folder

↓

Insert Database

↓

Refresh Gallery
```

---

# Alur Ganti Cover

```
Admin

↓

Klik ikon Bintang

↓

Reset Cover Lama

↓

Set Cover Baru

↓

Refresh Gallery
```

---

# Alur Hapus

```
Admin

↓

Klik Hapus

↓

Validasi Cover

↓

Hapus File

↓

Delete Database

↓

Refresh Gallery
```

---

# Struktur Database

Lokasi

```
lokasi

↓

foto_lokasi
```

Ruangan

```
ruangan

↓

foto_ruangan
```

Public Space

```
public_space

↓

foto_public_space
```

---

# Validasi

Semua validasi dilakukan pada modul ini.

Meliputi:

- Ukuran file
- Format file
- Jumlah maksimal foto
- Cover
- Nama file
- Folder upload

Sehingga seluruh modul master menggunakan aturan yang sama.

---

# Keuntungan Arsitektur

Menggunakan satu modul gallery memberikan beberapa keuntungan:

- Tidak ada duplikasi kode.
- Struktur project lebih rapi.
- Mudah dipelihara.
- Mudah dikembangkan.
- Konsisten pada seluruh modul.
- Perubahan cukup dilakukan pada satu tempat.

---

# Future Development

Modul ini dapat dikembangkan untuk mendukung:

- Drag & Drop urutan gallery
- Multi Upload
- Crop gambar
- Kompres otomatis
- Watermark
- Preview fullscreen
- Lazy Loading
- Image Optimization

Tanpa mengubah struktur folder maupun database.

---

# Author

SISARPRAS Politeknik NEST

Version : 2.0

Architecture :
Reusable Gallery Module

Framework :
PHP Native

Database :
MySQL

UI :
Bootstrap 5 + AdminLTE 4