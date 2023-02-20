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
    <?php
    if (isset($_POST['deleteCheck'])) {
        $polaczenie = mysqli_connect('localhost', 'root', '', 'monety');
        $deleteQ = "DELETE FROM `" . $_SESSION['album'] . "` WHERE nazwa = '" . $_SESSION['nazwa'] . "' LIMIT 1;";
        $query = mysqli_query($polaczenie, $deleteQ);
        mysqli_close($polaczenie);
        header("Location: album.php?nazwa=" . $_SESSION['album'] . "&admin=yes");
    }
    ?>
    <script src="./script/main.js"></script>
    <script src="script/moneta.js"></script>
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
            <div id="banner-bottom">
                <?php
                $backLink = str_replace(' ', '%20', $_SESSION['album']);
                if (isset($_SESSION['admin']) and $_SESSION['admin'] == "yes") {
                    echo "<a onclick=fadeOut('./album.php?nazwa=" . $backLink . "&admin=yes')>Powrót do albumu: " . $_SESSION['album'] . "</a>";
                } else {
                    echo "<a onclick=fadeOut('./album.php?nazwa=" . $backLink . "')>Powrót do albumu: " . $_SESSION['album'] . "</a>";
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
                    $showQuery = "SELECT nazwa, opis, awers, rewers FROM `" . $_SESSION['album'] . "` WHERE nazwa='" . $_SESSION['nazwa'] . "';";
                    $query = mysqli_query($polaczenie, $showQuery);
                    $row = mysqli_fetch_row($query);
                    if (isset($_FILES['awers'])) {
                        echo "<span id='img-magnifier-glass-wrap-top'><img id='img-top' src='images/" . $_SESSION['album'] . "/" . basename($_FILES['awers']['name']) . "'></span>";
                        echo "<span id='img-magnifier-glass-wrap-bot'><img id='img-bot' src='images/" . $_SESSION['album'] . "/" . basename($_FILES['rewers']['name']) . "'></span>";
                    } else {
                        echo "<span id='img-magnifier-glass-wrap-top'><img id='img-top' src='images/" . $_SESSION['album'] . "/" . $row[2] . "'></span>";
                        echo "<span id='img-magnifier-glass-wrap-bot'><img id='img-bot' src='images/" . $_SESSION['album'] . "/" . $row[3] . "'></span>";
                    }
                    ?>
                </div>
            </div>
            <div id="side">
                <?php
                if (isset($_SESSION['admin']) and $_SESSION['admin'] == "yes") {
                    $adminQuery = "SELECT nazwa, opis FROM `" . $_SESSION['album'] . "` WHERE nazwa='" . $_SESSION['nazwa'] . "';";
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
                        echo '<textarea required name="opis" id="opis">' . $_POST['opis'] . '</textarea>';
                    else
                        echo '<textarea required name="opis" id="opis">' . $row[1] . '</textarea>';
                    echo "</span>";
                    echo <<<EOL
                    <span class="input" style="margin-bottom: 0.5rem">
                    <label for="awers">Awers:</label>
                    <input required type="file" name="awers" id="awers" accept=".jpg,.jpeg,.png,.jfif">
                    </span>
                    <span class="input" style="margin-bottom: 1rem">
                    <label for="rewers">Rewers:</label>
                    <input required type="file" name="rewers" id="rewers" accept=".jpg,.jpeg,.png,.jfif">
                    </span>
                    EOL;
                    echo "<input id='deleteCheck' name='deleteCheck' type='checkbox' style='display: none'>";
                    echo "<button type='button' id='delete' formmethod='post' form='form' style='margin-bottom: 1rem; width: 100%' onclick='usun()'>Usuń monetę</button>";
                    echo "<input type='submit' id='confirm' value='Zaakceptuj zmiany' style='margin-bottom: 1.5rem; width: 100%'>";
                    echo "</form>";
                    if (isset($_POST['nazwa'])) {
                        $deleteQuery = "SELECT awers, rewers FROM `" . $_SESSION['album'] . "` WHERE nazwa='" . $_SESSION['nazwa'] . "';";
                        $deleteQ = mysqli_query($polaczenie, $deleteQuery);
                        $row = mysqli_fetch_row($deleteQ);
                        $oldAwers = "images/". strval($_SESSION['album']) . "/" . $row[0];
                        $oldRewers = "images/". strval($_SESSION['album']) . "/" . $row[1];
                        unlink($oldAwers);
                        unlink($oldRewers);
                        $updateQuery = "UPDATE `" . $_SESSION['album'] . "` SET nazwa = '" . $_POST['nazwa'] . "', opis='" . $_POST['opis'] . "', awers='".$_FILES['awers']['name']."', rewers='".$_FILES['rewers']['name']."' WHERE nazwa='" . $_SESSION['nazwa'] . "' LIMIT 1;";
                        $target_awers = "images/" . strval($_SESSION['album']) . "/" . basename($_FILES['awers']['name']);
                        $target_rewers = "images/" . strval($_SESSION['album']) . "/" . basename($_FILES['rewers']['name']);
                        $fileType_awers = pathinfo($target_awers, PATHINFO_EXTENSION);
                        $fileType_rewers = pathinfo($target_rewers, PATHINFO_EXTENSION);
                        $allowed = array('jpg', 'jpeg', 'png', 'jfif', 'JPG', 'JPEG', 'PNG', 'JFIF');
        
                        if (in_array($fileType_awers, $allowed) and in_array($fileType_rewers, $allowed)) {
                            move_uploaded_file($_FILES['awers']['tmp_name'], $target_awers);
                            move_uploaded_file($_FILES['rewers']['tmp_name'], $target_rewers);
                        }
                        $query3 = mysqli_query($polaczenie, $updateQuery);
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
                    <div id="lupa-top" style='margin-bottom:0.7rem'>
                        <span>
                            <label for="magnify-top">Włącz lupę dla awersu</label>
                            <input type="checkbox" name="magnify-top" id="magnify-top" oninput=checkMagnify("top")>
                        </span>
                        <span class='lupa-value'>
                            <input type="range" value=2 min=1.5 max=5 step=0.5 oninput=changeMagnifyTop(this.value)
                                name="strength-top" id="strength-top">
                            <span id='magnify-value-top' style='padding:15px'>2x</span>
                        </span>
                    </div>
                    <div id="lupa-bot">
                        <span>
                            <label for="magnify-bot">Włącz lupę dla rewersu</label>
                            <input type="checkbox" name="magnify-bot" id="magnify-bot" oninput=checkMagnify("bot")>
                        </span>
                        <span class='lupa-value'>
                            <input type="range" value=2 min=1.5 max=5 step=0.5 oninput=changeMagnifyBot(this.value)
                                name="strength-bot" id="strength-bot">
                            <span id='magnify-value-bot' style='padding:15px'>2x</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function checkMagnify(value) {
            if (value == "top") {
                if (!document.getElementById('magnify-top').checked) {
                    document.getElementById('img-magnifier-glass-top').remove()
                    return
                }
                if (document.getElementById('magnify-bot').checked) {
                    document.getElementById('img-magnifier-glass-bot').remove()
                    document.getElementById('magnify-bot').checked = false
                }
                magnify('top', 'img-top', 2)
            } else {
                if (!document.getElementById('magnify-bot').checked) {
                    document.getElementById('img-magnifier-glass-bot').remove()
                    return
                }
                if (document.getElementById('magnify-top').checked) {
                    document.getElementById('img-magnifier-glass-top').remove()
                    document.getElementById('magnify-top').checked = false
                }
                magnify('bot', 'img-bot', 2)
            }
        }
    </script>
</body>

</html>