<?php
/* developed by Hendro Wicaksono */
	
	require("db.php");

    mysql_connect ($host, $user, $pass);
    mysql_select_db ($db2);

    $sql1 = 'SELECT biblio.biblio_id,mst_publisher.publisher_name FROM biblio,mst_publisher WHERE biblio.publisher_id=mst_publisher.publisher_id';
    $query1 = mysql_query($sql1);
    
    while ($result1 = mysql_fetch_array($query1)) {
        echo 'Biblio ID --> '.$result1[0]."\n";
        $result1[1] = preg_replace ("/;/i",",", $result1[1]);
        echo 'Publishing Info --> '.$result1[1]."\n";

        if (preg_match("/:/i",$result1[1])) {
            $publisher = explode (":", $result1[1]);
            $result1[1] = NULL;
            foreach ($publisher as $key => $value) {
                if ($key > 0) {
                    $result1[1] .= $value;
                }
            }
            $result1[1] = trim ($result1[1]);
            $kotaterbit = trim ($publisher[0]);
            echo 'Kota Terbit --> '.$kotaterbit."\n";
            $sql_cek_kotaterbit = "SELECT * FROM mst_place WHERE place_name LIKE '%$kotaterbit%'";
            #$sql_cek_kotaterbit = "SELECT * FROM mst_place WHERE place_name='$kotaterbit'";
            $query_cek_kotaterbit = mysql_query ($sql_cek_kotaterbit);
            $numrows_cek_kotaterbit = mysql_num_rows($query_cek_kotaterbit);
            echo 'Kota Terbit --> '.$sql_cek_kotaterbit.' --- '.$numrows_cek_kotaterbit."\n";
            if ($numrows_cek_kotaterbit == 0) {
                $sql_insert_place = "INSERT INTO mst_place (place_name) VALUES ('$kotaterbit')";
                $query_insert_place = mysql_query ($sql_insert_place);
                $place_id = mysql_insert_id ();
                echo $sql_insert_place."\n";
                #$sql_update_place_in_biblio = "UPDATE biblio SET publish_place_id=$place_id WHERE biblio_id=$result1[0]";
            } elseif ($numrows_cek_kotaterbit == 1) {
                 while($res_kotaterbit = mysql_fetch_array($query_cek_kotaterbit)) {
                    $place_id = $res_kotaterbit[0];
                    #$sql_update_place_in_biblio = "UPDATE biblio SET publish_place_id=$place_id WHERE biblio_id=$result1[0]";
                }
            }
            $sql_update_place_in_biblio = "UPDATE biblio SET publish_place_id=$place_id WHERE biblio_id=$result1[0]";
            echo $sql_update_place_in_biblio."\n";
            $query_update_place_in_biblio = mysql_query ($sql_update_place_in_biblio);
            
        } else {
            $kotaterbit = NULL;
            echo 'Kota Terbit --> tidak ditemukan'."\n";
            $sql_update2null_kotaterbit = "UPDATE biblio SET publish_place_id=NULL WHERE biblio_id=$result1[0]";
            $query_update2null_kotaterbit = mysql_query ($sql_update2null_kotaterbit);
            echo 'Kota Terbit --> '.$sql_update2null_kotaterbit."\n";
        }

        $publisher = explode (",", $result1[1]);
        #$penerbit = $publisher[0];

        if (!preg_match ("/^[0-9]/i", $publisher[0])) {
            $penerbit = trim ($publisher[0]);
            #$sql_cek_penerbit = "SELECT * FROM mst_publisher WHERE publisher_name LIKE '%$penerbit%'";
            $sql_cek_penerbit = "SELECT * FROM mst_publisher WHERE publisher_name='$penerbit'";
            $query_cek_penerbit = mysql_query ($sql_cek_penerbit);
            $numrows_cek_penerbit = mysql_num_rows($query_cek_penerbit);

            if ($numrows_cek_penerbit == 0) {
                $sql_insert_penerbit = "INSERT INTO mst_publisher (publisher_name) VALUES ('$penerbit')";
                echo 'DEBUG-INSERT NEW PUBLISHER--> '.$sql_insert_penerbit."\n";
                $query_insert_penerbit = mysql_query ($sql_insert_penerbit);
                $publisher_id = mysql_insert_id ();
                #$sql_update_place_in_biblio = "UPDATE biblio SET publish_place_id=$place_id WHERE biblio_id=$result1[0]";
            } elseif ($numrows_cek_penerbit == 1) {
                while($res_penerbit = mysql_fetch_array($query_cek_penerbit)) {
                    $publisher_id = $res_penerbit[0];
                    #$sql_update_place_in_biblio = "UPDATE biblio SET publish_place_id=$place_id WHERE biblio_id=$result1[0]";
                }
            }
            $sql_update_publisher_in_biblio = "UPDATE biblio SET publisher_id=$publisher_id WHERE biblio_id=$result1[0]";
            $query_update_publisher_in_biblio = mysql_query ($sql_update_publisher_in_biblio);
            echo 'DEBUG-UPDATE PUBLISHER IN BIBLIO-->'.$sql_update_publisher_in_biblio."\n";
        } else {
            $penerbit = NULL;
            $sql_update_publisher_in_biblio = "UPDATE biblio SET publisher_id=NULL WHERE biblio_id=$result1[0]";
            $query_update_publisher_in_biblio = mysql_query ($sql_update_publisher_in_biblio);
            echo 'DEBUG-UPDATE PUBLISHER IN BIBLIO (NULL)-->'.$sql_update_publisher_in_biblio."\n";
        }

        echo 'Penerbit --> '.$penerbit."\n";

        #if ((count($publisher) > 1) AND (preg_match ("/^[0-9][0-9][0-9][0-9]/i", $publisher[1]))) {
        if (count($publisher) > 1) {
            $tahunterbit = trim ($publisher[1]);
            $sql_update_yeardate_in_biblio = "UPDATE biblio SET publish_year='$tahunterbit' WHERE biblio_id=$result1[0]";
            $query_update_yeardate_in_biblio = mysql_query ($sql_update_yeardate_in_biblio);
            echo 'Tahun Terbit --> '.$tahunterbit."\n";
        } else {
            $sql_update_yeardate_in_biblio = "UPDATE biblio SET publish_year=NULL WHERE biblio_id=$result1[0]";
            $query_update_yeardate_in_biblio = mysql_query ($sql_update_yeardate_in_biblio);
            echo 'Tahun Terbit --> Tidak tersedia'."\n";
        }


#        $publisher = explode (":", $result1[1]);
#        $kotaterbit = trim($publisher[0]);
#        echo 'Kota Terbit: '.$kotaterbit."\n";
        #echo 'SISA: '.$result1[1]."\n";
        echo "\n";



    }
?>