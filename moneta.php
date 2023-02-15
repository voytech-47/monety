<?php
session_start();
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
                    echo <<< EOL
                        <li>
                            <a onclick=fadeOut("./utworz.php")>Utwórz album</a>
                        </li>
                        <li>
                            <a onclick=fadeOut("./dodaj.php")>Dodaj monetę</a>
                        </li>
                        <li>
                            <a onclick=fadeOut("./index.php")>Wyloguj się</a>
                        </li>
                        EOL;
                } else {
                    echo <<< EOL
                        <li>
                            <a onclick=fadeOut("./index.php")>Zaloguj się</a>
                        </li>
                        EOL;
                }
                ?>
            </ul>
            <div id="banner-bottom">
            <?php
                echo "<a onclick=fadeOut('./album.php?nazwa=".$_GET['album']."')>Album: ".$_GET['album']."</a>";
            ?>
            </div>
        </div>
        <div id="main">
            <div id="back">
            <?php
                echo "<a onclick=fadeOut('./album.php?nazwa=".$_GET['album']."')>Powrót do albumu</a>";
            ?>  
            </div>
            <div class="panel">
                <div class="panel-title">
                    <?php
                        echo "<h1>".$_GET['nazwa']."</h1>";
                    ?>
                </div>
                <div class="panel-main">
                    <?php
                        $polaczenie = mysqli_connect('localhost', 'root', '', 'monety');
                        $showQuery = "SELECT nazwa, opis, awers, rewers FROM `" . $_GET['album'] . "` WHERE nazwa='".$_GET['nazwa']."';";
                        $query = mysqli_query($polaczenie, $showQuery);
                        $row = mysqli_fetch_row($query);
                        echo "<img id='img-top' src='images/".$_GET['album']."/".$row[2]."'>";
                        echo "<img id='img-bot' src='images/".$_GET['album']."/".$row[3]."'>";
                    ?>
                </div>
            </div>
            <?php
                echo "<p style='margin-top:1rem; margin-bottom:1.5rem'>Opis: ".$row[1]."</p>";
            ?>
            <div id="settings">
                <div id="brightness">
                    <span>
                        <label for="brightness">Janość: </label>
                        <span id="brightness_value">100%</span>
                    </span>
                    <input min=50 max=200 value=100 type="range" name="brightness" id="brightness_input" oninput="changeBrightness(this.value)">
                </div>
                <div id="contrast">
                    <span>
                        <label for="contrast">Kontrast: </label>
                        <span id="contrast_value">100%</span>
                    </span>
                    <input min=50 max=200 value=100 type="range" name="contrast" id="contrast_input" oninput="changeContrast(this.value)">
                </div>
            </div>
        </div>
    </div>    
</body>

</html>