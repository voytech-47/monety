<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Album monet</title>
    <link rel="stylesheet" href="style_utworz.css">
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
            <div id="form">
                <form action="utworz.php" method="post" enctype="multipart/form-data">
                    <h2>Wprowadź informacje dotyczące nowego albumu:</h2>
                    <span class="input">
                        <label for="nazwa" id="nazwa-label">Nazwa: </label>
                        <input type="text" name="nazwa" id="nazwa">
                    </span>
                    <span class="input input-textarea">
                        <label for="opis" id="opis-label">Opis: </label>
                        <textarea name="opis" id="opis"></textarea>
                    </span>
                    <input type="submit" value="Dodaj album">
                </form>
            </div>
        </div>
    </div>
    <script src="main.js"></script>
</body>

</html>