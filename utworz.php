<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Album monet</title>
    <link rel="stylesheet" href="styles/style_utworz.css">
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
                        <input required type="text" name="nazwa" id="nazwa">
                    </span>
                    <input type="submit" value="Dodaj album">
                </form>
                <?php
                // ini_set('display_errors', 0);
                $polaczenie = mysqli_connect('localhost', 'root', '', 'monety');
                if (mysqli_connect_errno()) {
                    echo "<p>error</p>";
                }
                if (!isset($_POST['nazwa'])) {
                    return;
                }
                $createQuery = "CREATE TABLE " . $_POST['nazwa'] . " (
                        `id` INT NOT NULL AUTO_INCREMENT,
                        `nazwa` VARCHAR(255) NOT NULL,
                        `opis` TEXT NOT NULL,
                        `awers` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                        `rewers` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE = InnoDB;";

                $flag = 1;

                try {
                    $query = mysqli_query($polaczenie, "SELECT 1 FROM " . $_POST['nazwa'] . " LIMIT 1;");
                } catch (Exception $e) {
                    $createTable = mysqli_query($polaczenie, $createQuery);
                    if (!file_exists("images/" . $_POST['nazwa'])) {
                        mkdir("images/" . $_POST['nazwa'], 0777, true);
                    }
                    echo "<p style='margin-top: 2rem;'>Dodano album o nazwie " . $_POST['nazwa'] . ".</p>";
                    $flag = 0;
                }

                if ($flag) {
                    echo "<p style='margin-top: 2rem;'>Istnieje już album o nazwie " . $_POST['nazwa'] . ".</p>";
                }
                ?>
            </div>
            <div>
                <p style="margin-top: 1rem;">Obecne albumy:</p>
                <ul>
                    <?php
                    $showQuery = "SHOW TABLES;";
                    $query = mysqli_query($polaczenie, $showQuery);
                    while ($row = mysqli_fetch_row($query)) {
                        echo "<li>" . $row[0] . "</li>";
                    }
                    mysqli_close($polaczenie);
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <script src="script/main.js"></script>
</body>

</html>