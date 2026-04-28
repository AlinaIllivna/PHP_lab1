<?php
require_once 'connection.php';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$limit = 5; //  5  
$offset = ($page - 1) * $limit;

$is_admin = (isset($_SESSION['admin']) && $_SESSION['admin'] == 1);

// Запит: адмін бачить все, користувач — тільки visible=1
if ($is_admin) {
    $sql = "SELECT * FROM products ORDER BY date DESC LIMIT $limit OFFSET $offset";
} else {
    $sql = "SELECT * FROM products ORDER BY date DESC LIMIT $limit OFFSET $offset";
}
// рахуємо кількість сторінок 
if ($is_admin) {
    $total = mysqli_fetch_row(mysqli_query($link,
        "SELECT COUNT(*) FROM products"
    ))[0];
} else {
    $total = mysqli_fetch_row(mysqli_query($link,
        "SELECT COUNT(*) FROM products WHERE visible = 1"
    ))[0];
}
$pages = ceil($total / $limit);

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

<div class="pagination">

    <!-- Перша -->
    <?php if ($page > 1): ?>
        <a href="index.php?action=products&page=1">Перша</a>
    <?php else: ?>
        <span class="disabled">Перша</span>
    <?php endif; ?>

    <!-- Номери -->
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <?php if ($i == $page): ?>
            <span class="active"><?= $i ?></span>
        <?php else: ?>
            <a href="index.php?action=products&page=<?= $i ?>"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <!-- Остання -->
    <?php if ($page < $pages): ?>
        <a href="index.php?action=products&page=<?= $pages ?>">Остання</a>
    <?php else: ?>
        <span class="disabled">Остання</span>
    <?php endif; ?>

</div>