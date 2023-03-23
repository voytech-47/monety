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
    <script src="./script/moneta.js"></script>
    <?php
    if (isset($_POST['deleteCheck'])) {
        $polaczenie = mysqli_connect('localhost', 'root', '', 'monety');
        $deleteQ = "DELETE FROM `" . $_SESSION['album'] . "` WHERE nazwa = '" . $_SESSION['nazwa'] . "' LIMIT 1;";
        $query = mysqli_query($polaczenie, $deleteQ);
        mysqli_close($polaczenie);
        header("Location: album.php?album=" . $_SESSION['album'] . "&admin=yes");
    }
    ?>
    <div id="wrapper">
        <div id="banner">
            <ul id="options">
                <li>
                    <a onclick=fadeOut("./home.php")>Albumy</a>
                </li>
                <?php
                foreach ($_GET as $key => $value) {
                    $_SESSION[$key] = $value;
                }
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
            <div id="banner-bottom">
                <?php
                $backLink = str_replace(' ', '%20', $_SESSION['album']);
                if (isset($_SESSION['admin']) and $_SESSION['admin'] == "yes") {
                    echo "<a onclick=fadeOut('./album.php?album=" . $backLink . "&admin=yes')>Powrót do albumu: " . $_SESSION['album'] . "</a>";
                } else {
                    echo "<a onclick=fadeOut('./album.php?album=" . $backLink . "')>Powrót do albumu: " . $_SESSION['album'] . "</a>";
                }
                ?>
            </div>
        </div>
        <div id="main">
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
                    $showQuery = "SELECT nazwa, opis, zdjecie1, zdjecie2, zdjecie3, zdjecie4, zdjecie5 FROM `" . $_SESSION['album'] . "` WHERE nazwa='" . $_SESSION['nazwa'] . "';";
                    $query = mysqli_query($polaczenie, $showQuery);
                    $row = mysqli_fetch_row($query);
                    for ($i = 1; $i < 6; $i++) {
                        $name = "zdjecie" . $i;
                        if ($row[$i + 1] == "") {
                            break;
                        }
                        if (!empty($_FILES[$name]['name'])) {
                            echo "<span class='img-magnifier-glass-wrap'><img id='img" . $i . "' src='images/" . $_SESSION['album'] . "/" . basename($_FILES[$name]['name']) . "'></span>";
                        } else {
                            echo "<span class='img-magnifier-glass-wrap'><img id='img" . $i . "' src='images/" . $_SESSION['album'] . "/" . $row[$i + 1] . "'></span>";
                        }
                    }
                    ?>
                </div>
            </div>
            <div id="side">
                <?php
                if (isset($_SESSION['admin']) and $_SESSION['admin'] == "yes") {
                    $adminQuery = "SELECT nazwa, opis, zdjecie1, zdjecie2, zdjecie3, zdjecie4, zdjecie5 FROM `" . $_SESSION['album'] . "` WHERE nazwa='" . $_SESSION['nazwa'] . "';";
                    $query2 = mysqli_query($polaczenie, $adminQuery);
                    $row = mysqli_fetch_row($query2);
                    echo <<<EOL
                    <form id='form' action="moneta.php" method="post" enctype="multipart/form-data">
                    <span class="input" style="margin-bottom: 0.5rem">
                    <label for="nazwa" id="nazwa-label">Nazwa:</label>
                    EOL;
                    if (isset($_POST['nazwa']))
                        echo '<input required type="text" name="nazwa" id="nazwa" value="' . $_POST['nazwa'] . '">';
                    else
                        echo '<input required type="text" name="nazwa" id="nazwa" value="' . $row[0] . '">';
                    echo <<<EOL
                    </span>
                    <span class="input" style="margin-bottom: 0.5rem">
                    <label for="opis" id="opis-label">Opis:</label>
                    EOL;
                    if (isset($_POST['nazwa']))
                        echo '<textarea name="opis" id="opis">' . $_POST['opis'] . '</textarea>';
                    else
                        echo '<textarea name="opis" id="opis">' . $row[1] . '</textarea>';
                    echo "</span>";

                    for ($i = 1; $i < 6; $i++) {
                        echo '<span class="input" style="margin-bottom: 0.5rem">';
                        echo '<label for="zdjecie' . $i . '" id="label-zdjecie' . $i . '" style="margin-bottom: 0.5rem" onmouseover="highlightPhoto(this)" onmouseout="unHighlightPhoto(this)">Zdjęcie ' . $i . '</label>';
                        echo '<input type="file" name="zdjecie' . $i . '" id="zdjecie' . $i . '" accept=".jpg,.jpeg,.png,.jfif">';
                        echo '</span>';
                    }
                    echo <<<EOL
                    <span class="input" style="margin-bottom: 1rem">
                    <label for="move">Przenieś do:</label>
                    EOL;
                    $showQuery = "SHOW TABLES WHERE tables_in_monety NOT LIKE 'uzytkownicy'";
                    $query = mysqli_query($polaczenie, $showQuery);
                    echo "<select name='move' id='move' onchange=moveCoin(this.value)>";
                    while ($row = mysqli_fetch_row($query)) {
                        if ($row[0] == $_SESSION['album']) {
                            echo "<option selected disabled value='" . $row[0] . "'>" . $row[0] . "</option>";
                        } else {
                            echo "<option value='" . $row[0] . "'>" . $row[0] . "</option>";
                        }
                    }
                    echo "</select>";
                    echo "</span>";
                    echo <<<EOL
                    </select>
                    </span>
                    EOL;
                    echo "<input id='deleteCheck' name='deleteCheck' type='checkbox' style='display: none'>";
                    echo "<input type='submit' id='confirm' value='Zaakceptuj zmiany' style='margin-bottom: 1rem; width: 100%'>";
                    echo "<button type='button' id='delete' formmethod='post' form='form' style='margin-bottom: 1.5rem; width: 100%' onclick='usun(true)'>Usuń monetę</button>";
                    echo "</form>";
                    if (isset($_POST['nazwa'])) {
                        $deleteQuery = "SELECT zdjecie1, zdjecie2, zdjecie3, zdjecie4, zdjecie5 FROM `" . $_SESSION['album'] . "` WHERE nazwa='" . $_SESSION['nazwa'] . "';";
                        $deleteQ = mysqli_query($polaczenie, $deleteQuery);
                        $row = mysqli_fetch_row($deleteQ);
                        $updateQuery = "UPDATE `" . $_SESSION['album'] . "` SET nazwa = '" . $_POST['nazwa'] . "', opis='" . $_POST['opis'] . "', time = NOW() WHERE nazwa='" . $_SESSION['nazwa'] . "' LIMIT 1;";
                        $query3 = mysqli_query($polaczenie, $updateQuery);
                        $allowed = array('jpg', 'jpeg', 'png', 'jfif', 'JPG', 'JPEG', 'PNG', 'JFIF');
                        for ($i = 0; $i < 5; $i++) {
                            $name = "zdjecie" . $i;
                            if ($_FILES[$name]['name'] != "") {
                                $oldPhoto = "images/" . strval($_SESSION['album']) . "/" . $row[$i];
                                unlink($oldPhoto);
                                $updatePhoto = "UPDATE `" . $_SESSION['album'] . "` SET zdjecie" . $i . "='" . $_FILES[$name]['name'] . "', time = NOW() WHERE nazwa='" . $_SESSION['nazwa'] . "' LIMIT 1;";
                                $targetPhoto = "images/" . strval($_SESSION['album']) . "/" . basename($_FILES[$name]['name']);
                                $fileType_photo = pathinfo($target_photo, PATHINFO_EXTENSION);
                                if (in_array($fileType_photo, $allowed)) {
                                    move_uploaded_file($_FILES[$name]['tmp_name'], $target_photo);
                                }
                                $query4 = mysqli_query($polaczenie, $updatePhoto);
                            }
                        }
                        if (isset($_POST['move'])) {
                            mysqli_begin_transaction($polaczenie);
                            $imagesQ = "SELECT zdjecie1, zdjecie2, zdjecie3, zdjecie4, zdjecie5 FROM `%s` WHERE nazwa = '%s';";
                            $query6 = mysqli_query($polaczenie, sprintf($imagesQ, $_SESSION['album'], $_POST['nazwa']));
                            $row = mysqli_fetch_row($query6);
                            for ($i = 0; $i < 5; $i++) {
                                if ($row[$i] == '')
                                    break;
                                $oldDir = "images/" . $_SESSION['album'] . "/" . $row[$i];
                                $newDir = "images/" . $_POST['move'] . "/" . $row[$i];
                                rename($oldDir, $newDir);
                            }
                            try {
                                $tempInsert = "INSERT INTO `%s` SELECT * FROM `%s` WHERE nazwa = '%s';";
                                $tempDelete = "DELETE FROM `%s` WHERE nazwa = '%s';";
                                $query7 = sprintf($tempInsert, $_POST['move'], $_SESSION['album'], $_POST['nazwa']);
                                $query8 = sprintf($tempDelete, $_SESSION['album'], $_POST['nazwa']);
                                mysqli_query($polaczenie, $query7);
                                mysqli_query($polaczenie, $query8);
                                mysqli_commit($polaczenie);
                                mysqli_close($polaczenie);
                                header("Location: album.php?album=" . $_SESSION['album'] . "&admin=yes");
                            } catch (mysqli_sql_exception $e) {
                                mysqli_rollback($polaczenie);
                                throw $e;
                            }
                        }
                    }
                    mysqli_close($polaczenie);
                    if (isset($_POST['nazwa'])) {
                        echo "<p style='margin-bottom:1.5rem'>Informacje zostały zaktualizowane</p>";
                    }
                }
                ?>
                <div id="settings">
                    <?php
                    if (isset($_SESSION['admin']) and $_SESSION['admin'] == "yes") {

                    } else {
                        echo "<p style='margin-bottom:.7rem'>Album: <i>" . $_SESSION['album'] . "</i></p>";
                        echo "<p style='margin-bottom:.7rem'>Nazwa: <i>" . $row[0] . "</i></p>";
                        echo "<p style='margin-bottom:1rem;'>Opis: " . $row[1] . "</p>";
                    }
                    ?>
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
                    <div id="lupa" style="margin-bottom: 0.7rem">
                        <span>
                            <label for="magnify-select">Wybierz zdjęcie: </label>
                            <select name="magnify-select" id="magnify-select" onchange="selectMagnify(this.value)">
                                <?php
                                $polaczenie = mysqli_connect('localhost', 'root', '', 'monety');
                                $q = "SELECT zdjecie1, zdjecie2, zdjecie3, zdjecie4, zdjecie5 FROM `" . $_SESSION['album'] . "` WHERE nazwa='" . $_SESSION['nazwa'] . "';";
                                $query = mysqli_query($polaczenie, $q);
                                $row = mysqli_fetch_row($query);
                                for ($i = 1; $i < 6; $i++) {
                                    if ($row[$i - 1] == "") {
                                        break;
                                    }
                                    echo "<option value='" . $i . "'>Zdjęcie " . $i . "</option>";
                                }
                                ?>
                            </select>
                        </span>
                        <span>
                            <label for="magnify" id='label-zdjecie'>Włącz lupę dla zdjęcia 1.</label>
                            <input type="checkbox" name="magnify" id="magnify" oninput="checkMagnify()">
                        </span>
                        </span>
                        <span class='magnify-value'>
                            <input type="range" value=2 min=1.5 max=5 step=0.5 oninput="changeMagnify(this.value)"
                                name="strength" id="strength">
                            <span id='magnify-value' style='padding:15px'>2x</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./script/main.js"></script>
    <script src="./script/moneta.js"></script>
    <script>
        function checkMagnify() {
            value = document.getElementById('magnify-select').value
            if (!document.getElementById('magnify').checked) {
                try {
                    document.getElementById('img-magnifier-glass').remove()
                } catch (error) {

                }
            } else {
                magnify(value)
            }
        }
    </script>
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