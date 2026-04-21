<?php
$link = mysqli_connect("localhost", "root", "", "smak_opto");

if (!$link) {
    die("Помилка підключення: " . mysqli_connect_error());

}

// Встановлюємо кодування UTF-8
mysqli_set_charset($link, "utf8mb4");
?>