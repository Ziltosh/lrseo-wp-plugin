<?php

namespace Admin\Tools;

use Claude\Claude3Api\Client;
use Claude\Claude3Api\Config;

class ClaudeApi
{
    private static string $anthropicVersion = "2023-06-01";

    private static function init(): Config
    {
        if (defined('CLAUDE_KEY')) {
            $claudeKey = CLAUDE_KEY;
        } else {
            throw new \Exception('Vous devez définir la constante CLAUDE_KEY dans votre fichier wp-config.php pour utiliser cette page.');
        }

        return new Config($claudeKey);
    }

    private static function replaceInPrompt(string $prompt, array $replaces): string
    {
        foreach ($replaces as $key => $value) {
            $prompt = str_replace($key, $value, $prompt);
        }

        return $prompt;
    }

    /**
     * @throws \Exception
     */
    public static function Message(string $prompt, array $replaces, $temperature = null, $model = null): string
    {
        if (is_null($temperature)) {
            $temperature = 0;
        }

        if (is_null($model)) {
            $model = 'claude-3-5-sonnet-20240620';
        }

        $claude = new Client(self::init());

        $prompt = self::replaceInPrompt($prompt, $replaces);

        $tries = 0;
        while ($tries <= 5) {
            $chatOptions = [
                'model' => $model,
                'system' => "Tu es un expert renommé en SEO et tu vas m'aider a faire le linking de mon site.",
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'max_tokens' => 4000,
            ];

            $chat = $claude->chat($chatOptions);

            if (!isset($chat->getContent()[0]["text"])) {
                var_dump('no choice gpt', sha1($prompt), $chat);
                sleep(5);
                $tries++;
            } else {
                break;
            }
        }

        if ($tries >= 5) {
            throw new \Exception('Impossible de récupérer le GPT');
        }

        return $chat->getContent()[0]["text"];
    }

}