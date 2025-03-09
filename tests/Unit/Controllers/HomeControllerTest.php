<?php
namespace Tests\Unit\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\HomeController;

class HomeControllerTest extends TestCase
{
    private $homeController;
    
    protected function setUp(): void
    {
        // Définir la constante ROOT_DIR si elle n'existe pas
        if (!defined('ROOT_DIR')) {
            define('ROOT_DIR', dirname(__DIR__, 3));
        }
        
        // Créer le mock pour la classe Requests
        $this->createMockForRequests();
        
        // Créer une instance de HomeController avec la méthode render() mockée
        $this->homeController = $this->getMockBuilder(HomeController::class)
            ->onlyMethods(['render'])
            ->getMock();
    }
    
    private function createMockForRequests(): void
    {
        // Vérifier si la classe App\Config\Requests existe déjà
        if (!class_exists('App\\Config\\Requests')) {
            eval('
            namespace App\\Config;
            class Requests {
                public static function getConnection() {
                    return null;
                }
                
                public static function getRestaurants($limit) {
                    return [
                        ["idRestau" => 1, "nameR" => "Restaurant 1", "city" => "Orléans"],
                        ["idRestau" => 2, "nameR" => "Restaurant 2", "city" => "Orléans"]
                    ];
                }
                
                public static function getAllRestaurantTypes() {
                    return ["Français", "Italien", "Japonais"];
                }
            }
            ');
        }
    }
    
    public function testHomeMethodRendersCorrectTemplate(): void
    {
        // Configurer les attentes pour la méthode render
        $this->homeController->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo('home/homepage'),
                $this->callback(function ($data) {
                    // Vérifier que les données attendues sont présentes
                    return $data['pageTitle'] === 'Baratie' &&
                           is_array($data['restaurants']) &&
                           is_array($data['restaurantTypes']);
                })
            );
        
        // Exécuter la méthode à tester
        $this->homeController->home();
    }
    
    
    protected function tearDown(): void
    {
        // Nettoyage après les tests
        $this->homeController = null;
    }
}
