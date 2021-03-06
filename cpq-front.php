<?php
if(!defined('ABSPATH'))exit;

add_filter('cppress_layout_data', function($layout){
    $lang = qtranxf_getLanguage();
    $layout['widgets'] = cpq_translate($layout['widgets'], $lang);
    return $layout;
});

add_filter('cppress_translate_field', function($field){
    $lang = qtranxf_getLanguage();
    if(is_array($field) && isset($field[$lang])){
        return $field[$lang];
    }

    return $field;
});

function cpq_translate($data, $lang){
    $widgets = array();
    foreach($data as $k => $d){
        if(is_array($d) && !array_key_exists($lang, $d)){
            $widgets[$k] = cpq_translate($d, $lang);
        }

        if(is_array($d) && isset($d[$lang])){
            $widgets[$k] = $d[$lang];
        }else if(is_array($d) && !isset($d[$lang]) && in_array(key($d), qtranxf_getSortedLanguages(), true)){
            $widgets[$k] = '';
        }
        if(!is_array($d)){
            $widgets[$k] = $d;
        }
    }

    return $widgets;
}