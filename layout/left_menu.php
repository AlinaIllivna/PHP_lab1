<nav>

<a href="index.php">Головна</a>

<a href="index.php?action=about">Про сайт</a>

<a href="index.php?action=products">Товари</a>

<?php if (isset($_SESSION["login"])): ?>
    <li><a href="index.php?action=cabinet">Кабінет</a></li>
<?php else: ?>
    <li><a href="index.php?action=login">Увійти</a></li>

<?php endif; ?>



</nav>