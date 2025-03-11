<?php
    $conn = new mysqli("localhost", "root", "", "smj_furnishings");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    ?>