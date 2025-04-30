<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Database connection details from environment
$host = env('DB_HOST', '127.0.0.1');
$port = env('DB_PORT', '3306');
$database = env('DB_DATABASE', 'forge');
$username = env('DB_USERNAME', 'forge');
$password = env('DB_PASSWORD', '');

echo "Connecting to database...\n";

try {
    // Create PDO connection
    $dsn = "mysql:host={$host};port={$port};dbname={$database}";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully.\n";

    // Read the SQL file
    $sqlFile = __DIR__ . '/sql/rebuild_ctg_documents.sql';
    $sql = file_get_contents($sqlFile);

    if (!$sql) {
        throw new Exception("Unable to read SQL file: {$sqlFile}");
    }

    echo "Executing SQL script...\n";

    // Execute the SQL statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    $pdo->beginTransaction();

    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
            echo ".";
        }
    }

    $pdo->commit();

    echo "\nSQL script executed successfully.\n";

    // Count documents created
    $stmt = $pdo->query("SELECT COUNT(*) FROM ctg_documents");
    $count = $stmt->fetchColumn();

    echo "Total CTG documents created: {$count}\n";

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

/**
 * Get an environment variable value
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function env($key, $default = null) {
    $value = getenv($key);

    if ($value === false) {
        return $default;
    }

    return $value;
}
