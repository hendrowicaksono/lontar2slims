<?php 
/* developed by Hendro Wicaksono */

$handle = @fopen("member-v2.csv", "r");
$switch = 0;
$tmpbuffermain = '';
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
        $buffer = trim ($buffer);
        if (!preg_match('/^""/i', $buffer)) {
            echo $buffer."\n";
        }
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}


?>