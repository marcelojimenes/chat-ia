<?php

namespace App\Services;

use App\Entities\AIAssistant;
use Exception;
use PDO;

class AIService
{
    private PDO $pdo;
    public const AI_URL = "ollama:11434/api/generate";

    public function __construct(private readonly AIAssistant $assistant, PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function query(string $message): array
    {
        try {
            $response = $this->sendRequest($message);
            $this->saveInteraction($message, $response);

            return [
                'success' => true,
                'response' => $response
            ];
        } catch (Exception $e) {
            throw new Exception("error: " . $e->getMessage());
        }
    }

    private function sendRequest(string $message): string
    {
        $data = [
            "model" => $this->assistant->getModel(),
            "prompt" => $message,
            "temperature" => $this->assistant->getTemperature(),
            "max_tokens" => $this->assistant->getMaxTokens(),
        ];

        $options = [
            \CURLOPT_URL => self::AI_URL,
            \CURLOPT_RETURNTRANSFER => true,
            \CURLOPT_POST => true,
            \CURLOPT_POSTFIELDS => json_encode($data),
            \CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("error: $error");
        }

        $lines = explode("\n", $response);
        $fullResponse = '';
        foreach ($lines as $line) {
            if (empty($line)) continue;

            $data = json_decode($line, true);
            if ($data === null) {
                continue;
            }

            if (isset($data['response'])) {
                $fullResponse .= $data['response'];
            }

            if (isset($data['done']) && $data['done'] === true) {
                break;
            }
        }

        if (empty($fullResponse)) {
            throw new Exception("invalid response: $response");
        }

        return $fullResponse;
    }

    private function saveInteraction(string $message, string $response): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO interactions (user_message, assistant_response) 
             VALUES (:message, :response)"
        );

        $stmt->execute([
            ':message' => $message,
            ':response' => $response
        ]);
    }
}