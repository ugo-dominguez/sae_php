<?php
namespace Tests\Unit\Config;

use PHPUnit\Framework\TestCase;
use App\Config\Database;

class DatabaseTest extends TestCase
{
    private static $testDbPath;
    
    protected function setUp(): void
    {
        // Créer un chemin de base de données temporaire pour les tests
        self::$testDbPath = sys_get_temp_dir() . '/test_baratie_' . uniqid() . '.db';
        
        // Définir la constante ROOT_DIR si elle n'existe pas
        if (!defined('ROOT_DIR')) {
            define('ROOT_DIR', dirname(__DIR__, 3));
        }
        
        // Remplacer le chemin de la base de données par notre chemin de test
        Database::$dbPath = self::$testDbPath;
        Database::$connection = null;
    }
    
    protected function tearDown(): void
    {
        // Fermer la connexion à la base de données
        Database::$connection = null;
        
        // Supprimer le fichier de base de données de test
        if (file_exists(self::$testDbPath)) {
            unlink(self::$testDbPath);
        }
    }
    
    public function testGetConnection(): void
    {
        // Attention: cette méthode devra être modifiée car votre initDatabase utilise un fichier JSON
        // Pour les tests, nous allons mocker cette partie ou utiliser un jeu de données de test
        
        // Tester que la connexion est bien initialisée
        $connection = Database::getConnection();
        $this->assertNotNull($connection);
        $this->assertInstanceOf(\PDO::class, $connection);
        
        // Vérifier que la deuxième fois, on récupère bien la même connexion
        $connection2 = Database::getConnection();
        $this->assertSame($connection, $connection2);
    }
    
    public function testCreateTables(): void
    {
        // Obtenir une connexion à la base de données
        $connection = Database::getConnection();
        
        // Vérifier que les tables ont été créées
        $tables = ['User', 'FoodType', 'Restaurant', 'Photo', 'Serves', 'Prefers', 'Illustrates', 'Reviewed', 'Likes'];
        
        foreach ($tables as $table) {
            $stmt = $connection->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
            $this->assertNotFalse($stmt);
            $this->assertEquals($table, $stmt->fetchColumn());
        }
    }
    
    public function testInsertUser(): void
    {
        // Obtenir une connexion à la base de données
        $connection = Database::getConnection();
        
        // Insérer un utilisateur de test
        $username = 'testuser';
        $password = password_hash('testpassword', PASSWORD_DEFAULT);
        
        Database::insertUser($username, $password);
        
        // Vérifier que l'utilisateur a bien été inséré
        $stmt = $connection->prepare("SELECT * FROM User WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        $this->assertNotFalse($user);
        $this->assertEquals($username, $user['username']);
        $this->assertTrue(password_verify('testpassword', $user['password']));
    }
    
    public function testDeleteTables(): void
    {
        // Obtenir une connexion à la base de données
        $connection = Database::getConnection();
        
        // Supprimer les tables
        Database::deleteTables();
        
        // Vérifier que les tables ont été supprimées
        $tables = ['User', 'FoodType', 'Restaurant', 'Photo', 'Serves', 'Prefers', 'Illustrates', 'Reviewed', 'Likes'];
        
        foreach ($tables as $table) {
            $stmt = $connection->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
            $this->assertNotFalse($stmt);
            $this->assertFalse($stmt->fetchColumn());
        }
    }
}