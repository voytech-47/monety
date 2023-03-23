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
    <link rel="stylesheet" href="styles/style_index.css">
</head>

<body>
    <?php
    foreach ($_GET as $key => $value) {
        $_SESSION[$key] = $value;
    }
    if (isset($_POST['deleteCheck'])) {
        $polaczenie = mysqli_connect('localhost', 'root', '', 'monety');
        $dropTable = str_replace('%20', ' ', $_SESSION['album']);
        $deleteQ = "DROP TABLE `" . $dropTable . "`;";
        $query = mysqli_query($polaczenie, $deleteQ);
        mysqli_close($polaczenie);
        header("Location: home.php?admin=yes");
    }
    if (isset($_POST['album'])) {
        // $oldFace = "images/" . strval($_SESSION['album']) . "/face.tmp";
        // unlink($oldFace);
        $dropTable = str_replace('%20', ' ', $_SESSION['album']);
        $target_face = "images/" . strval($dropTable) . "/face." . pathinfo($_FILES['zdjecie']['tmp_name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['zdjecie']['tmp_name'], $target_face);
    }
    ?>
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
                            <a onclick=fadeOut("./dodaj.php")>Dodaj przedmiot</a>
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
            if (isset($_SESSION['login']) and $_SESSION['login'] == 'admin' and isset($_GET['admin']) and $_GET['admin'] == "yes") {
                echo <<<EOL
                <div id='banner-input'>
                <form id='form' action="album.php" method="post" enctype="multipart/form-data">
                <input id='deleteCheck' name='deleteCheck' type='checkbox' style='display: none'>
                <span class="input" style="margin-bottom: 0.5rem">
                <label for='album'>Album:&nbsp</label>
                EOL;
                if (isset($_POST['album'])) {
                    echo "<input type='text' name='album' id='album' value='" . $_POST['album'] . "'>";
                } else {
                    echo "<input type='text' name='album' id='album' value='" . $_SESSION['album'] . "'>";
                }
                echo "</span>";
                echo <<<EOL
                <span class="input">
                <label for="zdjecie">Zdjęcie albumu:&nbsp</label>
                <input type="file" id="zdjecie" name="zdjecie" accept=".jpg,.jpeg,.png,.jfif">
                </span>
                EOL;
                echo "<input type='submit' style='background-color:#a0ffa0; cursor: pointer' value='Zaakceptuj zmiany'>";
                echo "<button type='button' id='delete' formmethod='post' form='form' style='background-color: #ff7373; margin-top: 1rem; width: 100%; cursor:pointer' onclick='usun(false)'>Usuń album</button>";
                echo "</form></div>";
            } else {
                if (isset($_POST['album'])) {
                    echo "<h1>Album: " . $_POST['album'] . "</h1>";
                } else {
                    echo "<h1>Album: " . $_GET['album'] . "</h1>";
                }
            }
            ?>
        </div>
        <div id="main">
            <span id='back-span'>
                <?php
                $sortDict = array(
                    "alphaAsc" => "Alfabetycznie",
                    "alphaDesc" => "Od Z do A",
                    "dateDesc" => "Od najnowszych",
                    "dateAsc" => "Od najstarszych",
                    "updateDesc" => "Najnowsza edycja",
                    "updateAsc" => "Najstarsza edycja"
                );
                $link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                echo "<div id='popup'>Skopiowano</div>";
                echo "<p id='link' onclick=copyToClipboard()>Kliknij, aby skopiować adres albumu</p>";
                echo "<p style='display: none' id='toCopy'>" . $link . "</p>";
                if (isset($_SESSION['login']) and $_SESSION['login'] == 'admin' and isset($_GET['admin']) and $_GET['admin'] == "yes") {
                    echo "<a class='back' onclick=fadeOut('./home.php?admin=yes')>Powrót do panelu administratora</a>";
                    echo "</span>";
                    echo "<p style='text-align: center; margin-bottom: 0.5rem; margin-top: 1rem'>Wybierz monetę, aby edytować</p>";
                } else {
                    echo "<a class='back' onclick=fadeOut('./home.php')>Powrót do albumów</a>";
                    echo <<<EOL
                    <form method='post'>
                    <select name='sort' id='sort' onchange='this.form.submit()' style='margin-top: 1rem'>
                    EOL;
                    if (!isset($_POST['sort'])) {
                        echo "<option selected disabled value='def'>Sortuj...</option>";
                        foreach ($sortDict as $key => $value) {
                            echo "<option value='" . $key . "'>" . $value . "</option>";
                        }
                    } else {
                        echo "<option disabled value='def'>Sortuj...</option>";
                        foreach ($sortDict as $key => $value) {
                            if ($_POST['sort'] == $key) {
                                echo "<option selected value='" . $key . "'>" . $value . "</option>";
                            } else {
                                echo "<option value='" . $key . "'>" . $value . "</option>";
                            }
                        }
                    }
                    echo <<<EOL
                    </select>
                    </form>
                    EOL;
                    echo <<<EOL
                    <div id='switch-wrapper'>
                    <p>Widok: </p>
                    <div id='view-list' onclick=changeView(this.id)>
                    <div class='button-element-list'></div>
                    <div class='button-element-list'></div>
                    <div class='button-element-list'></div>
                    <div class='button-element-list'></div>
                    </div>
                    </div>
                    EOL;
                    echo "</span>";
                }
                $flag = 0;
                while ($flag != 2) {
                    if ($flag == 0) {
                        echo "<div id='panels'>";
                    } else {
                        echo "<div id='panels-row'>";
                    }
                    $polaczenie = mysqli_connect('localhost', 'root', '', 'monety');
                    $sortQuery = array(
                        "alphaAsc" => "ORDER BY `nazwa` ASC",
                        "alphaDesc" => "ORDER BY `nazwa` DESC",
                        "dateDesc" => "ORDER BY `id` DESC",
                        "dateAsc" => "ORDER BY `id` ASC",
                        "updateDesc" => "ORDER BY `time` DESC",
                        "updateAsc" => "ORDER BY `time` ASC",
                        "priceDesc" => "ORDER BY `price` DESC",
                        "priceAsc" => "ORDER BY `price` ASC"
                    );
                    $album = str_replace('%20', ' ', $_SESSION['album']);
                    if (isset($_POST['sort'])) {
                        $showQuery = "SELECT nazwa, opis, awers, rewers FROM `" . $album . "` " . $sortQuery[$_POST['sort']] . ";";
                    } else {
                        $showQuery = "SELECT nazwa, opis, awers, rewers FROM `" . $album . "`;";
                    }
                    $query = mysqli_query($polaczenie, $showQuery);
                    if (mysqli_num_rows($query) == 0) {
                        echo "<p style='text-align: center; margin-bottom: 1rem'>Brak monet w albumie.</p>";
                    } else {
                        if ($flag != 0) {
                            while ($row = mysqli_fetch_row($query)) {
                                echo "<div class='panel-row'>";
                                echo "<div class='panel-left'>";
                                $row[0] = str_replace(' ', '%20', $row[0]);
                                $_SESSION['album'] = str_replace(' ', '%20', $_SESSION['album']);
                                if (isset($_SESSION['login']) and $_SESSION['login'] == 'admin' and isset($_GET['admin']) and $_GET['admin'] == "yes") {
                                    echo '<a id="img-wrap" onclick=fadeOut("./moneta.php?nazwa=' . $row[0] . '&album=' . $_SESSION["album"] . '&admin=yes")>';
                                } else {
                                    echo '<a id="img-wrap" onclick=fadeOut("./moneta.php?nazwa=' . $row[0] . '&album=' . $_SESSION["album"] . '")>';
                                }
                                echo "<img class='img-top' src='images/" . $_SESSION['album'] . "/" . $row[2] . "' alt='" . $row[0] . "'>
                                <img class='img-bot' src='images/" . $_SESSION['album'] . "/" . $row[3] . "' alt='" . $row[0] . "'>
                                </a>";
                                echo "</div>";
                                echo "<div class='panel-right'>";
                                echo "<div class='info-wrap-left'>";
                                echo "<h1>" . $row[0] . "</h1>";
                                echo "<p>Opis: " . $row[1] . "</p>";
                                echo "</div>";
                                echo "<div class='info-wrap-right'>";
                                echo "<p>Cena zakupu: </p>";
                                echo "<p>Wartość: </p>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }
                        } else {
                            while ($row = mysqli_fetch_row($query)) {
                                echo "<div class='panel'>";
                                echo "<div class='panel-title'>";
                                echo "<h1>" . $row[0] . "</h1>";
                                echo "</div>";
                                echo "<div class='panel-main'>";
                                $row[0] = str_replace(' ', '%20', $row[0]);
                                $_SESSION['album'] = str_replace(' ', '%20', $_SESSION['album']);
                                if (isset($_SESSION['login']) and $_SESSION['login'] == 'admin' and isset($_GET['admin']) and $_GET['admin'] == "yes") {
                                    echo '<a id="img-wrap" onclick=fadeOut("./moneta.php?nazwa=' . $row[0] . '&album=' . $_SESSION["album"] . '&admin=yes")>';
                                } else {
                                    echo '<a id="img-wrap" onclick=fadeOut("./moneta.php?nazwa=' . $row[0] . '&album=' . $_SESSION["album"] . '")>';
                                }
                                echo "<img class='img-top' src='images/" . $_SESSION['album'] . "/" . $row[2] . "' alt='" . $row[0] . "'>
                                <img class='img-bot' src='images/" . $_SESSION['album'] . "/" . $row[3] . "' alt='" . $row[0] . "'>
                                </a>";
                                echo "</div>";
                                echo "</div>";
                            }
                        }
                    }
                    if (isset($_POST['album'])) {
                        $regex = '/^(?!.*\b' . $_POST['album'] . '\b).*$/u';
                        if (preg_match($regex, $_SESSION['album'])) {
                            $updateQuery = "RENAME TABLE `" . $_SESSION['album'] . "` TO `" . $_POST['album'] . "`;";
                            try {
                                $query2 = mysqli_query($polaczenie, $updateQuery);
                            } catch (Exception $e) {

                            }
                            $old = str_replace('%20', ' ', "./images/" . $_SESSION['album']);
                            $new = "./images/" . $_POST['album'];
                            rename($old, $new);
                        }
                        mysqli_close($polaczenie);
                    }
                    echo "</div>";
                    $flag++;
                }
                ?>
        </div>
        <?php
        if (isset($_POST['album'])) {
            header("Location: home.php?admin=yes&changed=yes");
        }
        ?>
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
    <script src="./script/main.js"></script>
</body>

</html>