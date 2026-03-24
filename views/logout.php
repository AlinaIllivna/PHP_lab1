<?php
session_start();

session_unset();   // очищає змінні
session_destroy(); // знищує сесію

header("Location: index.php");
exit();