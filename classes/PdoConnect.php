<?php
class PdoConnect {
    private const CONFIG_FILE = __DIR__ . '/../config/config.json';

    protected static $_instance;
    protected $DSN;
    protected $OPD;
    public $PDO;

    private function __construct() {
        $config = $this->readConfig();
    
        $this->DSN = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    
        $this->OPD = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
    
        try {
            $this->PDO = new PDO($this->DSN, $config['user'], $config['password'], $this->OPD);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    private function __clone() {}
    public function __wakeup() {}

    private function readConfig(): array {
        // Read server configuration from JSON file
        $config = json_decode(file_get_contents(self::CONFIG_FILE), true);

        // Check if JSON decoding was successful
        if ($config === null) {
            throw new Exception('Error decoding config.json');
        }

        return $config;
    }
}
?>
