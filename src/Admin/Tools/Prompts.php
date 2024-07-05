<?php

namespace Admin\Tools;

class Prompts
{
    private static string $promptChoixPostsInbound = "Tu vas choisir des articles pour faire des liens entrants. 
    Voici le titre de l'article vers lequel tu vas faire des liens entrants : ###titre###.
    Voici un mot clé qui va servir d'ancre pour le lien entrant : ###kw###.
    Voici aussi une liste d'autres articles, chaque ligne est un json qui contient : l'ID de l'article, son titre, le pourcentage de liens pour 1000 mots (pct_links). :
    ###liste###
    
    Analye les et calcule pour chacun d'entre eux un score de pertinence sur 100 qui indique la proximité avec le titre de l'article a lié.
    Voici quelques exemples de scores:
    - Titre: Se libérer de ses peurs : comment réussir durablement ? - Comparaison: Exercices pour vaincre la peur grâce à la dépolarisation - Score: 95 (ça parle des peurs)
    - Titre: Se libérer de ses peurs : comment réussir durablement ? - Comparaison: Booster sa performance sportive : la meilleure méthode - Score: 0 (pas de rapport)
    - Titre: Se libérer de ses peurs : comment réussir durablement ? - Comparaison: Comment maîtriser son mental : conseils pratiques - Score: 60 (maitriser son mental et se libérer de ses peurs peuvent être liés)
    - Titre: Se libérer de ses peurs : comment réussir durablement ? - Comparaison: Se mettre la pression : comment arrêter définitivement ? - Score: 70 (la pression et les peurs peuvent être liés)
    - Titre: Se libérer de ses peurs : comment réussir durablement ? - Comparaison: Combattre sa peur de ne pas réussir : les clés - Score: 90 (ça parle de peurs et de réussir)
    - Titre: Se libérer de ses peurs : comment réussir durablement ? - Comparaison: Stress de sportif de haut niveau : les stratégies de gestion - Score: 65 (le stress et les peurs peuvent être liés)
    
    
    Pondère ce score par rapport au pourcentage de liens déjà présents dans l'article (pct_links), plus le pourcentage est élevé, moins le score doit être élevé, si le pct_links dépasse 6, le score est divisé au moins de moitié. 
    Renvoie-moi ça sous la forme d'un JSON qui contient les infos du JSON que tu as reçu en entrée avec en plus le score de pertinence sur 100 que tu as calculé.
    Exemple: [{ \"id\": 1, \"title\": \"Titre de l'article\", \"pct_links\": 0.2, \"score\": 75 }, { \"id\": 23, \"title\": \"Titre de l'article 2\", \"pct_links\": 0.5, \"score\": 50}]
    Ne me renvoie que le JSON, pas de texte supplémentaire, pas d'explications.
    Vérifie bien que tu as inclus tous les articles de la liste et que tu as bien respecté le format demandé.
    Entoure-le avec la balise <code> pour que je puisse le reconnaitre facilement.
    
    Prends le temps de réfléchir avant de répondre et utilise toutes tes capcités, le temps de réponse m'importe peu.";

    private static string $promptInsertLink = "Voici :
    - un titre d'article: \"###titre###\"
    - une ancre: \"###kw###\"
    - une url: \"###url###\"
    - un texte: 
    ###texte###
    
    ------------------------------------
    FIN DU TEXTE, DEBUT DES INSTRUCTIONS
    
    Voici ce que tu dois faire :
    1) Analyser où se trouvent des liens déjà existants dans le contenu
    2) Trouver l'endroit le plus pertinent du texte pour parler de l'article ###titre### . Ca ne doit pas être au milieu d'une phrase ou avant une liste. 
    3) Créer une phrase en français, qui s'intègre correctement au reste du contenu et du flux d'informations. Fait bien attention a la façon de s'exprimer (tutoiement ou vouvoiement, première ou troisième personne, etc).
    Elle doit contenir le lien en HTML vers l'article ###titre### avec comme ancre de lien (entre le <a></a>) \"###kw###\" , comme url: ###url### et en target=_blank.
    Cette phrase doit être indépendante, commencer par une majuscule et se terminer par un point. Elle doit être cohérente et compréhensible.
    Elle doit amener le lien de manière naturelle et pertinente. 
    4) Récupérer les quelques mots avant l'endroit où tu vas ajouter la phrase
   
    Retourne-moi ça sous la forme d'un json qui contient : 
    - les mots avant la phrase ajoutée que tu as récupéré à l'étape 4
    - la phrase ajoutée avec le lien en html de l'étape 3
    
    Exempled de JSON : { \"before\": \"les mots avant la phrase\", \"sentence\": \"Phrase générée avec le lien HTML\" }
    Ne me renvoie que le JSON, pas de texte supplémentaire.
    Entoure-le de balises <code></code> pour que je puisse le reconnaitre facilement.
    Vérifie que le json est valide et que tu as bien respecté le format demandé.
    Vérifie bien le lien, l'ancre (entre le <a></a>) doit être exactement \"###kw###\" et l'url doit être exactement : ###url###, le lien doit être en target=_blank.
    Vérifie bien que la phrase est cohérente et pertinente.
    
    Revérifie toutes les instructions, assure-toi de ne pas avoir oublié d'étape et d'avoir tout bien respecté.
    
    Prends tout ton temps pour bien réfléchir avant de répondre et utilise toutes tes capcités, le temps de réponse m'importe peu.
    ";



    /**
     * @throws \Exception
     */
    public static function ScorePostsInbound(string $titre, string $liste, string $kw, string $llm = "chatgpt"): string
    {
        if ($llm === 'chatgpt') {
            return OpenAiApi::Message(self::$promptChoixPostsInbound, [
                '###titre###' => $titre,
                '###liste###' => $liste,
                '###kw###' => $kw,
            ], null, null);
        } else {
            return ClaudeApi::Message(self::$promptChoixPostsInbound, [
                '###titre###' => $titre,
                '###liste###' => $liste,
                '###kw###' => $kw,
            ], null, null);
        }
    }

    /**
     * @throws \Exception
     */
    public static function InsertLinkInText(string $kw, string $titre, string $url, string $texte, string $llm = "claude"): string
    {
        if ($llm === 'chatgpt') {
            return OpenAiApi::Message(self::$promptInsertLink, [
                '###kw###' => $kw,
                '###titre###' => $titre,
                '###url###' => $url,
                '###texte###' => $texte,
            ], null, null);
        } else {
            return ClaudeApi::Message(self::$promptInsertLink, [
                '###kw###' => $kw,
                '###titre###' => $titre,
                '###url###' => $url,
                '###texte###' => $texte,
            ], null, null);
        }
    }
}