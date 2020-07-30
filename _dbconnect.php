<?php
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "users_data";


    $connect = mysqli_connect($server, $username, $password, $dbname);

    if(!$connect){
        die("Unable to connect");
    }
?>