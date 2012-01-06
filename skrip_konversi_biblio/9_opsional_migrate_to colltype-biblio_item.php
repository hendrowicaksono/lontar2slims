<?php
/* developed by Hendro Wicaksono */
	
	require("db.php");

    $changedgmd['32'] = '4';
    $changedgmd['33'] = '5';
    $changedgmd['34'] = '6';
    $changedgmd['36'] = '7';
    $changedgmd['37'] = '8';
    $changedgmd['39'] = '9';

    mysql_connect ($host, $user, $pass);
    mysql_select_db ($db2);

    foreach ($changedgmd as $key => $value) {
        $gmd = $key;
        $colltype = $value;
        #$sql_get_biblio = "SELECT biblio.biblio_id,biblio.title,biblio.gmd_id FROM biblio,mst_gmd WHERE mst_gmd.gmd_id=biblio.gmd_id AND biblio.gmd_id=$gmd";
        $sql_get_biblio = "SELECT biblio.biblio_id,biblio.title,biblio.gmd_id FROM biblio WHERE biblio.gmd_id=$gmd";
        echo $sql_get_biblio."\n";
        $query_get_biblio = mysql_query ($sql_get_biblio);
        while ($result_get_biblio = mysql_fetch_array ($query_get_biblio)) {
            $sql_update_item = "UPDATE item SET coll_type_id=$colltype WHERE biblio_id=$result_get_biblio[0]";
            echo $sql_update_item."\n";
            $query_update_item = mysql_query ($sql_update_item);
        }
    }


?>