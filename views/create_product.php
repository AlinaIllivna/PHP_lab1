<?php
// Перевірка авторизації
if (!isset($_SESSION['login'])) {
    echo "<p>Для додавання товару потрібно <a href='index.php?action=login'>увійти</a>.</p>";
    return;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    // 1. Отримуємо та очищуємо дані
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $description = mysqli_real_escape_string($link, $_POST['description']);
    $price = (float)$_POST['price'];
    $image = mysqli_real_escape_string($link, $_POST['image']); // Назва файлу, напр. product1.jpg
    
    $author_id = $_SESSION['user_id'];
    
    // 2. Логіка видимості (пункт 2 завдання)
    $visible = ($_SESSION['admin'] == 1) ? 1 : 0;

    // 3. Перевірка на порожні поля
    if (!empty($name) && $price > 0) {
        $sql = "INSERT INTO products (name, description, price, image, visible, author_id) 
                VALUES ('$name', '$description', $price, '$image', $visible, $author_id)";
        
        if (mysqli_query($link, $sql)) {
            $message = "<div class='success-box'>Товар успішно додано!" . 
                       ($_SESSION['admin'] == 0 ? " Він з'явиться на сайті після перевірки адміністратором." : "") . 
                       "</div>";
        } else {
            $message = "<div class='error-box'>Помилка БД: " . mysqli_error($link) . "</div>";
        }
    } else {
        $message = "<div class='error-box'>Будь ласка, заповніть обов'язкові поля!</div>";
    }
}
?>

<div class="form-container">
    <h2>Додати новий товар</h2>
    
    <?= $message ?>

    <form method="POST" action="index.php?action=create_product" class="crud-form">
        <div class="form-group">
            <label>Назва товару <span class="required">*</span></label>
            <input type="text" name="name" required placeholder="Наприклад: Кава Lavazza">
        </div>

        <div class="form-group">
            <label>Опис</label>
            <textarea name="description" rows="4" placeholder="Детальний опис товару..."></textarea>
        </div>

        <div class="form-group">
            <label>Ціна (грн) <span class="required">*</span></label>
            <input type="number" step="0.01" name="price" required placeholder="0.00">
        </div>

        <div class="form-group">
            <label>Зображення</label>
            <input type="text" name="image" placeholder="https://ekava.com.ua/...">
           
        </div>

        <button type="submit" name="submit" class="form-button">Створити товар</button>
    </form>
</div>