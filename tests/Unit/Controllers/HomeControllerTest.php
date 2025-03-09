<?php
namespace Tests\Unit\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\HomeController;

class HomeControllerTest extends TestCase {
    private $homeController;
    
    protected function setUp(): void {
        if (!defined('ROOT_DIR')) {
            define('ROOT_DIR', dirname(__DIR__, 3));
        }
        
        $this->createMockForRequests();
        
        $this->homeController = $this->getMockBuilder(HomeController::class)
            ->onlyMethods(['render'])
            ->getMock();
    }
    
    private function createMockForRequests(): void {
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
    
    public function testHomeMethodRendersCorrectTemplate(): void {
        $this->homeController->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo('home/homepage'),
                $this->callback(function ($data) {
                    return $data['pageTitle'] === 'Baratie' &&
                           is_array($data['restaurants']) &&
                           is_array($data['restaurantTypes']);
                })
            );
        
        $this->homeController->home();
    }
    
    protected function tearDown(): void {
        $this->homeController = null;
    }
}
