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
                echo "<p style='text-align: center'>Wybierz, aby edytować album:</p>";
            } else {
                $_SESSION['admin'] = "no";
                $sortDict = array(
                    "alphaAsc" => "Alfabetycznie",
                    "alphaDesc" => "Od Z do A",
                    "dateDesc" => "Od najnowszych",
                    "dateAsc" => "Od najstarszych",
                    "updateDesc" => "Najnowsza edycja",
                    "updateAsc" => "Najstarsza edycja"
                );
                echo "<span id='back-span'>";
                echo "<p style='text-align: center'>Dostępne albumy:</p>";
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
                </span>
                EOL;
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
                $sortQuery = array(
                    "alphaAsc" => "ORDER BY TABLE_NAME ASC",
                    "alphaDesc" => "ORDER BY TABLE_NAME DESC",
                    "dateDesc" => "ORDER BY CREATE_TIME DESC",
                    "dateAsc" => "ORDER BY CREATE_TIME ASC",
                    "updateDesc" => "ORDER BY CREATE_TIME DESC",
                    "updateAsc" => "ORDER BY CREATE_TIME ASC"
                );
                if (isset($_POST['sort'])) {
                    $showQuery = "SELECT table_name, engine FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_schema='monety' AND TABLE_NAME NOT LIKE 'uzytkownicy' ".$sortQuery[$_POST['sort']].";";
                } else {
                    $showQuery = "SELECT table_name, engine FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_schema='monety' AND TABLE_NAME NOT LIKE 'uzytkownicy';";
                }
                $tablesQuery = mysqli_query($polaczenie, $showQuery);
                if (mysqli_num_rows($tablesQuery) == 0) {
                    echo "<p style='text-align: center'>Brak albumów w bazie.</p>";
                } else {
                    while ($tables = mysqli_fetch_row($tablesQuery)) {
                        $newTables = str_replace(' ', '%20', $tables[0]);
                        echo "<div class='panel'>
                    <div class='panel-title'>
                    <h1>" . $tables[0] . "</h1>
                                </div>";
                        echo "<div class='panel-main'>";
                        if (isset($_GET['admin']) and $_GET['admin'] == "yes") {
                            if (file_exists("images/".$tables[0]."/face.tmp"))
                                echo "<a onclick=fadeOut('./album.php?album=" . $newTables . "&admin=yes')><img id='img-cover' src='images/" . $tables[0] . "/face.tmp' alt='" . $tables[0] . "'></a>";
                            else
                                echo "<a onclick=fadeOut('./album.php?album=" . $newTables . "&admin=yes')><img id='img-cover' src='images/face.tmp' alt='" . $tables[0] . "'></a>";
                        } else {
                            if (file_exists("images/".$tables[0]."/face.tmp"))
                                echo "<a onclick=fadeOut('./album.php?album=" . $newTables . "')><img id='img-cover' src='images/" . $tables[0] . "/face.tmp' alt='" . $tables[0] . "'></a>";
                            else
                            echo "<a onclick=fadeOut('./album.php?album=" . $newTables . "')><img id='img-cover' src='images/face.tmp' alt='" . $tables[0] . "'></a>";
                        }
                        echo "</div></div>";
                    }
                }

                ?>
            </div>
            <?php
            if (isset($_GET['changed'])) {
                echo "<p style='margin-top:1.5rem; text-align: center'>Informacje zostały zaktualizowane</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>