<?php
session_start();
// if (!$_SESSION['zalogowany']) {
//     header('Location: index.php');
// }
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
                    <a onclick=fadeOut('./home.php')>Albumy</a>
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
                    <a onclick=fadeOut("./admin.php")>Panel administratora</a>
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
            <?php
            echo "<h1>Album: " . $_GET['nazwa'] . "</h1>";
            ?>
        </div>
        <div id="main">
            <span id='back-span'>
                <?php
                if (isset($_GET['admin']) and $_GET['admin'] == "yes") {
                    echo "<a class='back' onclick=fadeOut('./admin.php')>Powrót do panelu administratora</a>";
                } else {
                    echo "<a class='back' onclick=fadeOut('./home.php')>Powrót do albumów</a>";
                }
                ?>
            </span>
            <div id="panels">
                <?php
                if (!isset($_GET['nazwa'])) {
                    header("Location: home.php");
                }
                $polaczenie = mysqli_connect('localhost', 'root', '', 'monety');
                $showQuery = "SELECT nazwa, opis, awers, rewers FROM `" . $_GET['nazwa'] . "`;";
                $query = mysqli_query($polaczenie, $showQuery);
                if (mysqli_num_rows($query) == 0) {
                    echo "<p style='text-align: center; margin-bottom: 1rem'>Brak monet w albumie.&nbsp</p><br>";
                } else {
                    while ($row = mysqli_fetch_row($query)) {
                        echo "<div class='panel'>";
                        echo "<div class='panel-title'>";
                        echo "<h1>" . $row[0] . "</h1>";
                        echo "</div>";
                        echo "<div class='panel-main'>";
                        $newRow = str_replace(' ', '%20', $row[0]);
                        if (isset($_GET['admin']) and $_GET['admin'] == "yes") {
                            echo '<a onclick=fadeOut("./moneta.php?nazwa=' . $newRow . '&album=' . $_GET["nazwa"] . '&admin=yes")>';
                        } else {
                            echo '<a onclick=fadeOut("./moneta.php?nazwa=' . $newRow . '&album=' . $_GET["nazwa"] . '")>';
                        }
                        echo "<img class='img-bot' src='images/" . $_GET['nazwa'] . "/" . $row[3] . "' alt='" . $row[0] . "'>
                          <img class='img-top' src='images/" . $_GET['nazwa'] . "/" . $row[2] . "' alt='" . $row[0] . "'>
                          </a>";
                        // echo "<p class='opis'>" . $row[1] . "</p>";
                        echo "</div>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>