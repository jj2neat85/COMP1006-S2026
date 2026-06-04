CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    published_year INT NOT NULL
);

INSERT INTO books (title, author, genre, published_year) VALUES
('Dune', 'Frank Herbert', 'Sci-Fi', 1965),
('Neuromancer', 'William Gibson', 'Sci-Fi', 1984),
('The Hobbit', 'J.R.R. Tolkien', 'Fantasy', 1937),
('Snow Crash', 'Neal Stephenson', 'Sci-Fi', 1992);