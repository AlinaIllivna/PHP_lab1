<?php
// 1. Перевірка прав (пункт 4 завдання: доступно лише адміністратору)
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    die("<div class='error-box'>Доступ заборонено! Тільки адміністратор може редагувати товари.</div>");
}

// 2. Отримуємо та перевіряємо ID (захист від ін'єкцій через (int))
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
} else {
    die("<div class='error-box'>ID товару не вказано.</div>");
}

$message = "";

// 3. Обробка натискання кнопки "Зберегти зміни"
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $description = mysqli_real_escape_string($link, $_POST['description']);
    $price = (float)$_POST['price'];
    $image = mysqli_real_escape_string($link, $_POST['image']);
    
    // Редагування поля visible (чекбокс)
    $visible = isset($_POST['visible']) ? 1 : 0;

    if (!empty($name) && $price > 0) {
        $update_sql = "UPDATE products SET 
                        name = '$name', 
                        description = '$description', 
                        price = $price, 
                        image = '$image', 
                        visible = $visible 
                      WHERE id = $id";
        
        if (mysqli_query($link, $update_sql)) {
            $message = "<div class='success-box'>Дані успішно оновлено! <a href='index.php?action=products'>Повернутися до списку</a></div>";
        } else {
            $message = "<div class='error-box'>Помилка оновлення: " . mysqli_error($link) . "</div>";
        }
    } else {
        $message = "<div class='error-box'>Заповніть обов'язкові поля!</div>";
    }
}

// 4. Отримуємо поточні дані товару для заповнення полів форми
$query = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($link, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("<div class='error-box'>Такої сторінки не існує (товару з ID $id не знайдено).</div>");
}
?>

<div class="form-container">
    <h2>Редагування товару №<?= $id ?></h2>
    
    <?= $message ?>

    <form method="POST" action="index.php?action=update_product&id=<?= $id ?>" class="crud-form">
        <div class="form-group">
            <label>Назва товару <span class="required">*</span></label>
            <input type="text" name="name" required value="<?= htmlspecialchars($product['name']) ?>">
        </div>

        <div class="form-group">
            <label>Опис</label>
            <textarea name="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Ціна (грн) <span class="required">*</span></label>
            <input type="number" step="0.01" name="price" required value="<?= $product['price'] ?>">
        </div>

        <div class="form-group">
            <label>Назва файлу зображення</label>
            <input type="text" name="image" value="<?= htmlspecialchars($product['image']) ?>">
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="visible" <?= $product['visible'] ? 'checked' : '' ?>>
                Опубліковано на сайті
            </label>
        </div>

        <button type="submit" name="submit" class="form-button">Зберегти зміни</button>
        <a href="index.php?action=products" style="margin-left: 10px;">Скасувати</a>
    </form>
</div>