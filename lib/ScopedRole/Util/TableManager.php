<?php

namespace ScopedRole;

class Util_TableManager {
    /**
     * @var \PDO
     */
    protected $_pdo;

    /**
     * @var string
     */
    protected $_prefix;

    protected $_sqlPath;

    public $errors = array();

    public function __construct(\PDO $pdo, $sqlPath = null, $prefix = 'scrl_')
    {
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->_pdo = $pdo;
        if (! $sqlPath) {
            $sqlPath = realpath(__DIR__ . '/../../../sql');
        }
        $this->_sqlPath = $sqlPath;
        $this->_prefix = $prefix;
    }

    protected function _getSqlFile($filename)
    {
        $file = $this->_sqlPath . '/' . $filename;
        if (! is_file($file)) {
            throw new Exception("File '$file' does not exist");
        }
        return file_get_contents($file);
    }

    public function createTables()
    {
        if ($this->configTableExists()) {
            $this->errors[] = "Tables already exist";
            return false;
        }
        $queries = $this->_getSqlFile('tables-create.sql');
        $queries = explode(';', $queries);
        foreach ($queries as $sql) {
            if (trim($sql) !== '') {
                $sql = \str_replace('`scrl_', "`{$this->_prefix}", $sql);
                try {
                    @$this->_pdo->exec($sql);
                } catch (\PDOException $e) {
                    $this->errors[] = $e->getMessage();
                }
            }
        }
        $stmt = $this->_pdo->prepare("INSERT INTO {$this->_prefix}config (title, val) VALUES (?, ?)");
        $stmt->bindValue(1, 'version');
        $stmt->bindValue(2, Version::VERSION);
        $stmt->execute();
        return true;
    }

    public function dropTables()
    {
        $queries = $this->_getSqlFile('tables-drop.sql');
        $queries = explode(';', $queries);
        foreach ($queries as $sql) {
            if (trim($sql) !== '') {
                $sql = \str_replace('`scrl_', "`{$this->_prefix}", $sql);
                try {
                    @$this->_pdo->exec($sql);
                } catch (\PDOException $e) {
                    $this->errors[] = $e->getMessage();
                }
            }
        }
        return true;
    }

    public function configTableExists()
    {
        $this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        try {
            @$this->_pdo->exec("SELECT * FROM {$this->_prefix}config");
            return true;
        } catch (\PDOException $e) {}
        return false;
    }
}