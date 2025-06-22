<?php
session_start();
$prikazi = isset($_POST['prikaz']) ? 1 : 0;

include 'connect.php';

$slika_putanja = null;
if (isset($_FILES['slika']) && $_FILES['slika']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['slika']['tmp_name'];
    $ime = basename($_FILES['slika']['name']);
    $folder = 'img/';
    $slika_putanja = $ime;

    if (!is_dir($folder)) mkdir($folder);
    move_uploaded_file($tmp, $folder . $ime);
}


$naslov = $conn->real_escape_string($_POST['naslov']);
$sazetak = $conn->real_escape_string($_POST['sazetak']);
$tekst = $conn->real_escape_string($_POST['tekst']);
$kategorija = $conn->real_escape_string($_POST['kategorija']);
$datum_unosa = $conn->real_escape_string($_POST['datum_unosa']);



$sql = "INSERT INTO clanci (naslov, sazetak, tekst, slika, kategorija, prikaz, datum_unosa)
        VALUES ('$naslov', '$sazetak', '$tekst', '$slika_putanja', '$kategorija', $prikazi, '$datum_unosa')";
$conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Članak</title>
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
        <?php if ($prikazi): ?>
            <section class="clanak">
                <h1><?php echo htmlspecialchars($_POST['naslov']); ?></h1>
                <h4><?php echo htmlspecialchars($_POST['sazetak']); ?></h4>
                <?php if ($slika_putanja): ?>
                    <img src="img/<?php echo htmlspecialchars($slika_putanja); ?>" alt="Slika" />
                <?php endif; ?>
                <p><?php echo nl2br(htmlspecialchars($_POST['tekst'])); ?></p>
                <p><strong>Kategorija:</strong> <?php echo htmlspecialchars($_POST['kategorija']); ?></p>
            </section>
        <?php else: ?>
            <p style="text-align: center;">Vijest nije označena za prikaz na stranici.</p>
        <?php endif; ?>
    </main>

    <footer></footer>
</body>
</html>
