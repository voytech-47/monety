<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Album monet</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style_index.css">
</head>

<body>
    <div id="wrapper">
        <div id="banner">
            <ul id="options">
                <li>
                    <a href="./index.php">Albumy</a>
                </li>
                <li>
                    <a href="./utworz.php">Utwórz album</a>
                </li>
                <li>
                    <a href="./dodaj.php">Dodaj monetę</a>
                </li>
                <li>
                    <a href="./login.php">Zaloguj się</a>
                </li>
            </ul>
        </div>
        <div id="main">
            <?php
            $polaczenie = mysqli_connect('localhost', 'root', '');
            try {
                mysqli_select_db($polaczenie, 'monety');
            } catch (Exception $e) {
                mysqli_query($polaczenie, "CREATE DATABASE `monety`;");
                mysqli_query($polaczenie, "USE `monety`;");
            }
            $showQuery = "SHOW TABLES";
            $tablesQuery = mysqli_query($polaczenie, $showQuery);
            if (mysqli_num_rows($tablesQuery) == 0) {
                echo "<p>Brak albumów w bazie.</p>";
            } else {
                while ($tables = mysqli_fetch_row($tablesQuery)) {
                    echo "<div class='panel'>
                    <div class='panel-title'>
                    <h1>" . $tables[0] . "</h1>
                                </div>";
                    echo "<div class='panel-main'>";
                    echo "<img class='img-top' src='images/" . $tables[0] . "/face.tmp' alt='" . $tables[0] . "'>";
                    echo "</div></div>";
                }
            }

            ?>
        </div>
    </div>
    <script src="script/main.js"></script>
</body>

</html>