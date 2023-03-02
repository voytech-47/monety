<?php
session_start();
if (!$_SESSION['zalogowany']) {
    header('Location: index.php');
}
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
                        <li>
                            <a onclick=fadeOut("./home.php?admin=yes")>Panel administratora</a>
                        </li>
                        <li>
                            <a onclick=fadeOut("./index.php")>Wyloguj się</a>
                        </li>
                        EOL;
                } else {
                    echo <<<EOL
                        <li>
                            <a onclick=fadeOut("./index.php")>Zaloguj się</a>
                        </li>
                        EOL;
                }
                ?>
            </ul>
        </div>
        <div id="main">
            <div id="form">
                <form action="utworz.php" method="post" enctype="multipart/form-data">
                    <h2>Wprowadź informacje dotyczące nowego albumu:</h2>
                    <span class="input">
                        <label for="nazwa" id="nazwa-label">Nazwa: </label>
                        <input required type="text" name="nazwa" id="nazwa" pattern='^(?=\S)(?!.*[<>])(.*\S)?$'>
                    </span>
                    <span class="input">
                        <label for="zdjecie">Zdjęcie albumu: </label>
                        <input type="file" id="zdjecie" name="zdjecie" accept=".jpg,.jpeg,.png,.jfif">
                    </span>
                    <input type="submit" value="Dodaj album">
                </form>
                <?php
                $polaczenie = mysqli_connect('localhost', 'root', '', 'monety');
                if (mysqli_connect_errno()) {
                    echo "<p>error</p>";
                }
                if (!isset($_POST['nazwa'])) {
                    return;
                }
                $createQuery = "CREATE TABLE `" . strval($_POST['nazwa']) . "` (
                        `id` INT NOT NULL AUTO_INCREMENT,
                        `nazwa` VARCHAR(255) NOT NULL,
                        `opis` TEXT NOT NULL,
                        `awers` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                        `rewers` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                        `time` DATETIME NOT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE = InnoDB;";

                $flag = 1;

                try {
                    $query = mysqli_query($polaczenie, "SELECT 1 FROM `" . strval($_POST['nazwa']) . "` LIMIT 1;");
                } catch (Exception $e) {
                    $createTable = mysqli_query($polaczenie, $createQuery);
                    if (!file_exists("images/" . strval($_POST['nazwa']))) {
                        mkdir("images/" . strval($_POST['nazwa']), 0777, true);
                    }
                    if ($_FILES['zdjecie']['name'] != "") {
                        $target_face = "images/" . strval($_POST['nazwa']) . "/face." . pathinfo($_FILES['zdjecie']['tmp_name'], PATHINFO_EXTENSION);
                        move_uploaded_file($_FILES['zdjecie']['tmp_name'], $target_face);
                    }
                    echo "<p style='margin-top: 2rem;'>Dodano album o nazwie <i>" . strval($_POST['nazwa']) . "</i>.</p>";
                    $flag = 0;
                }

                if ($flag) {
                    echo "<p style='margin-top: 2rem;'>Istnieje już album o nazwie <i>" . strval($_POST['nazwa']) . "</i>.</p>";
                }
                ?>
            </div>
            <div>
                <p style="margin-top: 1rem;">Obecne albumy:</p>
                <ul>
                    <?php
                    $showQuery = "SHOW TABLES WHERE tables_in_monety NOT LIKE 'uzytkownicy'";
                    $query = mysqli_query($polaczenie, $showQuery);
                    while ($row = mysqli_fetch_row($query)) {
                        echo "<li>" . $row[0] . "</li>";
                    }
                    mysqli_close($polaczenie);
                    ?>
                </ul>
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