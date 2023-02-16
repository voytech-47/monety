<?php
session_start();
if (!isset($_GET['admin'])) {
    foreach ($_POST as $key => $value) {
        if ($key == "nazwa")
            $_SESSION["newNazwa"] = $value;
        else if ($key = "opis")
            $_SESSION["newOpis"] = $value;
        else
            $_SESSION[$key] = $value;
    }
} else {
    foreach ($_GET as $key => $value) {
        $_SESSION[$key] = $value;
    }
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Album monet</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style_moneta.css">
</head>

<body>
    <script src="./script/main.js"></script>
    <script src="script/moneta.js"></script>
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
            <div id="banner-bottom">
                <?php
                echo "<a onclick=fadeOut('./album.php?nazwa=" . $_SESSION['album'] . "')>Album: " . $_SESSION['album'] . "</a>";
                ?>
            </div>
        </div>
        <div id="main">
            <div id="back">
                <?php
                if (isset($_SESSION['admin']) and $_SESSION['admin'] == "yes") {
                    echo "<a onclick=fadeOut('./album.php?nazwa=" . $_SESSION['album'] . "&admin=yes')>Powrót do albumu</a>";
                } else {
                    echo "<a onclick=fadeOut('./album.php?nazwa=" . $_SESSION['album'] . "')>Powrót do albumu</a>";
                }
                ?>
            </div>
            <div class="panel">
                <div class="panel-title">
                    <?php
                    if (isset($_POST['nazwa']))
                        echo "<h1>" . $_POST['nazwa'] . "</h1>";
                    else
                        echo "<h1>" . $_SESSION['nazwa'] . "</h1>";
                    ?>
                </div>
                <div class="panel-main">
                    <?php
                    $polaczenie = mysqli_connect('localhost', 'root', '', 'monety');
                    $showQuery = "SELECT nazwa, opis, awers, rewers FROM `" . $_SESSION['album'] . "` WHERE nazwa='" . $_SESSION['nazwa'] . "';";
                    $query = mysqli_query($polaczenie, $showQuery);
                    $row = mysqli_fetch_row($query);
                    echo "<img id='img-top' src='images/" . $_SESSION['album'] . "/" . $row[2] . "'>";
                    echo "<img id='img-bot' src='images/" . $_SESSION['album'] . "/" . $row[3] . "'>";
                    ?>
                </div>
            </div>
            <?php
            if (isset($_SESSION['admin']) and $_SESSION['admin'] == "yes") {
                $adminQuery = "SELECT nazwa, opis FROM `" . $_SESSION['album'] . "` WHERE nazwa='" . $_SESSION['nazwa'] . "';";
                $query2 = mysqli_query($polaczenie, $adminQuery);
                $row = mysqli_fetch_row($query2);
                echo <<<EOL
                    <form action="moneta.php" method="post" enctype="multipart/form-data">
                    <span class="input" style="margin-bottom: 0.5rem">
                    <label for="nazwa" id="nazwa-label">Nazwa:</label>
                    EOL;
                if (isset($_POST['nazwa']))
                    echo '<input required type="text" name="nazwa" id="nazwa" value="' . $_POST['nazwa'] . '">';
                else
                    echo '<input required type="text" name="nazwa" id="nazwa" value="' . $row[0] . '">';
                echo <<<EOL
                    </span>
                    <span class="input input-textarea">
                    <label for="opis" id="opis-label">Opis:</label>
                    EOL;
                if (isset($_POST['nazwa']))
                    echo '<textarea required name="opis" id="opis">' . $_POST['opis']. '</textarea>';
                else
                    echo '<textarea required name="opis" id="opis">' . $row[1] . '</textarea>';
                echo "</span>";
                echo "<input type='submit' value='Zaakceptuj zmiany' style='margin-bottom: 1.5rem; width: 100%'>";
                echo "</form>";
                if (isset($_POST['nazwa'])) {
                    $updateQuery = "UPDATE `" . $_SESSION['album'] . "` SET nazwa = '" . $_POST['nazwa'] . "', opis='" . $_POST['opis'] . "' WHERE nazwa='" . $_SESSION['nazwa'] . "' LIMIT 1;";
                    $query3 = mysqli_query($polaczenie, $updateQuery);
                }
                mysqli_close($polaczenie);
                if (isset($_POST['nazwa'])) {
                    echo "<p style='margin-bottom:1.5rem'>Informacje zostały zaktualizowane</p>";
                }
            } else {
                echo "<p style='margin-top:1rem; margin-bottom:1.5rem'>Opis: " . $row[1] . "</p>";
            }
            ?>
            <div id="settings">
                <div id="brightness">
                    <span>
                        <label for="brightness">Jasność: </label>
                        <span id="brightness_value">100%</span>
                    </span>
                    <input min=50 max=200 value=100 type="range" name="brightness" id="brightness_input"
                        oninput="changeBrightness(this.value)">
                </div>
                <div id="contrast">
                    <span>
                        <label for="contrast">Kontrast: </label>
                        <span id="contrast_value">100%</span>
                    </span>
                    <input min=50 max=200 value=100 type="range" name="contrast" id="contrast_input"
                        oninput="changeContrast(this.value)">
                </div>
            </div>
        </div>
    </div>
</body>

</html>