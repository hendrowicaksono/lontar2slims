<?php

/* developed by Indra Sutriadi */
	
	require("db.php");
	require("csv.php");

	$kode_field = array(
		'judul' => 4,
		'gmd' => null,
		'edisi' => 13,
		'isbn' => 6,
		'penerbit' => 14,
		'thn' => 29,
		'fisik' => 15,
		'seri' => 16,
		'kolasi' => 3,
		'bahasa' => 7,
		'lokasi' => null,
		'klasifikasi' => null,
		'abstrak' => 18,
		'gambar' => null,
		'file' => null,
		'pengarang' => 5,
		'subyek' => 26,
		'item' => 1,
		'barcode' => 2,
	);

	$def_line = array();
	foreach ($kode_field as $key => $int)
	{
		$def_line[$key] = '';
	}

	$mysqli = @new mysqli($host, $user, $pass, $db);

	if ($mysqli->connect_error)
	{
		die('Connect Error (' . $mysqli->connect_errno . ') '
			. $mysqli->connect_error);
	}

	$query = "SELECT `kode` FROM `koleksi` GROUP BY `kode`";
	$kode = array();
	if ($result = $mysqli->query($query))
	{
		while ($obj = $result->fetch_object())
		{
			$kode[] = $obj->kode;
		}
	}
	$result->close();

	$format = "SELECT `koleksi`.`kode`, `koleksi`.`foto`, `tipekoleksi`.`nama`, `fieldkoleksi`.`kode`, `fielddatakoleksi`.`data`, `fieldkoleksi`.`field` "
		. " FROM `koleksi`, `tipekoleksi`, `fielddatakoleksi`, `fieldkoleksi` "
		. " WHERE `koleksi`.`tipe` = `tipekoleksi`.`kode` "
			. " AND `fielddatakoleksi`.`koleksi` = `koleksi`.`kode` "
			. " AND `fieldkoleksi`.`kode`=`fielddatakoleksi`.`field` "
			. " AND `koleksi`.`kode` = %d";

	foreach ($kode as $i)
	{
		$line = array();
		$query = sprintf($format, $i);
		if ($result = $mysqli->query($query))
		{
			if ($result->num_rows > 0)
			{
				$line = $def_line;
				while ($obj = $result->fetch_object())
				{
					if ($obj->kode == $kode_field['pengarang'])
					{
						$query_pengarang = sprintf("SELECT l.`data` AS `nama` "
							. " FROM `lookuptabledata` l, `lookuptable` l2 "
							. " WHERE l.`lookupTable` = l2.`kode` AND l2.`kode` = 1 AND l.`kode` = %d",
							$obj->data
						);
						if ($result_pengarang = $mysqli->query($query_pengarang))
						{
							if ($result_pengarang->num_rows > 0)
							{
								$obj_pengarang = $result_pengarang->fetch_object();
								$obj->data = $obj_pengarang->nama;
							}
						}
						@$line['pengarang'] .= '<' . $obj->data . '>';
					}
					else if ($obj->kode == $kode_field['item'] || $obj->kode == $kode_field['barcode'])
					{
						$obj->data = '<' . $obj->data . '>';
						if ( ! isset($item_array))
						{
							
							$item_array = array($obj->data);
						}
						else if (is_array($item_array) AND ! in_array($obj->data, $item_array) AND ! empty($obj->data))
							$item_array = array_merge($item_array, array($obj->data));
						$line['item'] = implode('', $item_array);
					}
					else if ($obj->kode == $kode_field['subyek'])
					{
						$obj->data = explode(";", $obj->data);
						$obj->data = implode("><", $obj->data);
						@$line['subyek'] = '<' . $obj->data . '>';
					}
					else
					{
						$line[array_search($obj->kode, $kode_field)] = $obj->data;
					}
					$line['gmd'] = $obj->nama;
					$line['gambar'] = $obj->foto;
				}
				unset($line[0]);
				unset($line['barcode']);
				unset($item_array);
			}
		}
		echo _QT . implode(_QT . _SEP . _QT , $line) . _QT . _NL;
		unset($line);
	}

	$mysqli->close();

?>