<?php
	
	require("db.php");

    mysql_connect ($host, $user, $pass);
    mysql_select_db ($db2);

    $sql1 = 'SELECT call_number,biblio_id FROM biblio';
    $query = mysql_query($sql1);
    
    while ($result1 = mysql_fetch_array($query)) {
        $classnum = '';
        echo 'Call Number: '.$result1[0]."\n";
        $class = explode (" ", $result1[0]);

        # classnum builder
        $classnum = $class[0];
        if (count($class) > 1) {
            if (preg_match("/^[0-9]/i",$class[1])) {
                #echo 'Classification: '.$class[0].' '.$class[1]."\n";
                $classnum .= ' '.$class[1];
            }
        }

        if (count($class) > 2) {
            if (preg_match("/^[0-9]/i",$class[2])) {
                #echo 'Classification: '.$class[0].' '.$class[1]."\n";
                $classnum .= ' '.$class[2];
            }
        }

        $classnum = trim ($classnum);
        echo 'Classification: '.$classnum."\n";
        echo "SQL statement: UPDATE biblio SET classification='$classnum' WHERE biblio_id='$result1[1]'\n";

        $sql2 = "UPDATE biblio SET classification='$classnum' WHERE biblio_id='$result1[1]'";        
        mysql_query($sql2);

    }
?>