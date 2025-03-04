<?php
namespace App\Config;

class Provider {
    private string $filePath;

    public function __construct(string $filePath) {
        $this->filePath = $filePath;
    }

    public function getData(): array {
        if (!file_exists($this->filePath) || !is_readable($this->filePath)) {
            die('Erreur : Impossible d\'accéder au fichier JSON.');
        }

        $jsonData = file_get_contents($this->filePath);
        if ($jsonData === false) {
            die('Erreur : Échec de la lecture du fichier JSON.');
        }

        $data = json_decode($jsonData, true);
        if ($data === null) {
            die('Erreur : Échec du décodage JSON.');
        }

        return $data;
    }
}
