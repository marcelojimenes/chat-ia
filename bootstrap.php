<?php

const BASE_PATH = __DIR__;
require_once BASE_PATH . '/vendor/autoload.php';

use App\Database\Sqlite;
use App\Entities\AIAssistant;
use App\Services\AIService;
use App\Services\Session;

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Sao_Paulo');

Session::init();

function handleChatRequest($message): array
{
    try {
        $pdo = Sqlite::getInstance();

        $assistant = new AIAssistant(
            name: "AI Assistant",
            model: "llama3.2",
            temperature: "0.7",
            maxTokens: 2048
        );

        $aiService = new AIService($assistant, $pdo);
        $response = $aiService->query($message);

        Session::addMessage('user', $message);
        Session::addMessage('assistant', $response['response']);

        return [
            'success' => true,
            'response' => $response['response']
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}
