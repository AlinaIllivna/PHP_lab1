<div class="cabinet">

    <h2>Кабінет</h2>

    <p class="cabinet-user">
        Ви увійшли як: <b><?= $_SESSION["user"] ?></b>
    </p>

    <form method="POST" action="index.php?action=logout">
        <button class="logout-btn" type="submit">Вийти</button>
    </form>

</div>