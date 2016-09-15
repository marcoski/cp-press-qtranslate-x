<?php
if(!defined('ABSPATH'))exit;

add_filter('cppress_layout_data', function($layout){
	$lang = qtranxf_getLanguage();
	
	$layout['widgets'] = cpq_translate($layout['widgets'], $lang);
	return $layout;
});

function cpq_translate($data, $lang){
	$widgets = array();
	foreach($data as $k => $d){
		if(is_array($d) && !array_key_exists($lang, $d)){
			$widgets[$k] = cpq_translate($d, $lang);
		}
		if(isset($d[$lang])){
			$widgets[$k] = $d[$lang];
		}
		if(!is_array($d)){
			$widgets[$k] = $d;
		}
	}
	
	return $widgets;
}