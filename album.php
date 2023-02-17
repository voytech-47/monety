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
    $_SESSION['admin'] = "no";
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
            <?php
            foreach ($_GET as $key => $value) {
                $_SESSION[$key] = $value;
            }
            if ((isset($_GET['admin']) and $_GET['admin'] == "yes") or (isset($_SESSION['admin']) and $_SESSION['admin'] == "yes")) {
                echo <<<EOL
                <div id='banner-input'>
                <form action="album.php" method="post" enctype="multipart/form-data">
                <span class="input" style="margin-bottom: 0.5rem">
                <label for='album'>Album:&nbsp</label>
                EOL;
                if (isset($_POST['album'])) {
                    echo "<input type='text' name='album' id='album' value='".$_POST['album']."'>";
                } else {
                    echo "<input type='text' name='album' id='album' value='".$_SESSION['nazwa']."'>";
                }
                echo "</span>";
                echo "<input type='submit' value='Zmień nazwę'>";
                echo "</form></div>";
            } else {
                if (isset($_POST['album'])) {
                    echo "<h1>Album: " . $_POST['album'] . "</h1>";    
                } else {
                    echo "<h1>Album: " . $_GET['nazwa'] . "</h1>";
                }
            }
            ?>
        </div>
        <div id="main">
            <span id='back-span'>
                <?php
                if ((isset($_GET['admin']) and $_GET['admin'] == "yes") or (isset($_SESSION['admin']) and $_SESSION['admin'] == "yes")) {
                    echo "<a class='back' onclick=fadeOut('./home.php?admin=yes')>Powrót do panelu administratora</a>";
                    echo "</span>";
                    echo "<p style='text-align: left; margin-bottom: 0.5rem; margin-top: 1rem'>Wybierz monetę, aby edytować</p>";
                } else {
                    echo "<a class='back' onclick=fadeOut('./home.php')>Powrót do albumów</a>";
                    echo "</span>";
                }
                ?>
            <div id="panels">
                <?php
                // if (!isset($_GET['nazwa'])) {
                //     header("Location: home.php");
                // }
                $polaczenie = mysqli_connect('localhost', 'root', '', 'monety');
                $showQuery = "SELECT nazwa, opis, awers, rewers FROM `" . $_SESSION['nazwa'] . "`;";
                $query = mysqli_query($polaczenie, $showQuery);
                if (mysqli_num_rows($query) == 0) {
                    echo "<p style='text-align: left; margin-bottom: 1rem'>Brak monet w albumie.</p>";
                } else {
                    while ($row = mysqli_fetch_row($query)) {
                        echo "<div class='panel'>";
                        echo "<div class='panel-title'>";
                        echo "<h1>" . $row[0] . "</h1>";
                        echo "</div>";
                        echo "<div class='panel-main'>";
                        $newRow = str_replace(' ', '%20', $row[0]);
                        if (isset($_GET['admin']) and $_GET['admin'] == "yes") {
                            echo '<a onclick=fadeOut("./moneta.php?nazwa=' . $newRow . '&album=' . $_SESSION["nazwa"] . '&admin=yes")>';
                        } else {
                            echo '<a onclick=fadeOut("./moneta.php?nazwa=' . $newRow . '&album=' . $_SESSION["nazwa"] . '")>';
                        }
                        echo "<img class='img-top' src='images/" . $_SESSION['nazwa'] . "/" . $row[2] . "' alt='" . $row[0] . "'>
                          <img class='img-bot' src='images/" . $_SESSION['nazwa'] . "/" . $row[3] . "' alt='" . $row[0] . "'>
                          </a>";
                        // echo "<p class='opis'>" . $row[1] . "</p>";
                        echo "</div>";
                        echo "</div>";
                    }
                }
                if (isset($_POST['album'])) {
                    $updateQuery = "RENAME TABLE `".$_SESSION['nazwa']."` TO `".$_POST['album']."`;";
                    $query2 = mysqli_query($polaczenie, $updateQuery);
                    $old = "./images/".$_SESSION['nazwa'];
                    $new = "./images/".$_POST['album'];
                    rename($old, $new);
                    mysqli_close($polaczenie);
                    }
                ?>
            </div>
            <?php
            if (isset($_POST['album'])) {
                    echo "<p style='margin-top:1.5rem; text-align: left'>Informacje zostały zaktualizowane</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>