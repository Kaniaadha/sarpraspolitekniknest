/*
=================================================
FOREIGN KEY
SISARPRAS POLITEKNIK NEST

File : 06_foreign_key.sql
=================================================
*/

USE db_sisarpras;

ALTER TABLE lantai
ADD CONSTRAINT fk_lantai_lokasi
FOREIGN KEY (id_lokasi)
REFERENCES lokasi(id_lokasi)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE ruangan
ADD CONSTRAINT fk_ruangan_lantai
FOREIGN KEY (id_lantai)
REFERENCES lantai(id_lantai)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE public_space
ADD CONSTRAINT fk_publicspace_lantai
FOREIGN KEY (id_lantai)
REFERENCES lantai(id_lantai)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE inventaris
ADD CONSTRAINT fk_inventaris_kategori
FOREIGN KEY (id_kategori)
REFERENCES kategori(id_kategori)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE inventaris
ADD CONSTRAINT fk_inventaris_ruangan
FOREIGN KEY (id_ruangan)
REFERENCES ruangan(id_ruangan)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE inventaris
ADD CONSTRAINT fk_inventaris_publicspace
FOREIGN KEY (id_public_space)
REFERENCES public_space(id_public_space)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE foto_lokasi
ADD CONSTRAINT fk_fotolokasi_lokasi
FOREIGN KEY (id_lokasi)
REFERENCES lokasi(id_lokasi)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE foto_ruangan
ADD CONSTRAINT fk_fotoruangan_ruangan
FOREIGN KEY (id_ruangan)
REFERENCES ruangan(id_ruangan)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE foto_public_space
ADD CONSTRAINT fk_fotopublicspace
FOREIGN KEY (id_public_space)
REFERENCES public_space(id_public_space)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE foto_inventaris
ADD CONSTRAINT fk_fotoinventaris
FOREIGN KEY (id_inventaris)
REFERENCES inventaris(id_inventaris)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE detail_peminjaman
ADD CONSTRAINT fk_detail_peminjaman
FOREIGN KEY (id_peminjaman)
REFERENCES peminjaman(id_peminjaman)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE detail_peminjaman
ADD CONSTRAINT fk_detail_inventaris
FOREIGN KEY (id_inventaris)
REFERENCES inventaris(id_inventaris)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE activity_log
ADD CONSTRAINT fk_activity_admin
FOREIGN KEY (id_admin)
REFERENCES admin(id_admin)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE banner
ADD CONSTRAINT fk_banner_admin
FOREIGN KEY (id_admin)
REFERENCES admin(id_admin)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE peminjaman
ADD CONSTRAINT fk_peminjaman_admin
FOREIGN KEY (id_admin)
REFERENCES admin(id_admin)
ON UPDATE CASCADE
ON DELETE RESTRICT;

