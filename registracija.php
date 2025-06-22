<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ime = trim($_POST['ime']);
    $prezime = trim($_POST['prezime']);
    $korisnicko_ime = trim($_POST['korisnicko_ime']);
    $lozinka = $_POST['lozinka'];
    $lozinka2 = $_POST['lozinka2'];

    if ($lozinka !== $lozinka2) {
        $error = "Lozinke se ne podudaraju.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM korisnik WHERE korisnicko_ime = ?");
        $stmt->bind_param("s", $korisnicko_ime);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Korisničko ime već postoji.";
        } else {
            $hash_lozinka = password_hash($lozinka, PASSWORD_DEFAULT);
            $stmt_insert = $conn->prepare("INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka, razina) VALUES (?, ?, ?, ?, 1)");
            $stmt_insert->bind_param("ssss", $ime, $prezime, $korisnicko_ime, $hash_lozinka);

            if ($stmt_insert->execute()) {
                $success = "Uspješna registracija! <a href='login.php'>Prijavite se</a>";
            } else {
                $error = "Greška prilikom registracije.";
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8" />
    <title>Registracija</title>
</head>
<body>
    <h1>Registracija</h1>
    <?php
    if (isset($error)) echo "<p style='color:red;'>$error</p>";
    if (isset($success)) echo "<p style='color:green;'>$success</p>";
    ?>
    <form method="post" id="forma" action="">
        <div class="form-field">
            <label for="ime">Ime:</label><br>
            <input type="text" name="ime" id="ime" required>
            <span id="porukaIme" class="bojaPoruke"></span>
        </div>

        <div class="form-field">
            <label for="prezime">Prezime:</label><br>
            <input type="text" name="prezime" id="prezime" required>
            <span id="porukaPrezime" class="bojaPoruke"></span>
        </div>

        <div class="form-field">
            <label for="korisnicko_ime">Korisničko ime:</label><br>
            <input type="text" name="korisnicko_ime" id="korisnicko_ime" required>
            <span id="porukaUsername" class="bojaPoruke"></span>
        </div>

        <div class="form-field">
            <label for="lozinka">Lozinka:</label><br>
            <input type="password" name="lozinka" id="lozinka" required>
            <span id="porukaPass" class="bojaPoruke"></span>
        </div>

        <div class="form-field">
            <label for="lozinka2">Ponovite lozinku:</label><br>
            <input type="password" name="lozinka2" id="lozinka2" required>
            <span id="porukaPassRep" class="bojaPoruke"></span>
        </div>

        <button type="submit" id="slanje">Registriraj se</button>
    </form>

    <p><a href="login.php">Već imate račun? Prijavite se</a></p>
    <script>
    document.getElementById("forma").addEventListener("submit", function(event) {
        let valid = true;

        const ime = document.getElementById("ime");
        if (ime.value.trim() === "") {
            valid = false;
            ime.style.border = "1px solid red";
            document.getElementById("porukaIme").textContent = "Unesite ime.";
        } else {
            ime.style.border = "1px solid green";
            document.getElementById("porukaIme").textContent = "";
        }

        const prezime = document.getElementById("prezime");
        if (prezime.value.trim() === "") {
            valid = false;
            prezime.style.border = "1px solid red";
            document.getElementById("porukaPrezime").textContent = "Unesite prezime.";
        } else {
            prezime.style.border = "1px solid green";
            document.getElementById("porukaPrezime").textContent = "";
        }

        const korisnickoIme = document.getElementById("korisnicko_ime");
        if (korisnickoIme.value.trim() === "") {
            valid = false;
            korisnickoIme.style.border = "1px solid red";
            document.getElementById("porukaUsername").textContent = "Unesite korisničko ime.";
        } else {
            korisnickoIme.style.border = "1px solid green";
            document.getElementById("porukaUsername").textContent = "";
        }

        const lozinka = document.getElementById("lozinka");
        const lozinka2 = document.getElementById("lozinka2");
        if (lozinka.value === "" || lozinka2.value === "" || lozinka.value !== lozinka2.value) {
            valid = false;
            lozinka.style.border = "1px solid red";
            lozinka2.style.border = "1px solid red";
            document.getElementById("porukaPass").textContent = "Lozinke nisu iste.";
            document.getElementById("porukaPassRep").textContent = "Lozinke nisu iste.";
        } else {
            lozinka.style.border = "1px solid green";
            lozinka2.style.border = "1px solid green";
            document.getElementById("porukaPass").textContent = "";
            document.getElementById("porukaPassRep").textContent = "";
        }

        if (!valid) {
            event.preventDefault(); 
        }
    });
    </script>
</body>
</html>
