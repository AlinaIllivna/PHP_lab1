<?php

include("layout/header.php");

echo '<div class="page">';

include("layout/left_menu.php");

echo '<main class="content">';

$action = $_GET["action"] ?? "main";

if(!file_exists("views/$action.php")){
    $action = "main";
}

include("views/$action.php");

echo '</main>';

echo '</div>';

include("layout/footer.php");

?>