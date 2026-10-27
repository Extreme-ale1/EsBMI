
<?php
if ($_SERVER["REQUEST_METHOD"] == "GET"){
    header("Location: form.html");
    exit;
}

$nome = $_POST["nome"];
$peso = $_POST["peso"];
$altezza = $_POST["altezza"];

// Validazione Dati
$nomeValido = ($nome != "");
$pesoValido = (is_numeric($peso) && $peso > 0);
$altezzaValida = (is_numeric($altezza) && $altezza > 0);

if (!$nomeValido || !$pesoValido || !$altezzaValida){
    exit("Err: inserisci dei dati validi!!!");
}

// Converte l'altezza da centimetri a metri     
$altezza = $altezza / 100;

// Calcola il BMI
$bmi = $peso / ($altezza * $altezza);

// Controlla in quale fascia rientra il BMI
if ($bmi < 18.5) {
    $fascia = "SOTTOPESO";
    $classe = "sottopeso";
} else if ($bmi < 25) {
    $fascia = "NORMOPESO";
    $classe = "normopeso";
} else if ($bmi < 30) {
    $fascia = "SOVRAPPESO";
    $classe = "sovrappeso";
} else {
    $fascia = "OBESITÀ";
    $classe = "obesita";
}

// Arrotonda il BMI a due cifre decimali
$bmi = round($bmi, 2);

// Salvataggio dello storico in log.csv
$data = date("d/m/Y");
$ora = date("H:i:s");
$ip = $_SERVER["REMOTE_ADDR"];

$riga = array($data, $ora, $ip, $nome, $altezza * 100; $peso, $bmi, $classe);

$file = fopen("log.csv", "a");
fputcsv($file, $riga);
fclose($file);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risultato BMI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Calcolatore BMI</h1>

    <!-- Aggiunge alla card la classe della fascia BMI -->
    <div class="card <?php echo $classe; ?>">

        <div class="risultato">
            <p>
                <?php echo $nome; ?>
            </p>

            <p>Il tuo BMI è:</p>

            <div class="bmi-numero">
                <?php echo $bmi; ?>
            </div>

            <div class="fascia">
                <?php
                echo $fascia;
                ?>
            </div>
        </div>

        <a class="link-nuovo" href="form.html">
            Calcola di nuovo
        </a>

    </div>

</body>
</html>
