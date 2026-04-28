

<?php
require_once 'connection.php';
$id = (int)$_GET['id']; // Захист


$userId = $_SESSION['user_id'] ?? null;

// ЛАЙК / ДИЗЛАЙК
if (isset($_GET['like']) && $userId) {
    $productId = (int)$_GET['like'];

    $check = mysqli_query($link,
        "SELECT * FROM likes WHERE user_id=$userId AND product_id=$productId"
    );

    if (mysqli_num_rows($check) > 0) {
        // вже лайкнув → видаляємо
        mysqli_query($link,
            "DELETE FROM likes WHERE user_id=$userId AND product_id=$productId"
        );
    } else {
        // ще не лайкнув → додаємо
        mysqli_query($link,
            "INSERT INTO likes (user_id, product_id) VALUES ($userId, $productId)"
        );
    }

    header("Location: index.php?action=view_product&id=$productId");
    exit();
}

// ПІДРАХУНОК ЛАЙКІВ
$count = mysqli_fetch_row(mysqli_query($link,
    "SELECT COUNT(*) FROM likes WHERE product_id=$id"
))[0];

// ЧИ ЛАЙКНУВ КОРИСТУВАЧ
$liked = false;

if ($userId) {
    $res = mysqli_query($link,
        "SELECT * FROM likes WHERE user_id=$userId AND product_id=$id"
    );
    $liked = mysqli_num_rows($res) > 0;
}

if (isset($_GET['delete_comment']) && isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
    $commentId = (int)$_GET['delete_comment'];

    mysqli_query($link, "DELETE FROM comments WHERE id = $commentId");

    header("Location: index.php?action=view_product&id=$id");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['user_id'])) {
    $text = mysqli_real_escape_string($link, $_POST['comment']);
    $userId = $_SESSION['user_id'];

    mysqli_query($link, "
        INSERT INTO comments (product_id, user_id, text)
        VALUES ($id, $userId, '$text')
    ");

    // щоб не дублювалося при перезавантаженні
    header("Location: index.php?action=view_product&id=$id");
    exit();
}

$sql = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($link, $sql);
$product = mysqli_fetch_assoc($result);

// Перевірка на існування
if (!$product) {
    die("<h2>Такої сторінки не існує</h2><a href='index.php?action=products'>Назад до списку</a>");
}

// Перевірка видимості (якщо не адмін і товар прихований — не показувати)
if ($product['visible'] == 0 && (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1)) {
    die("<h2>Цей товар наразі недоступний для перегляду</h2>");
}
?>

<div class="product-view">
    <a href="index.php?action=products" >⬅ Назад до списку</a>
    
    <div class="view-content" style="display: flex; gap: 30px; margin-top: 20px;">
        <div class="view-image" style="flex: 1; max-width: 500px;">
    <?php if (!empty($product['image'])): ?>
        <img src="<?= $product['image'] ?>" style="width: 100%; max-height: 500px; object-fit: contain; border-radius: 10px;">
    <?php else: ?>
        <img src="img/no-photo.png" style="width: 100%;">
    <?php endif; ?>
</div>
        <div class="view-details" style="flex: 1;">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <p class="price" style="font-size: 24px; color: green; font-weight: bold;">
                <?= $product['price'] ?> грн
            </p>
            <p><strong>Опис:</strong></p>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <hr>
            <p><small>Додано: <?= $product['date'] ?></small></p>
            
            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1): ?>
                <div style="background: #eee; padding: 10px; border-radius: 5px;">
                    <p><strong>Інформація для адміна:</strong></p>
                    <p>Статус: <?= $product['visible'] ? "Публічний" : "Прихований" ?></p>
                    <p>ID автора: <?= $product['author_id'] ?></p>
                    <a href="index.php?action=update_product&id=<?= $product['id'] ?>" class="btn-edit">Редагувати дані</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<!-- кнопка лайка  -->
<div class="like-block">
    <?php if ($userId): ?>
        <a href="index.php?action=view_product&id=<?= $id ?>&like=<?= $id ?>"
           class="like-btn <?= $liked ? 'liked' : 'not-liked' ?>">
           
        <?= $liked ? "💔 Не подобається" : "❤️ Подобається" ?>
        </a>
    <?php else: ?>
        <span>Увійдіть, щоб поставити лайк</span>
    <?php endif; ?>

    <span class="like-count">
        ❤️ <?= $count ?>
    </span>
</div>

<!-- КОМЕНТАРІ -->
<hr>

<div class="comments-block">
    <h3>Коментарі</h3>

    <?php if (isset($_SESSION['user_id'])): ?>
        <form method="POST" class="comment-form">
            <textarea name="comment" required placeholder="Напишіть коментар..."></textarea>
            <button type="submit">Додати</button>
        </form>
    <?php else: ?>
        <p>Щоб залишити коментар — увійдіть у систему</p>
    <?php endif; ?>

    <?php
    $sql = "SELECT c.*, u.login 
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.product_id = $id
            ORDER BY c.created_at DESC";

    $result = mysqli_query($link, $sql);

    while ($comment = mysqli_fetch_assoc($result)):
    ?>

        <div class="comment">
            <div class="comment-header">
                <span class="comment-user"><?= htmlspecialchars($comment['login']) ?></span>
                <span class="comment-date"><?= $comment['created_at'] ?></span>
            </div>
            <div class="comment-text">
                <?= nl2br(htmlspecialchars($comment['text'])) ?>
            </div>
            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1): ?>
    <a href="index.php?action=view_product&id=<?= $id ?>&delete_comment=<?= $comment['id'] ?>"
       style="color:red; font-size:12px;">
       Видалити
    </a>
<?php endif; ?>
        </div>

    <?php endwhile; ?>
</div>