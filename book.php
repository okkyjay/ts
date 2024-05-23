<?php

// Function to connect to MySQL database
function connectToDatabase() {
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "books_database";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to create books table if not exists
function createBooksTable($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        genre VARCHAR(255),
        publication_year INT
    )";

    if ($conn->query($sql) === FALSE) {
        echo "Error creating table: " . $conn->error;
    }
}

// Function to import data from CSV file
function importDataFromCSV($conn) {
    $file = "books.csv";
    $handle = fopen($file, "r");

    if ($handle !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $title = $data[0];
            $author = $data[1];
            $genre = $data[2];
            $publicationYear = $data[3];

            // Validate data (e.g., check for empty values)

            // Insert data into database
            $sql = "INSERT INTO books (title, author, genre, publication_year)
                    VALUES ('$title', '$author', '$genre', $publicationYear)";

            if ($conn->query($sql) === FALSE) {
                echo "Error inserting data: " . $conn->error;
            }
        }

        fclose($handle);
    } else {
        echo "Error opening file: $file";
    }
}

// Function to authenticate user
function authenticateUser() {
    $username = $_SERVER['PHP_AUTH_USER'] ?? '';
    $password = $_SERVER['PHP_AUTH_PW'] ?? '';

    // Validate username and password (e.g., check against database)
    if ($username !== 'admin' || $password !== 'admin123') {
        header('WWW-Authenticate: Basic realm="Restricted Area"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Unauthorized';
        exit;
    }
}

// Function to handle GET request for /books endpoint
function getBooks($conn) {
    $sql = "SELECT * FROM books";
    $result = $conn->query($sql);

    $books = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }

    return $books;
}

// Function to handle POST request for /books endpoint
function addBook($conn, $data) {
    $title = $data['title'];
    $author = $data['author'];
    $genre = $data['genre'] ?? '';
    $publicationYear = $data['publication_year'] ?? '';

    // Insert data into database
    $sql = "INSERT INTO books (title, author, genre, publication_year)
            VALUES ('$title', '$author', '$genre', $publicationYear)";

    if ($conn->query($sql) === FALSE) {
        return ['error' => $conn->error];
    }

    return ['success' => true];
}

// Function to handle PUT request for /books/{id} endpoint
function updateBook($conn, $id, $data) {
    $title = $data['title'];
    $author = $data['author'];
    $genre = $data['genre'] ?? '';
    $publicationYear = $data['publication_year'] ?? '';

    // Update data in database
    $sql = "UPDATE books SET title='$title', author='$author', genre='$genre', publication_year=$publicationYear WHERE id=$id";

    if ($conn->query($sql) === FALSE) {
        return ['error' => $conn->error];
    }

    return ['success' => true];
}

// Function to handle DELETE request for /books/{id} endpoint
function deleteBook($conn, $id) {
    // Delete data from database
    $sql = "DELETE FROM books WHERE id=$id";

    if ($conn->query($sql) === FALSE) {
        return ['error' => $conn->error];
    }

    return ['success' => true];
}

// Main script

// Connect to database and create table
$conn = connectToDatabase();
createBooksTable($conn);
importDataFromCSV($conn);

// Authenticate user for API endpoints
authenticateUser();

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['endpoint'])) {
    if ($_GET['endpoint'] === 'books') {
        $books = getBooks($conn);
        respondWithFormat($books);
    } else {
        header('HTTP/1.0 404 Not Found');
        echo 'Endpoint not found';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['endpoint'])) {
    if ($_GET['endpoint'] === 'books') {
        $postData = json_decode(file_get_contents('php://input'), true);
        $response = addBook($conn, $postData);
        respondWithFormat($response);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['endpoint']) && isset($_GET['id'])) {
    if ($_GET['endpoint'] === 'books') {
        $id = $_GET['id'];
        $putData = json_decode(file_get_contents('php://input'), true);
        $response = updateBook($conn, $id, $putData);
        respondWithFormat($response);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['endpoint']) && isset($_GET['id'])) {
    if ($_GET['endpoint'] === 'books') {
        $id = $_GET['id'];
        $response = deleteBook($conn, $id);
        respondWithFormat($response);
    }
}

$conn->close();

// Function to respond with appropriate format (JSON or XML)
function respondWithFormat($data) {
    if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/xml') !== false) {
        header('Content-Type: application/xml');
        echo arrayToXml($data);
    } else {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}

function arrayToXml($array, $xml = null) {
    if ($xml === null) {
        $xml = new SimpleXMLElement('<root/>');
    }

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            if (!is_numeric($key)) {
                arrayToXml($value, $xml->addChild($key));
            } else {
                arrayToXml($value, $xml->addChild('item'));
            }
        } else {
            $xml->addChild($key, htmlspecialchars($value));
        }
    }

    return $xml->asXML();
}

