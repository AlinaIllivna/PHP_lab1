<?php
// 1. Перевірка прав (тільки адмін)
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    die("<div class='error-box'>Доступ заборонено! Тільки адміністратор може видаляти товари.</div>");
}

// 2. Отримуємо ID та захищаємо його (перетворюємо в ціле число)

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
} else {
    die("<div class='error-box'>ID товару не вказано.</div>");
}

// 3. Перевіряємо, чи існує такий товар
$check_query = "SELECT id FROM products WHERE id = $id";
$check_result = mysqli_query($link, $check_query);

if (mysqli_num_rows($check_result) == 0) {
    echo "<div class='error-box'>Такої сторінки не існує (товар з ID $id не знайдено).</div>";
} else {
    // 4. Видаляємо
    $delete_query = "DELETE FROM products WHERE id = $id";
    
    if (mysqli_query($link, $delete_query)) {
        echo "<div class='success-box'>Товар успішно видалено!</div>";
        echo "<a href='index.php?action=products' class='btn-view'>Повернутися до списку</a>";
    } else {
        echo "<div class='error-box'>Помилка при видаленні: " . mysqli_error($link) . "</div>";
    }
}
?>