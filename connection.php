<?php
$link = mysqli_connect("localhost", "root", "", "smak_opto");

if (!$link) {
    die("Помилка підключення: " . mysqli_connect_error());
}
?>