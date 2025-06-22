<?php session_start(); ?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Unos vijesti</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
         <header class="main-header">
            <nav>
                <ul>
                    <li>
                        <a href="index.php">HOME</a>
                    </li>
                    <li>
                        <a href="kategorija.php?kategorija=Glazba">GLAZBA</a>
                    </li>
                    <li>
                        <a href="kategorija.php?kategorija=E-Sport">E-SPORT</a>
                    </li>
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
            <h1>Unos nove vijesti</h1>
            <form action="skripta.php" method="POST" autocomplete="off" enctype="multipart/form-data">
            
                <label for="naslov">Naslov:</label><br />
                <input type="text" id="naslov" name="naslov" required autofocus /><br /><br />

                <label for="sazetak">Sažetak:</label><br />
                <textarea id="sazetak" name="sazetak" rows="4" cols="50" required></textarea><br /><br />

                <label for="tekst">Tekst vijesti:</label><br />
                <textarea id="tekst" name="tekst" rows="8" cols="50" required></textarea><br /><br />

                <label for="kategorija">Kategorija:</label><br />
                <select id="kategorija" name="kategorija" required>
                    <option value="">Odaberi kategoriju</option>
                    <option value="Glazba">Glazba</option>
                    <option value="E-Sport">E-sport</option>
                </select><br /><br />

                <label for="slika">Odaberi sliku:</label><br />
                <input type="file" id="slika" name="slika" accept="image/*" /><br /><br />

                <input type="checkbox" id="prikaz" name="prikaz" value="da" checked />
                <label for="prikaz">Prikaži vijest na stranici</label><br /><br />

                <label for="datum_unosa">Datum unosa:</label><br />
                <input type="datetime-local" id="datum_unosa" name="datum_unosa" required /><br /><br />


                <button type="submit">Pošalji vijest</button>
            </form>

        </main>

        <footer></footer>

        <script>
            document.querySelector("form").addEventListener("submit", function(event) {
            
            let slanjeForme = true;

            let naslov = document.getElementById("naslov");
            let sazetak = document.getElementById("sazetak");
            let tekst = document.getElementById("tekst");
            let kategorija = document.getElementById("kategorija");
            let slika = document.getElementById("slika");

            function ocistiGreske(polje, porukaId) {
                polje.style.border = "";
                let p = document.getElementById(porukaId);
                if (p) p.remove();
            }

            ocistiGreske(naslov, "porukaNaslov");
            ocistiGreske(sazetak, "porukaSazetak");
            ocistiGreske(tekst, "porukaTekst");
            ocistiGreske(kategorija, "porukaKategorija");
            ocistiGreske(slika, "porukaSlika");

            function ispisiPoruku(polje, poruka, id) {
                let porukaEl = document.createElement("span");
                porukaEl.style.color = "red";
                porukaEl.id = id;
                porukaEl.innerHTML = poruka;
                polje.parentNode.insertBefore(porukaEl, polje.nextSibling);
            }

            if (naslov.value.length < 5 || naslov.value.length > 30) {
                slanjeForme = false;
                naslov.style.border = "1px dashed red";
                ispisiPoruku(naslov, "Naslov mora imati između 5 i 30 znakova!", "porukaNaslov");
            } else {
                naslov.style.border = "1px solid green";
            }

            if (sazetak.value.length < 10 || sazetak.value.length > 100) {
                slanjeForme = false;
                sazetak.style.border = "1px dashed red";
                ispisiPoruku(sazetak, "Sažetak mora imati između 10 i 100 znakova!", "porukaSazetak");
            } else {
                sazetak.style.border = "1px solid green";
            }

            if (tekst.value.trim() === "") {
                slanjeForme = false;
                tekst.style.border = "1px dashed red";
                ispisiPoruku(tekst, "Tekst vijesti ne smije biti prazan!", "porukaTekst");
            } else {
                tekst.style.border = "1px solid green";
            }

            if (kategorija.value === "") {
                slanjeForme = false;
                kategorija.style.border = "1px dashed red";
                ispisiPoruku(kategorija, "Morate odabrati kategoriju!", "porukaKategorija");
            } else {
                kategorija.style.border = "1px solid green";
            }

            if (slika.files.length === 0) {
                slanjeForme = false;
                slika.style.border = "1px dashed red";
                ispisiPoruku(slika, "Morate odabrati sliku!", "porukaSlika");
            } else {
                slika.style.border = "1px solid green";
            }

            if (!slanjeForme) {
                event.preventDefault();
            }
        });
        </script>

    </body>
</html>