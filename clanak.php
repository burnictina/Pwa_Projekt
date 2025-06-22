<?php
session_start();
include 'connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT * FROM clanci WHERE id = $id AND prikaz = 1";
$result = mysqli_query($conn, $query);
$clanak = mysqli_fetch_assoc($result);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo htmlspecialchars($clanak['naslov'] ?? 'Nepostojeći članak'); ?></title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <header class="main-header">
        <nav>
            <ul>
                <li><a href="index.php">HOME</a></li>
                <li><a href="kategorija.php?kategorija=Glazba">GLAZBA</a></li>
                <li><a href="kategorija.php?kategorija=E-Sport">E-SPORT</a></li>
                <li>
                    <?php 
                    if (isset($_SESSION['korisnicko_ime'])) {
                        if ($_SESSION['razina'] == 2) {
                            echo '<a href="administrator.php">ADMINISTRACIJA</a> | <a href="logout.php">ODJAVA</a>';
                        } else {
                            echo '<a href="logout.php">ODJAVA</a>';
                        }
                    } else {
                        echo '<a href="login.php">LOGIN</a>';
                    }
                    ?>
                </li>
                <?php if (isset($_SESSION['korisnicko_ime'])): ?>
                    <li><a href="unos.php">UNOS</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <?php if ($clanak): ?>
            <section class="clanak">
                <h1><?php echo htmlspecialchars($clanak['naslov']); ?></h1>
                <h4><?php echo htmlspecialchars($clanak['sazetak']); ?></h4>
                <img src="img/<?php echo htmlspecialchars($clanak['slika']); ?>" alt="Slika" />
                <p><?php echo nl2br(htmlspecialchars($clanak['tekst'])); ?></p>
                <p><strong>Kategorija:</strong> <?php echo htmlspecialchars($clanak['kategorija']); ?></p>
                <p><strong>Datum unosa:</strong> <?php echo htmlspecialchars($clanak['datum_unosa']); ?></p>
            </section>
        <?php else: ?>
            <p style="text-align: center;">Članak nije pronađen ili nije označen za prikaz.</p>
        <?php endif; ?>
    </main>

    <footer></footer>
</body>
</html>
