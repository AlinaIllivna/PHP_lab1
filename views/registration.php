<?php

require_once("./connection.php");

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $login = $_POST["login"] ?? "";
    $password = $_POST["password"] ?? "";
    $password2 = $_POST["password2"] ?? "";
    $email = $_POST["email"] ?? "";
    $pib = $_POST["pib"] ?? "";

    // 🔹 ЛОГІН
    if (!preg_match("/^[a-zA-Zа-яА-ЯіІїЇєЄ0-9_-]{4,}$/u", $login)) {
        $errors[] = "Логін має бути мінімум 4 символи і містити лише букви, цифри, _ або -";
    }

    // 🔹 ПАРОЛЬ
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{7,}$/", $password)) {
        $errors[] = "Пароль має містити мінімум 7 символів, великі, малі букви і цифри";
    }

    // 🔹 ПОВТОР ПАРОЛЯ
    if ($password !== $password2) {
        $errors[] = "Паролі не співпадають";
    }

    // 🔹 EMAIL
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Невірний email";
    }
    elseif (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        $errors[] = "Email містить недопустимі символи";
    }

    // 🔹 ПІБ
    if (!empty($pib)) {

        if (strlen($pib) > 255) {
            $errors[] = "ПІБ не може перевищувати 255 символів";
        }

        if (!preg_match("/^[A-Za-zА-Яа-яІіЇїЄє'’.\- ]+$/u", $pib)) {
            $errors[] = "ПІБ містить недопустимі символи";
        }

        if (!preg_match("/^(
            [A-Za-zА-Яа-яІіЇїЄє'’\-]+\\s
            [A-Za-zА-Яа-яІіЇїЄє'’\-]+\\s
            [A-Za-zА-Яа-яІіЇїЄє'’\-]+
            |
            [A-Za-zА-Яа-яІіЇїЄє'’\-]+\\s
            [A-ZА-ЯІЇЄ]\\.[A-ZА-ЯІЇЄ]\\.
            |
            [A-ZА-ЯІЇЄ]\\.[A-ZА-ЯІЇЄ]\\.\\s
            [A-Za-zА-Яа-яІіЇїЄє'’\-]+
        )$/ux", $pib)) {
            $errors[] = "Невірний формат ПІБ";
        }
    }

    // 🔹 ПЕРЕВІРКА ЧИ Є КОРИСТУВАЧ
    $query = "SELECT id FROM users WHERE login='$login' LIMIT 1";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $errors[] = "Користувач з таким логіном вже існує";
    }

    //  ЯКЩО ВСЕ ОК → ДОДАЄМО В БД
    if (empty($errors)) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (login, password, email, pib) 
                  VALUES ('$login', '$hashedPassword', '$email', '$pib')";

        mysqli_query($link, $query);

        $_SESSION["login"] = $login;

        header("Location: index.php?action=registration_successful");
        exit();
    }
}
?>

<div class="form-container">

<h2 class="registration_h2">Реєстрація</h2>

<?php if (!empty($errors)): ?>
    <div class="error-box">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST">

    <div class="form-group">
        <label for="login">Логін<span class="required">*</span></label>
        <input type="text" id="login" name="login" required>
    </div>

    <div class="form-group">
        <label for="password">Пароль<span class="required">*</span></label>
        <input type="password" id="password" name="password"  required>
    </div>

    <div class="form-group">
        <label for="password2">Повторіть пароль<span class="required">*</span></label>
        <input type="password" id="password2" name="password2"  required>
    </div>

    <div class="form-group">
        <label for="email">Email<span class="required">*</span></label>
        <input type="email" id="email" name="email"  required>
    </div>

    <div class="form-group">
        <label for="pib">ПІБ</label>
        <input type="text" id="pib" name="pib">
    </div>

    <button class="form-button" type="submit">
        Зареєструватися
    </button>

    <p>
    Вже маєш акаунт?
    <a href="index.php?action=login">Увійти</a>
</p>

</form>

</div>