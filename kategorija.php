<?php
session_start();
include 'connect.php';

$kategorija = isset($_GET['kategorija']) ? $_GET['kategorija'] : '';

if (!$kategorija) {
    die("Nije odabrana kategorija.");
}

$query = "SELECT * FROM clanci WHERE kategorija = ? AND prikaz = 1 ORDER BY datum_unosa DESC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $kategorija);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$clanci = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo htmlspecialchars($kategorija); ?></title>
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
    <h1><?php echo htmlspecialchars($kategorija); ?></h1>
    <?php if ($clanci): ?>
        <div class="articles-wrapper">
            <?php foreach ($clanci as $clanak): ?>
                <a href="clanak.php?id=<?php echo $clanak['id']; ?>" class="article-link">
                    <article>
                        <img src="img/<?php echo htmlspecialchars($clanak['slika']); ?>" alt="<?php echo htmlspecialchars($clanak['naslov']); ?>" />
                        <h3><?php echo htmlspecialchars($clanak['naslov']); ?></h3>
                        <h4><?php echo date('d. F Y.', strtotime($clanak['datum_unosa'])); ?></h4>
                        <p><?php echo htmlspecialchars($clanak['sazetak']); ?></p>
                    </article>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Nema vijesti u odabranoj kategoriji.</p>
    <?php endif; ?>
</main>

<footer></footer>
</body>
</html>
