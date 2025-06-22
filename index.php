<?php
session_start();
include 'connect.php';

$query = "SELECT * FROM clanci WHERE prikaz = 1 ORDER BY datum_unosa DESC";
$result = mysqli_query($conn, $query);

$glazba = [];
$esport = [];

while ($row = mysqli_fetch_assoc($result)) {
    if ($row['kategorija'] === 'Glazba') {
        $glazba[] = $row;
    } elseif ($row['kategorija'] === 'E-Sport') {
        $esport[] = $row;
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Početna Stranica</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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

<section id="glazba">
    <header class="section-header">
        <h2>GLAZBA</h2>
    </header>
    <div class="articles-wrapper">
        <?php foreach ($glazba as $clanak): ?>
            <a href="clanak.php?id=<?php echo $clanak['id']; ?>" class="article-link">
                <article>
                    <img src="img/<?php echo htmlspecialchars($clanak['slika']); ?>" alt="<?php echo htmlspecialchars($clanak['naslov']); ?>" />
                    <h3><?php echo htmlspecialchars($clanak['naslov']); ?></h3>
                    <h4><?php echo date('d. F Y.', strtotime($clanak['datum_unosa'])); ?></h4>
                </article>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section id="esport">
    <header class="section-header2">
        <h2>E-SPORT</h2>
    </header>
    <div class="articles-wrapper">
        <?php foreach ($esport as $clanak): ?>
            <a href="clanak.php?id=<?php echo $clanak['id']; ?>" class="article-link">
                <article>
                    <img src="img/<?php echo htmlspecialchars($clanak['slika']); ?>" alt="<?php echo htmlspecialchars($clanak['naslov']); ?>" />
                    <h3><?php echo htmlspecialchars($clanak['naslov']); ?></h3>
                    <h4><?php echo date('d. F Y.', strtotime($clanak['datum_unosa'])); ?></h4>
                </article>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<footer></footer>
</body>
</html>
