<?php
session_start();
include 'connect.php';

if (isset($_SESSION['korisnicko_ime'])) {
    if ($_SESSION['razina'] == 2) {
        header("Location: administrator.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $korisnicko_ime = $_POST['korisnicko_ime'];
    $lozinka = $_POST['lozinka'];

    $stmt = $conn->prepare("SELECT korisnicko_ime, lozinka, razina FROM korisnik WHERE korisnicko_ime = ?");
    $stmt->bind_param("s", $korisnicko_ime);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($lozinka, $user['lozinka'])) {
            $_SESSION['korisnicko_ime'] = $user['korisnicko_ime'];
            $_SESSION['razina'] = $user['razina'];
            if ($_SESSION['razina'] == 2) {
                header("Location: administrator.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Neispravna lozinka!";
        }
    } else {
        $error = "Korisnik ne postoji. Registrirajte se <a href='registracija.php'>ovdje</a>.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8" />
    <title>Prijava</title>
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
    <h1>Prijava</h1>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" action="">
        Korisničko ime: <input type="text" name="korisnicko_ime" required><br><br>
        Lozinka: <input type="password" name="lozinka" required><br><br>
        <button type="submit">Prijavi se</button>
    </form>
    <p>Niste registrirani? <a href="registracija.php">Registrirajte se ovdje</a>.</p>
    <footer></footer>
</body>
</html>
