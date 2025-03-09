<?php
namespace Tests\Unit\Config;

use PHPUnit\Framework\TestCase;
use App\Config\Database;

class DatabaseTest extends TestCase
{
    private static $testDbPath;
    
    protected function setUp(): void {
        self::$testDbPath = sys_get_temp_dir() . '/test_baratie_' . uniqid() . '.db';
        
        if (!defined('ROOT_DIR')) {
            define('ROOT_DIR', dirname(__DIR__, 3));
        }
        
        Database::$dbPath = self::$testDbPath;
        Database::$connection = null;
    }
    
    protected function tearDown(): void {
        Database::$connection = null;
        
        if (file_exists(self::$testDbPath)) {
            unlink(self::$testDbPath);
        }
    }
    
    public function testGetConnection(): void {
        $connection = Database::getConnection();
        $this->assertNotNull($connection);
        $this->assertInstanceOf(\PDO::class, $connection);
        
        $connection2 = Database::getConnection();
        $this->assertSame($connection, $connection2);
    }
    
    public function testCreateTables(): void {
        $connection = Database::getConnection();
        
        $tables = ['User', 'FoodType', 'Restaurant', 'Photo', 'Serves', 'Prefers', 'Illustrates', 'Reviewed', 'Likes'];
        
        foreach ($tables as $table) {
            $stmt = $connection->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
            $this->assertNotFalse($stmt);
            $this->assertEquals($table, $stmt->fetchColumn());
        }
    }
    
    public function testInsertUser(): void {
        $connection = Database::getConnection();
        
        $username = 'testuser';
        $password = password_hash('testpassword', PASSWORD_DEFAULT);
        
        Database::insertUser($username, $password);
        
        $stmt = $connection->prepare("SELECT * FROM User WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        $this->assertNotFalse($user);
        $this->assertEquals($username, $user['username']);
        $this->assertTrue(password_verify('testpassword', $user['password']));
    }
    
    public function testDeleteTables(): void {
        $connection = Database::getConnection();
        
        Database::deleteTables();
        
        $tables = ['User', 'FoodType', 'Restaurant', 'Photo', 'Serves', 'Prefers', 'Illustrates', 'Reviewed', 'Likes'];
        
        foreach ($tables as $table) {
            $stmt = $connection->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
            $this->assertNotFalse($stmt);
            $this->assertFalse($stmt->fetchColumn());
        }
    }
}