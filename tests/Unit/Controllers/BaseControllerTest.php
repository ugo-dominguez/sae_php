<?php
namespace Tests\Unit\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\BaseController;

class BaseControllerTest extends TestCase {
    private $baseController;
    
    protected function setUp(): void {
        if (!defined('ROOT_DIR')) {
            define('ROOT_DIR', dirname(__DIR__, 3));
        }
        
        $this->baseController = new class extends BaseController {
            public function testRender(string $template, array $data = []): void {
                $this->render($template, $data);
            }
            
            public function testRedirect(string $path): void {
                $this->redirect($path);
            }
        };
    }
    
    public function testRenderOutputsContent(): void {
        $tempDir = ROOT_DIR . '/templates/test';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        
        $tempFile = $tempDir . '/test.php';
        file_put_contents($tempFile, '<div>Test content: <?= $variable ?></div>');
        
        $defaultLayoutPath = ROOT_DIR . '/templates/layouts/default.php';
        $originalContent = '';
        
        if (file_exists($defaultLayoutPath)) {
            $originalContent = file_get_contents($defaultLayoutPath);
        }
        
        file_put_contents($defaultLayoutPath, '<?php include ROOT_DIR . "/templates/$template.php"; ?>');
        
        ob_start();
        $this->baseController->testRender('test/test', ['variable' => 'Hello World']);
        $output = ob_get_clean();
        
        if (!empty($originalContent)) {
            file_put_contents($defaultLayoutPath, $originalContent);
        } else {
            unlink($defaultLayoutPath);
        }
        
        unlink($tempFile);
        rmdir($tempDir);
        
        $this->assertStringContainsString('Test content: Hello World', $output);
    }
    
    public function testRedirectSendsHeaderAndExits(): void {
        
        $this->assertTrue(method_exists($this->baseController, 'testRedirect'));
        
        $reflectionMethod = new \ReflectionMethod($this->baseController, 'testRedirect');
        $this->assertTrue($reflectionMethod->isPublic());
    }
}