
<?php

// Controllo di come è stata aperta la pagina PHP
if ($_SERVER["REQUEST_METHOD"] == "GET"){
    header("Location: EsBMI.html");     // rimanda l'utente alla pagina EsBMI.html
    exit;   // interrompe l'esecuzione del PHP
}


// Recupero Dati form
$nome = $_POST["nome"];
$peso = $_POST["peso"];
$altezza = $_POST["altezza"];


// Validazione dei dati
if ($nome != "") {
    $nomeValido = true;
} else {
    $nomeValido = false;
}

if (is_numeric($peso) && $peso > 0) {
    $pesoValido = true;
} else {
    $pesoValido = false;
}

if (is_numeric($altezza) && $altezza > 0) {
    $altezzaValida = true;
} else {
    $altezzaValida = false;
}


// Registrazione dei dati
$nomeLog = "NOT_VALID";
$pesoLog = "NOT_VALID";
$altezzaLog = "NOT_VALID";

$bmiLog = "NOT_VALID";
$classeLog = "NOT_VALID";

if ($nomeValido == true) {
    $nomeLog = $nome;   // copia il nome nella variabile destinata al registro
                        // Se il nome non è valido, $nomeLog rimane "NOT_VALID"
}

if ($pesoValido == true) {
    $pesoLog = $peso;
}

if ($altezzaValida == true) {
    $altezzaLog = $altezza;
}


// Calcola il BMI (soltanto se i dati sono validi)
if ($nomeValido == true && $pesoValido == true && $altezzaValida == true) {

    // Converte l'altezza da centimetri a metri
    $altezza = $altezza / 100;

    // Calcola il BMI
    $bmi = $peso / ($altezza * $altezza);

    // Controlla in quale fascia rientra il BMI
    if ($bmi < 18.5) {
        $fascia = "SOTTOPESO";
        $classe = "sottopeso";      // nome tecnico utilizzato nel CSS per applicare lo stile corrispondente
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

    // Arrotonda il BMI a due cifre decimali (ES: 21.224 diventa 21.22)
    $bmi = round($bmi, 2);

    // Copia il BMI calcolato e la classe individuata nelle variabili destinate al registro CSV
    $bmiLog = $bmi;
    $classeLog = $classe;
}


// Salvataggio dello storico (delle operazioni effettuate) in log.csv
// Recupero della data e dell'ora
$data = date("d/m/Y");      // ES: 27/10/2026
$ora = date("H:i:s");       // ES: 18:35:20

// Recupero dell'indirizzo IP (del dispositivo o della connessione che ha effettuato la richiesta al server)
$ip = $_SERVER["REMOTE_ADDR"];

// Creazione Array (che serve a raggruppare i dati che verranno scritti nel file)
$riga = array($data, $ora, $ip, $nomeLog, $altezzaLog, $pesoLog, $bmiLog, $classeLog);

// Apertura File
$file = fopen("log.csv", "a");

// Scrittura dei valori dell'array nel file CSV (ES RIGA: 27/10/2026,18:35:20,127.0.0.1,Luca,175,65,21.22,normopeso)
fputcsv($file, $riga);

fclose($file);

// Controlla se i dati sono validi
if ($nomeValido == false || $pesoValido == false || $altezzaValida == false) {
    exit("Errore: inserisci dei dati validi!");     // interrompe e mostra un msg di errore
}
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
