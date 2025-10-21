<?php
// Wczytanie danych logowania z db_config.ini
$db = parse_ini_file('db_config.ini');

try {
    // Połączenie z bazą danych
    $pdo = new PDO(
        "mysql:host={$db['host']};dbname={$db['dbname']};charset=utf8",
        $db['user'],
        $db['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}

// Funkcja do inicjalizacji sesji w bazie
function start_session($pdo) {
    session_set_save_handler(
        new class($pdo) implements SessionHandlerInterface {
            private $pdo;
            public function __construct($pdo) { $this->pdo = $pdo; }

            public function open($savePath, $sessionName) { return true; }
            public function close() { return true; }

            public function read($id) {
                $stmt = $this->pdo->prepare("SELECT data FROM sessions WHERE id = :id AND expire > NOW()");
                $stmt->execute([':id'=>$id]);
                return $stmt->fetchColumn() ?: '';
            }

            public function write($id, $data) {
                $stmt = $this->pdo->prepare("REPLACE INTO sessions (id, data, expire) VALUES (:id, :data, DATE_ADD(NOW(), INTERVAL 1 HOUR))");
                return $stmt->execute([':id'=>$id, ':data'=>$data]);
            }

            public function destroy($id) {
                $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE id = :id");
                return $stmt->execute([':id'=>$id]);
            }

            public function gc($maxlifetime) {
                $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE expire < NOW()");
                return $stmt->execute();
            }
        }
    );
    session_start();
}

// Uruchamiamy sesję
start_session($pdo);
?>
