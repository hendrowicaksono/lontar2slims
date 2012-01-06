<?php
/* developed by Hendro Wicaksono */
	
	require("db.php");

    mysql_connect ($host, $user, $pass);
    mysql_select_db ($db2);

    $sql_get_orphaned_publisher = 'SELECT mst_publisher.publisher_id,mst_publisher.publisher_name, biblio.title FROM mst_publisher LEFT JOIN biblio ON mst_publisher.publisher_id = biblio.publisher_id WHERE biblio.title IS NULL LIMIT 0,100';
    $query_get_orphaned_publisher = mysql_query($sql_get_orphaned_publisher);
    $orphaned_publisher = mysql_num_rows($query_get_orphaned_publisher);
    if ($orphaned_publisher > 0) {
        while ($result_get_orphaned_publisher = mysql_fetch_array($query_get_orphaned_publisher)) {
            $sql_del_orphaned_publisher = "DELETE FROM mst_publisher WHERE mst_publisher.publisher_id=$result_get_orphaned_publisher[0]";
            $query_del_orphaned_publisher = mysql_query ($sql_del_orphaned_publisher);
            echo $sql_del_orphaned_publisher."\n";
        }
    }
    

?>


