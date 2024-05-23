<?php
// Sample array
$numbers = [1, 2, 3, 4, 5];

// Callback function
function square(&$value, $key) {
    $value = $value * $value;
}

// Applying the callback function to each element of the array
array_walk($numbers, 'square');

// Output the modified array
//print_r($numbers);



trait Logger {
    public function log($message) {
        echo $message;
    }
}

trait FileLogger {
    public function logToFile($message, $filePath) {
        file_put_contents($filePath, $message . PHP_EOL, FILE_APPEND);
    }
}

class Application {
    use Logger, FileLogger;

    public function run() {
        $this->log("Application is running");
        $this->logToFile("Application started", "log.txt");
    }
}

$app = new Application();
//$app->run();

/**
 * @param array $array
 * @return array
 */
function bubbleSort(array $array): array {
    $n = count($array);
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $n - 1; $j++) {
            if ($array[$j] > $array[$j + 1]) {
                $temp = $array[$j];
                $array[$j] = $array[$j + 1];
                $array[$j + 1] = $temp;
            }
        }
    }
    return $array;
}

$array = [64, 34, 25, 12, 22, 11, 90];
//print_r(bubbleSort($array));


function quickSort(array $array): array {
    if (count($array) < 2) {
        return $array;
    }

    $left = $right = [];
    $pivot = $array[0];

    for ($i = 1; $i < count($array); $i++) {
        if ($array[$i] < $pivot) {
            $left[] = $array[$i];
        } else {
            $right[] = $array[$i];
        }
    }

    return array_merge(quickSort($left), [$pivot], quickSort($right));
}

$array = [64, 34, 25, 12, 22, 11, 90];
//print_r(quickSort($array));


function mergeSort(array $array): array {
    if (count($array) <= 1) {
        return $array;
    }

    $mid = intdiv(count($array), 2);
    $left = array_slice($array, 0, $mid);
    $right = array_slice($array, $mid);

    return merge(mergeSort($left), mergeSort($right));
}

function merge(array $left, array $right): array {
    $result = [];
    $i = $j = 0;

    while ($i < count($left) && $j < count($right)) {
        if ($left[$i] < $right[$j]) {
            $result[] = $left[$i];
            $i++;
        } else {
            $result[] = $right[$j];
            $j++;
        }
    }

    while ($i < count($left)) {
        $result[] = $left[$i];
        $i++;
    }

    while ($j < count($right)) {
        $result[] = $right[$j];
        $j++;
    }

    return $result;
}

$array = [64, 34, 25, 12, 22, 11, 90];
//print_r(mergeSort($array));


$users = [
    ['first_name' => 'John', 'last_name' => 'Doe', 'age' => 28],
    ['first_name' => 'Jane', 'last_name' => 'Smith', 'age' => 34],
    ['first_name' => 'Alice', 'last_name' => 'Johnson', 'age' => 24],
    ['first_name' => 'Mike', 'last_name' => 'Brown', 'age' => 40],
];

function filterAndSortUsers($users, $minAge) {
    // Filter users by age
    $filteredUsers = array_filter($users, function($user) use ($minAge) {
        return $user['age'] >= $minAge;
    });

    // Sort users by last name
    usort($filteredUsers, function($a, $b) {
        return strcmp($a['last_name'], $b['last_name']);
    });

    return $filteredUsers;
}

//print_r(filterAndSortUsers($users, 10));


$users = [
    1 => ['first_name' => 'John', 'last_name' => 'Doe', 'age' => 28],
    2 => ['first_name' => 'Jane', 'last_name' => 'Smith', 'age' => 34],
    3 => ['first_name' => 'Alice', 'last_name' => 'Johnson', 'age' => 24],
    4 => ['first_name' => 'Mike', 'last_name' => 'Brown', 'age' => 40],
];

// Simulate a GET request with a user ID
//$_GET['user_id'] = 2;
//
//if (isset($_GET['user_id'])) {
//    header('Content-Type: application/json');
//    $user_id = (int)$_GET['user_id'];
//    if (isset($users[$user_id])) {
//        echo json_encode($users[$user_id]);
//    } else {
//        http_response_code(404);
//        echo json_encode(['error' => 'User not found']);
//    }
//} else {
//    http_response_code(400);
//    echo json_encode(['error' => 'No user ID provided']);
//}

function findLongestWord($string) {
    $words = explode(' ', $string);
    $longestWord = '';

    foreach ($words as $word) {
        if (strlen($word) > strlen($longestWord)) {
            $longestWord = $word;
        }
    }

    return $longestWord;
}

//echo findLongestWord("The quick brown fox jumps over the lazy dog");
// Expected output: "jumps"


function getProducts() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "numberingplan";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //exit(var_dump($conn));

    $sql = "SELECT * FROM imei_reporting_bodies";
    $result = $conn->query($sql);

    $products = [];

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    $conn->close();

    return $products;
}

// Example usage (actual output will depend on the database contents):
// print_r(getProducts());


function capitalizeFirstLetter($strings) {
    return array_map(function($string) {
        return ucfirst($string);
    }, $strings);
}

$strings = ['apple', 'banana', 'cherry'];
//print_r(capitalizeFirstLetter($strings));

// Database connection using PDO
$dsn = 'mysql:host=localhost;dbname=testdb';
$username = 'dbuser';
$password = 'dbpass';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        // Prepare statement
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $user);
        $stmt->bindParam(':password', $pass);

        // Execute statement
        $stmt->execute();

        // Fetch result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo "Login successful!";
        } else {
            echo "Invalid username or password.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null;

<?php
// Database connection details
$servername = "localhost";
$username = "dbuser";
$password = "dbpass";
$dbname = "testdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $user, $pass);

    // Execute the statement
    $stmt->execute();

    // Store the result
    $stmt->store_result();

    // Check if a matching user was found
    if ($stmt->num_rows > 0) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }

    // Close statement
    $stmt->close();
}

// Close connection





/*
 *
 *
 * SELECT
    c.customer_id,
    c.first_name,
    c.last_name,
    c.email,
    SUM(oi.quantity * p.price) AS total_revenue
FROM
    customers c
JOIN
    orders o ON c.customer_id = o.customer_id
JOIN
    order_items oi ON o.order_id = oi.order_id
JOIN
    products p ON oi.product_id = p.product_id
WHERE
    MONTH(o.order_date) = 5 AND YEAR(o.order_date) = 2023
GROUP BY
    c.customer_id, c.first_name, c.last_name, c.email
ORDER BY
    total_revenue DESC;

*/
$conn->close();



