<?php

namespace Admin;

class Links
{
    /**
     * @param \WP_Post[] $posts
     * @param bool $out
     * @param bool $in
     * @return \WP_Post[]
     */
    public static function processLinks(array $posts = [], bool $out = true, bool $in = true, $stats = true): array
    {
        if ($out) {
            $posts = self::outLinks($posts);
        }
        if ($in) {
            $posts = self::inLinks($posts);
        }
        if ($stats) {
            $posts = self::getLinksStats($posts);
        }

        return $posts;
    }

    /**
     * @param \WP_Post[] $posts
     * @return \WP_Post[]
     */
    private static function getLinksStats(array $posts): array
    {
        // On calcule le nombre de mots de l'article
        // On divise par le nombre de liens sortants
        // On obtient le nombre de liens pour 1000 mots
        // On stocke dans un tableau

        foreach ($posts as $post) {
            $content = $post->post_content;
            $words = str_word_count(strip_tags($content));
            $links = count($post->outbound_links);
            $post->nb_links = $links;
            $post->words = $words;
            $post->pct_links = $links / ($words / 1000);
        }

        return $posts;
    }

    /**
     * @param \WP_Post[] $posts
     * @return \WP_Post[]
     */
    private static function outLinks(array $posts): array
    {
        foreach ($posts as $post) {
            $content = $post->post_content;
            $dom = new \DOMDocument();
            @$dom->loadHTML("<html><meta charset=\"UTF-8\"><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"><body>" . $content . "</body></html>");
            $links = $dom->getElementsByTagName('a');
            $post->outbound_links = [];
            /** @var \DOMNode $link */
            foreach ($links as $link) {
                $href = $link->getAttribute('href');
                $text = $link->textContent;
                if (strpos($href, get_site_url()) === 0) {
                    $post->outbound_links[] = [
                        "href" => $href,
                        "text" => $text
                    ];
                }
            }
        }

        return $posts;
    }

    /**
     * @param \WP_Post[] $posts
     * @return \WP_Post[]
     */
    private static function inLinks(array $posts): array
    {
        $postsCopy = $posts;
        foreach ($posts as $post) {
            foreach ($postsCopy as $p) {
                if ($p->ID === $post->ID) {
                    continue;
                }
                $content = $p->post_content;
                $dom = new \DOMDocument();
                @$dom->loadHTML($content);
                $links = $dom->getElementsByTagName('a');
                if (!isset($post->inbound_links)) $post->inbound_links = [];
                /** @var \DOMNode $link */
                foreach ($links as $link) {
                    $href = $link->getAttribute('href');
                    $text = $link->textContent;
                    if (strpos($href, get_permalink($post->ID)) === 0) {
                        $post->inbound_links[] = [
                            "href" => $href,
                            "text" => $text
                        ];
                    }
                }
            }
        }

        return $posts;
    }
}