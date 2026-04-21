<?php
$id = (int)$_GET['id']; // Захист

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