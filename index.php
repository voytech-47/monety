<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Album monet</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <div id="wrapper">
        <div id="banner">
            <h1 style="padding-left: 35px;">Album monet</h1>
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
            while ($tables = mysqli_fetch_row($tablesQuery)) {
                echo "  <div class='panel'>
                                <div class='panel-title'>
                                    <h1>" . $tables[0] . "</h1>
                                </div>";
                $fetchQuery = "SELECT `awers`, `rewers`, `nazwa` FROM `" . $tables[0] . "` ORDER BY `id` ASC LIMIT 1;";
                $fetch = mysqli_query($polaczenie, $fetchQuery);
                while ($row = mysqli_fetch_row($fetch)) {
                    echo "<div class='panel-main'>";
                    echo "<img class='img-top' src='images/" . $tables[0] . "/" . $row[1] . "' alt='" . $row[2] . "'>";
                    echo "<img class='img-bot' src='images/" . $tables[0] . "/" . $row[0] . "' alt='" . $row[2] . "'>";
                    echo "</div>";
                }
                echo "</div>";
            }
            ?>
        </div>
    </div>
    <script src="script/main.js"></script>
</body>

</html>