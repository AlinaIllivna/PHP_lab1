<?php
$is_admin = (isset($_SESSION['admin']) && $_SESSION['admin'] == 1);

// Запит: адмін бачить все, користувач — тільки visible=1
if ($is_admin) {
    $sql = "SELECT * FROM products ORDER BY date DESC";
} else {
    $sql = "SELECT * FROM products WHERE visible = 1 ORDER BY date DESC";
}

$result = mysqli_query($link, $sql);
?>

<div class="products-section">
    <div class="products-header">
        <h2>Наші товари</h2>
        <?php if (isset($_SESSION['login'])): ?>
            <a href="index.php?action=create_product" class="btn-add-main">+ Додати товар</a>
        <?php endif; ?>
    </div>

    <div class="products-grid">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <div class="product-card <?= ($row['visible'] == 0) ? 'draft-card' : '' ?>">
        
        <div class="product-img-container">
            <img src="<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['name']) ?>">
            
            <?php if ($row['visible'] == 0): ?>
                <span class="badge-hidden">Приховано</span>
            <?php endif; ?>
        </div>

        <div class="product-info">
            <h3><?= htmlspecialchars($row['name']) ?></h3>
            <p class="product-price"><?= number_format($row['price'], 2, '.', ' ') ?> грн</p>
            
           <div class="product-actions">
    <a href="index.php?action=view_product&id=<?= $row['id'] ?>" class="btn-view">Перегляд</a>
    
    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1): ?>
        <a href="index.php?action=update_product&id=<?= $row['id'] ?>" title="Редагувати">Редагувати</a>
        
        <a href="index.php?action=delete_product&id=<?= $row['id'] ?>" 
           style="color: red; margin-left: 10px;" 
           onclick="return confirm('Ви дійсно хочете видалити цей товар?')" 
           title="Видалити">Видалити</a>
    <?php endif; ?>
</div>
        </div>
    </div>
<?php endwhile; ?>
    </div>
</div>