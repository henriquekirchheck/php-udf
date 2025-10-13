<?php

function open_db(): mysqli
{
    $conn = new mysqli('127.0.0.1', 'admin', 'admin', 'app', 3_306);
    if ($conn->connect_error) {
        exit('DB Connection Failed: '.$conn->connect_error);
    }

    return $conn;
}

function close_db(mysqli $conn)
{
    $conn->close();
}
