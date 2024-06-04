<?php

namespace Front;

// Vérifiez que le script n'est pas exécuté en dehors de WordPress.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


class Shortcodes
{

    public static function init()
    {
        add_shortcode('lrseo', [Shortcodes::class, 'lrseo_fieldset']);
        add_shortcode('lrseo_fieldset', [Shortcodes::class, 'lrseo_fieldset']);
        add_shortcode('lrseo_list', [Shortcodes::class, 'lrseo_list']);
        add_shortcode('lrseo_icon', [Shortcodes::class, 'lrseo_icon']);
        add_shortcode('lrseo_faq', [Shortcodes::class, 'lrseo_faq']);
    }

    public static function lrseo_fieldset($atts, $content = null)
    {
        // Extraire les attributs avec des valeurs par défaut
        $a = shortcode_atts(array(
            'class' => '',
            'legend' => '' // Paramètre 'legend' ajouté
        ), $atts);

        // Construire le début du fieldset
        $fieldset = '<fieldset class="lrseo_fieldset ' . esc_attr($a['class']) . '">';

        // Ajouter une légende si elle est fournie
        if (!empty($a['legend'])) {
            $fieldset .= '<legend class="lrseo_legend">' . esc_html($a['legend']) . '</legend>';
        }

        // Ajouter le contenu et fermer le fieldset
        $fieldset .= do_shortcode($content) . '</fieldset>';

        return $fieldset;
    }

    public static function lrseo_list($atts, $content = null)
    {
        // Extraire les attributs avec des valeurs par défaut
        $a = shortcode_atts(array(
            'class' => '',
        ), $atts);

        // Construire le début de la liste
        $liste = '<ul class="lrseo_list ' . esc_attr($a['class']) . '">';

        // Ajouter le contenu et fermer la liste
        $liste .= do_shortcode($content) . '</ul>';

        return $liste;
    }

    public static function lrseo_icon($atts)
    {
        // Extraire les attributs avec des valeurs par défaut
        $a = shortcode_atts(array(
            'icon' => '',
        ), $atts);

        $iconName = esc_attr($a['icon']) === '' ? '' : ' lrseoicon-' . esc_attr($a['icon']);

        // Construire le début de la liste
        return '<i class="lrseo_icon'. $iconName . '"></i>';
    }

    public static function lrseo_faq($atts, $content = null)
    {
        // Extraire les attributs avec des valeurs par défaut
        $a = shortcode_atts(array(
            'json' => ''
        ), $atts);

        $jsonText = base64_decode($a['json']);
        $jsonText = str_replace('<script type="application/ld+json">', '', $jsonText);
        $jsonText = str_replace('</script>', '', $jsonText);
        $jsonText = str_replace("\n", '\n', $jsonText);
        $jsonText = str_replace("\t", '\t', $jsonText);

        $json = json_decode($jsonText, true);

        $questions = $json['mainEntity'];
        foreach ($questions as $key => $question)
        {
            $text = $json['mainEntity'][$key]['acceptedAnswer'][0]['text'];
            $text = json_encode(do_shortcode($text));
            $json['mainEntity'][$key]['acceptedAnswer'][0]['text'] = $text;
        }

        $jsonTextBack = json_encode($json);
        $jsonTextBack = '<script type="application/ld+json">'.$jsonTextBack.'</script>';

        $jsonBase64 = base64_encode($jsonTextBack);

        update_post_meta(get_the_ID(), 'lrseo_faq_json', $jsonBase64);

        return '';
    }
}