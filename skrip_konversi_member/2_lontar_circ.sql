INSERT INTO `to`.`loan` (`item_code`, `member_id`, `loan_date`, `due_date`, `is_lent`, `is_return`, `return_date`) (SELECT `koleksi`, fda.`data`, `tglpinjam`, `tglKembali`, 1, IF(`tgldikembalikan` IS NOT NULL, 1, 0), `tgldikembalikan` FROM `from`.`peminjaman` p, `from`.`fielddataanggota` fda WHERE fda.field = 1 AND fda.`anggota` =  p.`anggota`);
