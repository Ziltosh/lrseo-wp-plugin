<?php

namespace Admin\Tools;

class Prompts
{
    private static string $promptChoixPostsInbound = "Tu vas choisir des articles pour faire des liens entrants. 
    Voici le titre de l'article vers lequel tu vas faire des liens entrants : ###titre###.
    Voici aussi une liste de titres d'autres articles, chaque ligne est un json qui contient l'ID de l'article et son titre. :
    ###liste###
    
    Analye les et calcule pour chacun un score de pertinence sur 100 qui indique la proximité avec le titre de l'article a lié. 
    Renvoie-moi ça sous la forme d'un JSON qui contient en premier l'id de l'article analysé, en second son titre et en dernier le score de pertinence sur 100.
    Exemple: [{ \"id\": 1, \"title\": \"Titre de l\'article\", \"score\": 75 }, { \"id\": 23, \"title\": \"Titre de l\'article 2\", \"score\": 50 }]
    Ne me renvoie que le JSON, pas de texte supplémentaire.
    Vérifie bien que tu as inclus tout les articles de la liste et que tu as bien respecté le format demandé.
    Entoure-le de balises <code></code> pour que je puisse le reconnaitre facilement.";

    private static string $promptInsertLink = "Je vais te donner un titre d'article, une url ainsi qu'un texte, voici ce que tu dois faire:
    - Analyser où se trouvent des liens déjà existants dans le texte
    - Trouver un endroit pertinent par rapport au titre: \" ###titre### \" dans le texte. Assez espacé par rapport aux autres liens pour insérer un lien avec une phrase qui incite le visiteur à cliquer. L'ancre du lien doit être exactement ###titre###.
    - Récupérer quelques mots avant
    - Créer la phrase clic avec le lien en target _blank
    
    Titre: ###titre###
    URL: ###url###
    Texte: 
    ###texte###
    
    ---
    Retourne-moi ça sous la forme d'un json qui contient: 
    - les mots avant la phrase ajoutée
    - la phrase ajoutée avec le lien en html
    Exemple : { \"before\": \"Phrase avant le lien\", \"sentence\": \"Phrase générée avec le lien HTML\" }
    Ne me renvoie que le JSON, pas de texte supplémentaire.
    Entoure-le de balises <code></code> pour que je puisse le reconnaitre facilement.
    Vérifie que le json est valide et que tu as bien respecté le format demandé.
    Vérifie qu'il y est bien le lien.
    ";

    /**
     * @throws \Exception
     */
    public static function ScorePostsInbound(string $titre, string $liste): string
    {
        return OpenAiApi::Message(self::$promptChoixPostsInbound, [
            '###titre###' => $titre,
            '###liste###' => $liste,
        ], null, null);
    }

    /**
     * @throws \Exception
     */
    public static function InsertLinkInText(string $titre, string $url, string $texte): string
    {
        return OpenAiApi::Message(self::$promptInsertLink, [
            '###titre###' => $titre,
            '###url###' => $url,
            '###texte###' => $texte,
        ], null, null);
    }
}