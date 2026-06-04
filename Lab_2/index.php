<?php
// index.php
require_once 'db_config.php';
require_once 'BookRepository.php';

$dbEngine = new BookDatabase();
$conn = $dbEngine->getConnection();
$repo = new BookRepository($conn);

// Hardcoded genre filter for this quick challenge
$selectedGenre = "Sci-Fi";
$books = $repo->getBooksByGenre($selectedGenre);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mini Book Lab</title>
</head>
<body>
    <h1>Genre Spotlight: <?php echo htmlspecialchars($selectedGenre); ?></h1>

    <?php if (empty($books)): ?>
        <p style="color: #ff6b6b;">No books found, or database logic error remaining.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Year</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($book->title); ?></strong></td>
                        <td><?php echo htmlspecialchars($book->author); ?></td>
                        <td><?php echo htmlspecialchars($book->published_year); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>