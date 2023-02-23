<?php
session_start();
session_unset();
$_SESSION['zalogowany'] = false;
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Album monet</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style_form.css">
</head>

<body>
    <script src="./script/main.js"></script>
    <div id="wrapper">
        <div id="banner">
            <ul id="options">
                <li>
                    <a onclick=fadeOut("./home.php")>Albumy</a>
                </li>
                <?php
                if (isset($_SESSION['zalogowany']) and $_SESSION['zalogowany']) {
                    echo <<<EOL
                        <li>
                            <a onclick=fadeOut("./utworz.php")>Utwórz album</a>
                        </li>
                        <li>
                            <a onclick=fadeOut("./dodaj.php")>Dodaj monetę</a>
                        </li>
                        EOL;
                }
                ?>
                <li>
                    <a onclick=fadeOut("./index.php")>Zaloguj się</a>
                </li>
            </ul>
        </div>
        <div id="main">
            <div id="form">
                <form action="index.php" method="post" enctype="multipart/form-data">
                    <?php
                    echo <<<EOL
                        <span class="input">
                        <label for="login">Login:</label>
                        EOL;
                    if (isset($_POST['login'])) {
                        echo "<input type='text' id='login' name='login' required value='" . $_POST['login'] . "'>";
                    } else {
                        echo "<input type='text' required id='login' name='login'>";
                    }
                    echo <<<EOL
                        </span>
                        <span class="input">
                        <label for="haslo">Hasło:</label>
                        <input type="password" name="haslo" id="haslo" required>
                        </span>
                        <input type="submit" value="Zaloguj się">
                        EOL;

                    if (isset($_POST['haslo']) and isset($_POST['login'])) {
                        $polaczenie = mysqli_connect('localhost', 'root', '', 'monety');
                        $loginKW = 'SELECT login, haslo FROM uzytkownicy WHERE login = "' . $_POST['login'] . '";';
                        try {
                            $row = mysqli_fetch_row(mysqli_query($polaczenie, $loginKW));
                        } catch (Exception $e) {
                            echo "<p style='text-align: center; margin-top:2rem'>Błąd w bazie danych, skontaktuj się z administratorem";
                            return;
                        }
                        if ($row == null or $row[0] != 'admin' or $row[1] != sha1($_POST['haslo'])) {
                            echo "<p style='text-align: center; margin-top:2rem'>Niepoprawny login lub hasło";
                        } else {
                            $_SESSION['zalogowany'] = true;
                            $_SESSION['login'] = $_POST['login'];
                            header("Location: home.php");
                        }
                    }

                    ?>
                </form>
            </div>
        </div>
    </div>
    <footer>
        <a href='https://github.com/voytech-47'>autor: Wojciech Grzybowski</a>
        <?php
        if (isset($_SESSION['zalogowany']) and $_SESSION['zalogowany']) {
            echo "<p>Zalogowany użytykownik: " . $_SESSION['login'] . "</p>";
        }
        ?>
    </footer>
</body>

</html>