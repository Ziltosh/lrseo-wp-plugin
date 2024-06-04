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
    public static function processLinks(array $posts = [], bool $out = true, bool $in = true): array
    {
        if ($out) {
            $posts = self::outLinks($posts);
        }
        if ($in) {
            $posts = self::inLinks($posts);
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
            @$dom->loadHTML($content);
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