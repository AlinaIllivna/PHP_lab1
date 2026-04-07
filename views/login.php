<?php


if (!empty($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $login = $_POST["login"] ?? "";
    $password = $_POST["password"] ?? "";

    if (!empty($login) && !empty($password)) {


        $query = "SELECT * FROM users WHERE login='$login' LIMIT 1";
        $result = mysqli_query($link, $query);

        if ($result && mysqli_num_rows($result) == 1) {

            $user = mysqli_fetch_assoc($result);

            // перевірка пароля
            if (password_verify($password, $user["password"])) {

                // запис у сесію
                $_SESSION["login"] = $user["login"];
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["admin"] = $user["admin"];

                header("Location: index.php");
                exit();

            } else {
                $error = "Невірний логін або пароль";
            }

        } else {
            $error = "Невірний логін або пароль";
        }

    } else {
        $error = "Заповніть всі поля";
    }
}
?>
<div class="form-container">

<h2 class="registration_h2">Авторизація</h2>

<?php if (!empty($error)): ?>
    <div class="error-box">
        <p><?= $error ?></p>
    </div>
<?php endif; ?>

<form method="POST">

    <div class="form-group">
        <label for="login">Логін<span class="required">*</span></label>
        <input type="text" id="login" name="login" required>
    </div>

    <div class="form-group">
        <label for="password">Пароль<span class="required">*</span></label>
        <input type="password" id="password" name="password" required>
    </div>

    <button class="form-button" type="submit">
        Увійти
    </button>

    <p>
        Немає акаунту?
        <a href="index.php?action=registration">Зареєструватися</a>
    </p>

</form>

</div>

</form>