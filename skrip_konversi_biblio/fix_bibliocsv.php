<?php 

$handle = @fopen("biblio.csv", "r");
$switch = 0;
$tmpbuffermain = '';
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
        $buffer = trim ($buffer);

        #if (((preg_match("/^"/i", $buffer)) AND ($switch == 1)) {
        if ((preg_match('/^"\w/i', $buffer)) AND ($switch == 1)) {
            echo $tmpbuffermain."\n";
            $tmpbuffermain = '';
            $switch = 0;
        }
        
        if (preg_match('/^"\w/i', $buffer)) {
            $tmpbuffermain .= $buffer;
            $switch = 1;
        } else {
            $tmpbuffermain .= $buffer;
        }

        #echo $buffer;
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}


?>