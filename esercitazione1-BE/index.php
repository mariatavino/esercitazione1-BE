<?php
include 'functions.php';

// Inizializzazione delle variabili per i messaggi di errore
$error_message_add = "";
$error_message_edit = "";
$error_message_remove = "";

// Aggiungi un nuovo libro se il modulo è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'])) {
    // Validazione dei dati del modulo
    $title = htmlspecialchars($_POST['title']);
    $author = htmlspecialchars($_POST['author']);
    $year = intval($_POST['year']);
    $genre = htmlspecialchars($_POST['genre']);

    // Verifica se il titolo è vuoto
    if (empty($title)) {
        $error_message_add .= "Il campo titolo è obbligatorio.<br>";
    }

    // Verifica se l'autore è vuoto
    if (empty($author)) {
        $error_message_add .= "Il campo autore è obbligatorio.<br>";
    }

    // Verifica se il genere è vuoto
    if (empty($genre)) {
        $error_message_add .= "Il campo genere è obbligatorio.<br>";
    }

    // Verifica se l'anno di pubblicazione è un numero positivo
    if ($year <= 0) {
        $error_message_add .= "Inserire un anno di pubblicazione valido.<br>";
    }

    // Aggiungi il libro solo se non ci sono errori
    if (empty($error_message_add)) {
        if (addBook($title, $author, $year, $genre)) {
            echo "<script>alert('Libro aggiunto con successo!');</script>";
        } else {
            echo "<script>alert('Errore durante l\'aggiunta del libro.');</script>";
        }
    }
}

// Aggiorna il libro se il modulo di modifica è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit-title'])) {
    // Validazione dei dati del modulo
    $editBookId = intval($_POST['edit-book-id']);
    $editTitle = htmlspecialchars($_POST['edit-title']);
    $editAuthor = htmlspecialchars($_POST['edit-author']);
    $editYear = intval($_POST['edit-year']);
    $editGenre = htmlspecialchars($_POST['edit-genre']);

    // Verifica se l'ID del libro è valido
    if ($editBookId <= 0) {
        $error_message_edit .= "ID del libro non valido.<br>";
    }

    // Verifica se il titolo è vuoto
    if (empty($editTitle)) {
        $error_message_edit .= "Il campo titolo è obbligatorio.<br>";
    }

    // Verifica se l'autore è vuoto
    if (empty($editAuthor)) {
        $error_message_edit .= "Il campo autore è obbligatorio.<br>";
    }

    // Verifica se il genere è vuoto
    if (empty($editGenre)) {
        $error_message_edit .= "Il campo genere è obbligatorio.<br>";
    }

    // Verifica se l'anno di pubblicazione è un numero positivo
    if ($editYear <= 0) {
        $error_message_edit .= "Inserire un anno di pubblicazione valido.<br>";
    }

    // Aggiorna il libro solo se non ci sono errori
    if (empty($error_message_edit)) {
        if (updateBook($editBookId, $editTitle, $editAuthor, $editYear, $editGenre)) {
            echo "<script>alert('Dettagli del libro aggiornati con successo!');</script>";
        } else {
            echo "<script>alert('Errore durante l\'aggiornamento dei dettagli del libro.');</script>";
        }
    }
}

// Rimuovi il libro se il modulo di rimozione è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove-book-id'])) {
    // Validazione dei dati del modulo
    $removeBookId = intval($_POST['remove-book-id']);
    // Verifica se l'ID del libro è valido
    if ($removeBookId <= 0) {
        $error_message_remove .= "ID del libro non valido.<br>";
    }

    // Rimuovi il libro solo se l'ID è valido
    if (empty($error_message_remove)) {
        if (removeBook($removeBookId)) {
            echo "<script>alert('Libro rimosso con successo!');</script>";
        } else {
            echo "<script>alert('Errore durante la rimozione del libro.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Libreria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid bg-light">
        <h1 class="text-center">Gestione Libreria</h1>
        
        <!-- Mostra un elenco di tutti i libri disponibili -->
        <h2>Libri Disponibili</h2>
        <div id="book-list">
            <?php
            $books = getBooks();
            if (!empty($books)) {
                echo "<ul class='list-group'>";
                foreach ($books as $book) {
                    echo "<li class='list-group-item'>{$book['titolo']} - {$book['autore']} ({$book['anno_pubblicazione']}) - {$book['genere']}</li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='text-center'>Nessun libro disponibile.</p>";
            }
            ?>
        </div>

        <!-- Form per aggiungere un nuovo libro -->
        <div class="mt-5">
            <h2>Aggiungi un Nuovo Libro</h2>
            <form id="add-book-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="mb-3">
                    <label for="title" class="form-label">Titolo:</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="author" class="form-label">Autore:</label>
                    <input type="text" class="form-control" id="author" name="author" required>
                </div>
                <div class="mb-3">
                    <label for="year" class="form-label">Anno di Pubblicazione:</label>
                    <input type="number" class="form-control" id="year" name="year" required>
                </div>
                <div class="mb-3">
                    <label for="genre" class="form-label">Genere:</label>
                    <input type="text" class="form-control" id="genre" name="genre" required>
                </div>
                <button type="submit" class="btn btn-primary">Aggiungi Libro</button>
            </form>
        </div>

        <!-- Modifica dei libri -->
        <div class="mt-5">
    <h2>Modifica Libro</h2>
    <form id="edit-book-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="mb-3">
            <label for="edit-book-select" class="form-label">Seleziona un libro:</label>
            <select id="edit-book-select" name="edit-book-id" class="form-select">
                <?php foreach ($books as $book): ?>
                    <option value="<?php echo $book['id']; ?>"><?php echo $book['titolo']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="edit-title" class="form-label">Titolo:</label>
            <input type="text" id="edit-title" name="edit-title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="edit-author" class="form-label">Autore:</label>
            <input type="text" id="edit-author" name="edit-author" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="edit-year" class="form-label">Anno di Pubblicazione:</label>
            <input type="number" id="edit-year" name="edit-year" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="edit-genre" class="form-label">Genere:</label>
            <input type="text" id="edit-genre" name="edit-genre" class="form-control" required>
        </div>
        <span class="error"><?php echo $error_message_edit; ?></span><br>
        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
    </form>
</div>

<!-- Rimozione dei libri -->
<div class="mt-5">
    <h2>Rimozione Libro</h2>
    <form id="remove-book-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="mb-3">
            <label for="remove-book-select" class="form-label">Seleziona un libro:</label>
            <select id="remove-book-select" name="remove-book-id" class="form-select">
                <?php foreach ($books as $book): ?>
                    <option value="<?php echo $book['id']; ?>"><?php echo $book['titolo']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <span class="error"><?php echo $error_message_remove; ?></span><br>
        <button type="submit" class="btn btn-danger">Rimuovi Libro</button>
    </form>
</div>
    </div>
    <!-- Includi lo script JavaScript di Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

