<?php
include 'database.php';

// Funzione per ottenere tutti i libri dal database
function getBooks() {
    global $conn;
    $sql = "SELECT * FROM libri";
    // Prepara la query
    $stmt = $conn->prepare($sql);
    // Esegui la query
    $stmt->execute();
    // Ottieni il risultato della query
    $result = $stmt->get_result();
    $books = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
    return $books;
}

// Funzione per aggiungere un nuovo libro
function addBook($title, $author, $year, $genre) {
    global $conn;
    // Utilizziamo una query parametrica con un segnaposto '?' per ciascun valore
    $sql = "INSERT INTO libri (titolo, autore, anno_pubblicazione, genere) VALUES (?, ?, ?, ?)";
    // Prepara la query
    $stmt = $conn->prepare($sql);
    // Associa i parametri con i valori
    $stmt->bind_param("ssis", $title, $author, $year, $genre);
    // Esegui la query
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Funzione per aggiornare i dettagli del libro
function updateBook($id, $title, $author, $year, $genre) {
    global $conn;
    // Utilizziamo una query parametrica con un segnaposto '?' per ciascun valore
    $sql = "UPDATE libri SET titolo=?, autore=?, anno_pubblicazione=?, genere=? WHERE id=?";
    // Prepara la query
    $stmt = $conn->prepare($sql);
    // Associa i parametri con i valori
    $stmt->bind_param("ssisi", $title, $author, $year, $genre, $id);
    // Esegui la query
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Funzione per rimuovere un libro
function removeBook($id) {
    global $conn;
    // Utilizziamo una query parametrica con un segnaposto '?' per l'ID del libro
    $sql = "DELETE FROM libri WHERE id=?";
    // Prepara la query
    $stmt = $conn->prepare($sql);
    // Associa l'ID del libro con il parametro della query
    $stmt->bind_param("i", $id);
    // Esegui la query
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

?>
