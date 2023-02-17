<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Album monet</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style_index.css">
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
            <?php
                if (isset($_GET['admin']) and $_GET['admin'] == "yes") {
                    echo "<p style='text-align: left'>Wybierz, aby edytować album:</p>";
                } else {
                    echo "<p style='text-align: left'>Dostępne albumy:</p>";
                }
            ?>
            <div id="panels">
                <?php
                $polaczenie = mysqli_connect('localhost', 'root', '');
                try {
                    mysqli_select_db($polaczenie, 'monety');
                } catch (Exception $e) {
                    mysqli_query($polaczenie, "CREATE DATABASE `monety`;");
                    mysqli_query($polaczenie, "USE `monety`;");
                }
                $showQuery = "SHOW TABLES WHERE tables_in_monety NOT LIKE 'uzytkownicy'";
                $tablesQuery = mysqli_query($polaczenie, $showQuery);
                if (mysqli_num_rows($tablesQuery) == 0) {
                    echo "<p style='text-align: left'>Brak albumów w bazie.</p>";
                } else {
                    while ($tables = mysqli_fetch_row($tablesQuery)) {
                        echo "<div class='panel'>
                    <div class='panel-title'>
                    <h1>" . $tables[0] . "</h1>
                                </div>";
                        echo "<div class='panel-main'>";
                        if (isset($_GET['admin']) and $_GET['admin'] == "yes") {
                            echo "<a onclick=fadeOut('./album.php?nazwa=" . $tables[0] . "&admin=yes')><img class='img-bot' src='images/" . $tables[0] . "/face.tmp' alt='" . $tables[0] . "'></a>";
                        } else {
                            echo "<a onclick=fadeOut('./album.php?nazwa=" . $tables[0] . "')><img class='img-bot' src='images/" . $tables[0] . "/face.tmp' alt='" . $tables[0] . "'></a>";
                        }
                        echo "</div></div>";
                    }
                }

                ?>
            </div>
        </div>
    </div>
</body>

</html>