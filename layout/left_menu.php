<nav>

<a href="index.php">Головна</a>

<a href="index.php?action=about">Про сайт</a>

<?php if (isset($_SESSION["user"])): ?>
    <li><a href="index.php?action=cabinet">Кабінет</a></li>
<?php else: ?>
    <li><a href="index.php?action=registration">Реєстрація</a></li>
<?php endif; ?>



</nav>