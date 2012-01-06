<?php
	
	require("db.php");
	require("csv.php");

	$kode_field = array(
		'member' => 1,
		'nama' => 2,
		'jk' => null,
		'tipe' => null,
		'email' => 14,
		'alamat' => 3,
		'kodepos' => 7,
		'instansi' => 10,
		'unknown' => null,
		'foto' => null,
		'pin' => null,
		'hp' => null,
		'fax' => null,
		'reg' => null,
		'since' => null,
		'exp' => null,
		'ultah' => null,
		'note' => 11,
		'alamat_kos' => 4,
		'fakultas' => 12,
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

	$query = "SELECT `kode` FROM `anggota` GROUP BY `kode`";
	$kode = array();
	if ($result = $mysqli->query($query))
	{
		while ($obj = $result->fetch_object())
		{
			$kode[] = $obj->kode;
		}
	}
	$result->close();

	$format = "SELECT `grupanggota`.`nama` AS `grup`, `foto`, "
		. " DATE_FORMAT(`tglexp`, '%s') AS `tglexp`, DATE_FORMAT(`tglupdate`, '%s') AS `tglupdate`, "
		. " `fieldanggota`.`kode`, `fielddataanggota`.`data`, `fieldanggota`.`field` "
		. " FROM `anggota`, `fielddataanggota`, `fieldanggota`, `grupanggota` "
		. " WHERE `fielddataanggota`.`anggota` = `anggota`.`kode` "
			. " AND `fieldanggota`.`kode`=`fielddataanggota`.`field` "
			. " AND `grupanggota`.`kode` = `anggota`.`grup`"
			. " AND `anggota`.`kode` = %d ";

	foreach ($kode as $i)
	{
		$line = array();
		$query = sprintf($format, '%Y-%m-%d', '%Y-%m-%d', $i);
		if ($result = $mysqli->query($query))
		{
			if ($result->num_rows > 0)
			{
				$line = $def_line;
				while ($obj = $result->fetch_object())
				{
					if ($obj->kode == $kode_field['fakultas'] || $obj->kode == $kode_field['instansi'])
					{
						if ($obj->kode == $kode_field['fakultas'])
						{
							$query_fak = sprintf("SELECT l.`data` AS `fak` "
								. " FROM `lookuptabledata` l, `lookuptable` l2 "
								. " WHERE l.`lookupTable` = l2.`kode` AND l2.`kode` = 2 AND l.`kode` = %d",
								$obj->data
							);
							if ($result_fak = $mysqli->query($query_fak))
							{
								if ($result_fak->num_rows > 0)
								{
									$obj_fak = $result_fak->fetch_object();
									$obj->data = ' Fakultas: ' . $obj_fak->fak . '.';
								}
							}
						}
						else
						{
							$obj->data = ' Jurusan/Prodi: ' . $obj->data . '.';
						}
						$line['instansi'] .= $obj->data;
					}
					$line[array_search($obj->kode, $kode_field)] = $obj->data;
					$line['tipe'] = $obj->grup;
					$line['reg'] = $obj->tglupdate;
					$line['exp'] = $obj->tglexp;
					$line['foto'] = $obj->foto;
				}
				if (empty($line['alamat']) AND ! empty($line['alamat_kos']))
					$line['alamat'] = $line['alamat_kos'];
				$line['since'] = trim($line['reg']);
				$line['jk'] = 0;
				$line['instansi'] = trim($line['instansi']);
				unset($line[0]);
				unset($line['alamat_kos']);
				unset($line['fakultas']);
			}
		}
		echo _QT . implode(_QT . _SEP . _QT , $line) . _QT . _NL;
		unset($line);
	}

	$mysqli->close();

?>