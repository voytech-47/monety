<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Album monet</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style_form.css">
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
            <div id="form">
                <form action="dodaj.php" method="post" enctype="multipart/form-data">
                    <h2>Wprowadź informacje dotyczące nowej monety:</h2>
                    <span class="input">
                        <label for="nazwa" id="nazwa-label">Nazwa:</label>
                        <input required type="text" name="nazwa" id="nazwa">
                    </span>
                    <span class="input">
                        <label for="awers">Awers:</label>
                        <input required type="file" name="awers" id="awers" accept=".jpg,.jpeg,.png,.jfif">
                    </span>
                    <span class="input">
                        <label for="rewers">Rewers:</label>
                        <input required type="file" name="rewers" id="rewers" accept=".jpg,.jpeg,.png,.jfif">
                    </span>
                    <span class="input input-textarea">
                        <label for="opis" id="opis-label">Opis:</label>
                        <textarea required name="opis" id="opis"></textarea>
                    </span>
                    <span class="input">
                        <label for="album">Wybierz album:</label>
                        <?php
                        $polaczenie = mysqli_connect('localhost', 'root', '', 'monety');
                        $showQuery = "SHOW TABLES";
                        $query = mysqli_query($polaczenie, $showQuery);
                        if (mysqli_num_rows($query) == 0) {
                            echo "<select name='album' id='album' disabled>";
                            echo "<option value='brak' selected>brak albumów</option>";
                            echo "</select>";
                            echo "</span>";
                            echo "<input type='submit' value='Dodaj monetę' disabled>";
                        } else {
                            echo "<select name='album' id='album'>";
                            while ($row = mysqli_fetch_row($query)) {
                                echo "<option value='" . $row[0] . "'>" . $row[0] . "</option>";
                            }
                            echo "</select>";
                            echo "</span>";
                            echo "<input type='submit' value='Dodaj monetę'>";
                        }
                        ?>
                </form>
                <?php
                if (!isset($_POST['nazwa']) or !isset($_POST['opis'])) {
                    return;
                }
                $insertQuery = "INSERT INTO `" . strval($_POST['album']) . "` (`id`, `nazwa`, `opis`, `awers`, `rewers`) VALUES (NULL, '" . strval($_POST['nazwa']) . "', '" . strval($_POST['opis']) . "', '" . $_FILES['awers']['name'] . "', '" . $_FILES['rewers']['name'] . "');";
                $target_awers = "images/" . strval($_POST['album']) . "/" . basename($_FILES['awers']['name']);
                $target_rewers = "images/" . strval($_POST['album']) . "/" . basename($_FILES['rewers']['name']);
                $fileType_awers = pathinfo($target_awers, PATHINFO_EXTENSION);
                $fileType_rewers = pathinfo($target_rewers, PATHINFO_EXTENSION);
                $allowed = array('jpg', 'jpeg', 'png', 'jfif');

                if (in_array($fileType_awers, $allowed) and in_array($fileType_rewers, $allowed)) {
                    move_uploaded_file($_FILES['awers']['tmp_name'], $target_awers);
                    move_uploaded_file($_FILES['rewers']['tmp_name'], $target_rewers);
                }
                mysqli_query($polaczenie, $insertQuery);
                echo "<p style='margin-top: 2rem'>Dodano monetę " . strval($_POST['nazwa']) . " do katalogu " . strval($_POST['album']) . ".</p>";
                mysqli_close($polaczenie);
                ?>
            </div>
        </div>
    </div>
    <script src="script/main.js"></script>
</body>

</html>