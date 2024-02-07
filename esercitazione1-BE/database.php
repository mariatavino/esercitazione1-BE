<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestione_libreria";

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controllo della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
?>
