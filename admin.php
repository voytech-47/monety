<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Album monet</title>
    <link rel="stylesheet" href="styles/style_admin.css">
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <script src="./script/main.js"></script>
    <div id="wrapper">
        <div id="banner">
            <ul id="options">
                <li>
                    <a onclick="fadeOut(&quot;./home.php&quot;)">Albumy</a>
                </li>
                <li>
                    <a onclick="fadeOut(&quot;./utworz.php&quot;)">Utwórz album</a>
                </li>
                <li>
                    <a onclick="fadeOut(&quot;./dodaj.php&quot;)">Dodaj monetę</a>
                </li>
                <li>
                    <a onclick="fadeOut(&quot;./admin.php&quot;)">Panel administratora</a>
                </li>
                <li class="align-right">
                    <a onclick="fadeOut(&quot;./index.php&quot;)">Wyloguj się</a>
                </li>
            </ul>
        </div>
        <div id="main">
            <div id='settings'>
                <p style='margin-bottom:0.5rem'>Dostępne albumy:</p>
                <ul>
                    <?php
                    $polaczenie = mysqli_connect('localhost', 'root', '', 'monety');
                    $showQuery = "SHOW TABLES WHERE tables_in_monety NOT LIKE 'uzytkownicy'";
                    $tablesQuery = mysqli_query($polaczenie, $showQuery);
                    if (mysqli_num_rows($tablesQuery) == 0) {
                        echo "<p style='text-align: center'>Brak albumów w bazie.</p>";
                    } else {
                        while ($tables = mysqli_fetch_row($tablesQuery)) {
                            echo "<li class='list-item'><a class='anchor-item' onclick=fadeOut('./album.php?nazwa=" . $tables[0] . "&admin=yes')>" . $tables[0] . "</a></li>";
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</body>