<?php
session_start();
if (!$_SESSION['zalogowany']) {
    ?>
    <script type="text/javascript">
        window.location.href = 'index.php'
    </script>
    <?php
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Album monet</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style_form.css">
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
        </div>
        <div id="main">
            <div id="form">
                <form action="dodaj.php" method="post" enctype="multipart/form-data">
                    <h2>Wprowadź informacje dotyczące nowej monety:</h2>
                    <span class="input-reversed">
                        <input required type="text" name="nazwa" id="nazwa" oninput="checkValidation(this, 't')">
                        <label for="nazwa" id="nazwa-label">Nazwa:</label>
                    </span>
                    <span class="input">
                        <label for="cena" id="cena-label">Cena zakupu:</label>
                        <input type="text" name="cena" id="cena">
                    </span>
                    <span class="input">
                        <label for="wartosc" id="wartosc-label">Wartość:</label>
                        <input type="text" name="wartosc" id="wartosc">
                    </span>
                    <!-- <span class="input">
                        <label for="awers">Awers:</label>
                        <input required type="file" name="awers" id="awers" accept=".jpg,.jpeg,.png,.jfif">
                    </span>
                    <span class="input">
                        <label for="rewers">Rewers:</label>
                        <input required type="file" name="rewers" id="rewers" accept=".jpg,.jpeg,.png,.jfif">
                    </span> -->
                    <span class="input input-textarea">
                        <label for="opis" id="opis-label">Opis:</label>
                        <textarea name="opis" id="opis"></textarea>
                    </span>
                    <span class="input">
                        <label for="zdjecia" id="zdjecia-label">Dodaj zdjęcia: </label>
                        <input required multiple type="file" name="zdjecia[]" id="zdjecia"
                            accept=".jpg,.jpeg,.png,.jfif" oninput="checkValidation(this, 'f')">
                    </span>
                    <span class="input">
                        <label for="album">Wybierz album:</label>
                        <?php
                        $polaczenie = mysqli_connect('localhost', 'root', '', 'monety');
                        $showQuery = "SHOW TABLES WHERE tables_in_monety NOT LIKE 'uzytkownicy'";
                        $query = mysqli_query($polaczenie, $showQuery);
                        if (mysqli_num_rows($query) == 0) {
                            echo "<select name='album' id='album' disabled>";
                            echo "<option value='brak' selected>brak albumów</option>";
                            echo "</select>";
                            echo "</span>";
                            echo "<input type='submit' id='submit' value='Dodaj przedmiot' disabled>";
                        } else {
                            echo "<select name='album' id='album'>";
                            while ($row = mysqli_fetch_row($query)) {
                                echo "<option value='" . $row[0] . "'>" . $row[0] . "</option>";
                            }
                            echo "</select>";
                            echo "</span>";
                            echo "<input disabled type='submit' id='submit' value='Dodaj przedmiot'>";
                        }
                        ?>
                </form>
                <?php
                if (isset($_POST['nazwa']) or isset($_POST['opis'])) {
                    if (isset($_POST['cena']) and $_POST['cena'] == null) {
                        $cena = 0;
                    } else {
                        $cena = strval($_POST['cena']);
                    }
                    if (isset($_POST['wartosc']) and $_POST['wartosc'] == null) {
                        $wartosc = 0;
                    } else {
                        $wartosc = strval($_POST['wartosc']);
                    }
                    $insertQuery = "INSERT INTO `" . strval($_POST['album']) . "` VALUES (NULL, '" . strval($_POST['nazwa']) . "', '" . strval($cena) . "', '" . strval($wartosc) . "', '" . strval($_POST['opis']) . "', ";
                    $allowed = array('jpg', 'jpeg', 'png', 'jfif', 'JPG', 'JPEG', 'PNG', 'JFIF');
                    for ($i = 0; $i < 5; $i++) {
                        if ($i + 1 > count($_FILES['zdjecia']['name'])) {
                            $insertQuery .= "'', ";
                            continue;
                        }
                        $target_photo = "images/" . strval($_POST['album']) . "/" . basename($_FILES['zdjecia']['name'][$i]);
                        $fileType_photo = pathinfo($target_photo, PATHINFO_EXTENSION);
                        if (in_array($fileType_photo, $allowed)) {
                            move_uploaded_file($_FILES['zdjecia']['tmp_name'][$i], $target_photo);
                        }
                        $insertQuery .= "'" . $_FILES['zdjecia']['name'][$i] . "', ";
                    }
                    $insertQuery .= "NOW());";
                    // echo $insertQuery;
                    mysqli_query($polaczenie, $insertQuery);
                    echo "<p style='margin-top: 2rem'>Dodano monetę <i>" . strval($_POST['nazwa']) . "</i> do katalogu <i>" . strval($_POST['album']) . "</i>.</p>";
                    mysqli_close($polaczenie);
                }
                ?>
            </div>
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
</body>

</html>