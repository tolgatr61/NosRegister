<?php
require_once("config.php");
$params = array("fmohican");
$sql = "SELECT * FROM Account WHERE Name = ?";
$opts = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = sqlsrv_query($mssql, $sql, $params, $opts);
$obj = sqlsrv_fetch_object($result);
    echo $obj->Name;
?>