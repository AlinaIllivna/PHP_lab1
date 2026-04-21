<div class="cabinet">
    <h2>Особистий кабінет</h2>

    <div class="user-info">
        <p class="cabinet-user">
            Ви увійшли як: <b><?= htmlspecialchars($_SESSION["login"]) ?></b>
        </p>

        <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] == 1): ?>
            <p class="status-badge admin">Статус: <b>Адміністратор</b></p>
            <p class="admin-note">У вас є права на керування товарами (редагування та видалення).</p>
        <?php else: ?>
            <p class="status-badge user">Статус: <b>Користувач</b></p>
            <p class="user-note">Ви можете додавати нові товари, які з'являться на сайті після перевірки адміном.</p>
        <?php endif; ?>
    </div>

    <hr>

    <div class="cabinet-actions">
        <h3>Швидкі дії:</h3>
        <ul>
            <li><a href="index.php?action=create_product">Додати новий товар</a></li>
            <?php if ($_SESSION["admin"] == 1): ?>
                <li><a href="index.php?action=products">Керувати всіма товарами</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <form method="POST" action="index.php?action=logout" style="margin-top: 20px;">
        <button class="logout-btn" type="submit">Вийти з акаунту</button>
    </form>
</div>