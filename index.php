<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Läs Böcker - Bokhandel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>Bokbutiken</h1>
        <div class="subtitle">SELECT your book</div>
    </header>

    <nav>
        <ul>
            <li><a href="index.php" class="active">Lista Böcker</a></li>
            <li><a href="add_book.php">Ny Bok</a></li>
            <li><a href="authors.php">Lista Författare</a></li>
            <li><a href="add_author.php">Ny Författare</a></li>
        </ul>
    </nav>

    <h1>📚 Böcker</h1>
    
    <?php
    // Steg 1: Förbered databasanslutningen
    // DSN (Data Source Name) beskriver var databasen finns och vilken databas vi vill använda
    $dsn = "mysql:host=127.0.0.1;dbname=bookstore;charset=utf8mb4";
    $user = 'root';
    $pass = ''; // Tomt lösenord (standard i XAMPP)

    try {
        // Steg 2: Skapa anslutning till databasen
        // new PDO skapar ett objekt som låter oss prata med databasen
        // PDO står för "PHP Data Objects" - ett sätt att arbeta med databaser i PHP
        $pdo = new PDO($dsn, $user, $pass);
        
        // Steg 3: Skriv SQL-queryn
        // Vi vill hämta boktitlar och författarnamn från databasen
        // JOIN används för att kombinera data från två tabeller (books och authors)
        $sql = "
            SELECT 
                b.title, 
                a.name_first, 
                a.name_last
            FROM 
                books b 
            JOIN 
                authors a 
            ON 
                b.author_id = a.author_id
            ORDER BY
                b.title ASC
        ";
        
        // Steg 4: Kör SQL-queryn mot databasen
        // query() skickar vår SQL-query till databasen och får tillbaka resultatet
        $stmt = $pdo->query($sql);

        // Steg 5: Visa resultatet på webbsidan
        echo '<ul class="book-list">';
        
        // Loopa igenom varje rad i resultatet
        // fetch() hämtar en rad i taget från databasresultatet
        // FETCH_ASSOC betyder att vi får data som en array med kolumnnamn som nycklar
        $book_count = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $book_count++;
            // Sätt ihop förnamn och efternamn till ett fullständigt namn
            $full_author_name = htmlspecialchars($row['name_first']) . ' ' . htmlspecialchars($row['name_last']);
            
            // htmlspecialchars() säkerställer att specialtecken visas korrekt och skyddar mot XSS-attacker
            echo '<li class="book-item">';
            echo '<span class="title">' . htmlspecialchars($row['title']) . '</span>';
            echo '<span class="author">Författare: ' . $full_author_name . '</span>';
            echo '</li>';
        }
        
        echo '</ul>';

        // Om inga böcker hittades, visa ett meddelande
        if ($book_count == 0) {
             echo '<p class="empty-message">Inga böcker hittades i databasen.</p>';
        }


    } catch (\PDOException $e) {
        // Om något gick fel (t.ex. databasen är inte igång eller SQL-queryn är fel)
        // så fångar catch-blocket felet och visar ett felmeddelande
        echo '<div class="error-message">';
        echo '<h2>Databasfel:</h2>';
        echo '<p>Kunde inte hämta böcker. Kontrollera XAMPP eller SQL-queryn.</p>';
        echo 'Detaljer: <em>' . $e->getMessage() . '</em>';
        echo '</div>';
    }
    ?>

</body>
</html>