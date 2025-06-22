<?php

session_start();
include 'connect.php';

if (!isset($_SESSION['korisnicko_ime'])) {
    header("Location: login.php");
    exit;
} else {
    if ($_SESSION['razina'] != 2) {
        echo "<p>{$_SESSION['korisnicko_ime']}, nemate dovoljna prava za pristup ovoj stranici.</p>";
        echo '<a href="logout.php">Odjava</a>';
        exit;
    }
}

if (!isset($_SESSION['korisnicko_ime']) || $_SESSION['razina'] != 2) {
    echo "<p>Niste prijavljeni kao administrator.</p>";
    echo '<a href="login.php">Prijava</a>';
    exit;
}


if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = mysqli_prepare($conn, "DELETE FROM clanci WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: administrator.php");
    exit;
}


if (isset($_POST['edit_id'])) {
    $id = (int)$_POST['edit_id'];
    $naslov = mysqli_real_escape_string($conn, $_POST['naslov']);
    $sazetak = mysqli_real_escape_string($conn, $_POST['sazetak']);
    $tekst = mysqli_real_escape_string($conn, $_POST['tekst']);
    $kategorija = mysqli_real_escape_string($conn, $_POST['kategorija']);
    $prikaz = isset($_POST['prikaz']) && $_POST['prikaz'] === 'da' ? 1 : 0;
    $datum_unosa = mysqli_real_escape_string($conn, $_POST['datum_unosa']);
    

    $update_query = "UPDATE clanci SET 
                        naslov = '$naslov',
                        sazetak = '$sazetak',
                        tekst = '$tekst',
                        kategorija = '$kategorija',
                        prikaz = $prikaz,
                        datum_unosa = '$datum_unosa'
                    WHERE id = $id";
    mysqli_query($conn, $update_query);
    header("Location: administrator.php");
    exit;
}

$clanakZaUredi = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $query = "SELECT * FROM clanci WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $query);
    $clanakZaUredi = mysqli_fetch_assoc($result);
}


$query = "SELECT * FROM clanci ORDER BY datum_unosa DESC";
$result = mysqli_query($conn, $query);

$clanci = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Administracija vijesti</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<header class="main-header">
    <nav>
        <ul>
            <li><a href="index.php">HOME</a></li>
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
    <h1>Administracija vijesti</h1>
    <?php if ($clanakZaUredi): ?>
        <h2>Uredi vijest ID: <?php echo $clanakZaUredi['id']; ?></h2>
        <form action="administrator.php" method="POST" autocomplete="off">
            <input type="hidden" name="edit_id" value="<?php echo $clanakZaUredi['id']; ?>" />

            <label for="naslov">Naslov:</label><br />
            <input type="text" id="naslov" name="naslov" value="<?php echo htmlspecialchars($clanakZaUredi['naslov']); ?>" required /><br /><br />

            <label for="sazetak">Sažetak:</label><br />
            <textarea id="sazetak" name="sazetak" rows="4" required><?php echo htmlspecialchars($clanakZaUredi['sazetak']); ?></textarea><br /><br />

            <label for="tekst">Tekst vijesti:</label><br />
            <textarea id="tekst" name="tekst" rows="8" required><?php echo htmlspecialchars($clanakZaUredi['tekst']); ?></textarea><br /><br />

            <label for="kategorija">Kategorija:</label><br />
            <select id="kategorija" name="kategorija" required>
                <option value="Glazba" <?php if ($clanakZaUredi['kategorija'] == 'Glazba') echo 'selected'; ?>>Glazba</option>
                <option value="E-Sport" <?php if ($clanakZaUredi['kategorija'] == 'E-Sport') echo 'selected'; ?>>E-sport</option>
            </select><br /><br />

            <input type="checkbox" id="prikaz" name="prikaz" value="da" <?php if ($clanakZaUredi['prikaz']) echo 'checked'; ?> />
            <label for="prikaz">Prikaži vijest na stranici</label><br /><br />

            <label for="datum_unosa">Datum unosa:</label><br />
            <input type="datetime-local" id="datum_unosa" name="datum_unosa" 
                value="<?php 
                    echo date('Y-m-d\TH:i', strtotime($clanakZaUredi['datum_unosa'])); 
                ?>" required /><br /><br />

            <button type="submit">Spremi promjene</button>
            <a href="administrator.php">Odustani</a>
        </form>
        <hr />
    <?php endif; ?>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Naslov</th>
                <th>Kategorija</th>
                <th>Datum unosa</th>
                <th>Prikaz</th>
                <th>Uredi</th>
                <th>Obriši</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clanci as $clanak): ?>
                <tr>
                    <td><?php echo $clanak['id']; ?></td>
                    <td><?php echo htmlspecialchars($clanak['naslov']); ?></td>
                    <td><?php echo htmlspecialchars($clanak['kategorija']); ?></td>
                    <td><?php echo htmlspecialchars($clanak['datum_unosa']); ?></td>
                    <td><?php echo $clanak['prikaz'] ? 'Da' : 'Ne'; ?></td>
                    <td><a href="administrator.php?edit=<?php echo $clanak['id']; ?>">Uredi</a></td>
                    <td><a href="administrator.php?delete=<?php echo $clanak['id']; ?>" onclick="return confirm('Jeste li sigurni?')">Obriši</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<footer></footer>
</body>
</html>
