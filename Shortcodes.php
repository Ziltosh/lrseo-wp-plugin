<?php

class Shortcodes
{

    public static function lrseo_shortcode($atts, $content = null)
    {
        // Extraire les attributs avec des valeurs par défaut
        $a = shortcode_atts(array(
            'class' => '',
            'legend' => '' // Paramètre 'legend' ajouté
        ), $atts);

        // Construire le début du fieldset
        $fieldset = '<fieldset class="' . esc_attr($a['class']) . '">';

        // Ajouter une légende si elle est fournie
        if (!empty($a['legend'])) {
            $fieldset .= '<legend>' . esc_html($a['legend']) . '</legend>';
        }

        // Ajouter le contenu et fermer le fieldset
        $fieldset .= do_shortcode($content) . '</fieldset>';

        return $fieldset;
    }
}