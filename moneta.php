<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Album monet</title>
    <link rel="stylesheet" href="style_moneta.css">
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
            <div class="panel">
                <div class="panel-title">
                    <h1>Lorem</h1>
                </div>
                <div class="panel-main">
                    <img id="img-top" src="images/5-pln-front.jpg" alt="" srcset="">
                    <img id="img-bot" src="images/5-pln-back.jpg" alt="5-pln-front">
                </div>
            </div>
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
    <script src="moneta.js"></script>
</body>

</html>