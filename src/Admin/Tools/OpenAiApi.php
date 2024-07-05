<?php

namespace Admin\Tools;

use Orhanerday\OpenAi\OpenAi;

class OpenAiApi
{
    private static function init(): OpenAi
    {
        if (defined('OPENAI_KEY')) {
            $openAiKey = OPENAI_KEY;
        } else {
            throw new \Exception('Vous devez définir la constante OPENAI_KEY dans votre fichier wp-config.php pour utiliser cette page.');
        }

        return new OpenAi($openAiKey);
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
            $temperature = 1.0;
        }

        if (is_null($model)) {
            $model = 'gpt-4o';
        }

        $openAi = self::init();

        $prompt = self::replaceInPrompt($prompt, $replaces);
        
        $tries = 0;
        while ($tries <= 5) {
            $chatOptions = [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Tu es un expert renommé en SEO et tu vas m'aider a faire le linking de mon site.",
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => $temperature,
                'max_tokens' => 4000,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
            ];

            $chat = $openAi->chat($chatOptions);


            $d = json_decode($chat);

            if (!isset($d->choices[0]->message->content)) {
                var_dump('no choice gpt', sha1($prompt), $d);
                sleep(5);
                $tries++;
            } else {
                break;
            }
        }

        if ($tries >= 5) {
            throw new \Exception('Impossible de récupérer le GPT');
        }

        return $d->choices[0]->message->content;
    }

    public static function Transcription(string $file, string $langue = 'français'): string
    {
        $openAi = self::init();
        $lang = 'fr';
        if ($langue === 'anglais') {
            $lang = 'en';
        } elseif ($langue === 'espagnol') {
            $lang = 'es';
        }
        $opts = [
            'file' => curl_file_create($file),
            'model' => 'whisper-1',
            'language' => $lang,
            'response_format' => 'text',
        ];

        $text = $openAi->transcribe($opts);

        return $text;
    }
}