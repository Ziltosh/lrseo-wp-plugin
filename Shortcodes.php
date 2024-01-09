<?php

// Vérifiez que le script n'est pas exécuté en dehors de WordPress.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Shortcodes
{

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

    public static function lrseo_icon($atts, $content = null)
    {
        // Extraire les attributs avec des valeurs par défaut
        $a = shortcode_atts(array(
            'class' => '',
            'icon' => '',
        ), $atts);

        // Construire le début de la liste
        $icon = '<i class="lrseo_icon ' . esc_attr($a['class']) . ' ' . esc_attr($a['icon']) . '"></i>';

        return $icon;
    }
}