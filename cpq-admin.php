<?php
if(!defined('ABSPATH'))exit;

add_filter('qtranslate_load_admin_page_config', 'cpq_add_main_page_config');

function cpq_add_main_page_config($page_configs){
	$fields = &$page_configs['post']['forms']['post']['fields'];
	$fields['cppress-subtitle'] = array('jquery' => 'input.cppress-subtitle');
	
	if(!isset($page_configs['all-pages']['js-exec'])){
		$page_configs['all-pages']['js-exec'] = array();
	}
	$js = &$page_configs['all-pages']['js-exec'];
	$js[] = array(
		'handle' => 'cppress-qtranslate-x-exec',
		'src' => qtranxf_dir_from_wp_content(__FILE__).'/cppress-qtranslate-x-exec.js',
		'ver' => CPPRESS_VERSION
	);
	
	return $page_configs;
}

add_filter('cppress_widget_attrs', 'cpq_widget_attrs', 10, 3);

function cpq_widget_attrs($attrs, $instance, $field){
	$lang = cpq_get_lang($instance);
	if(is_array($attrs['value'])){
		$attrs['data-values'] = htmlspecialchars(json_encode($attrs['value'], JSON_HEX_TAG));
		if(isset($attrs['value'][$lang])){
			$value = $attrs['value'][$lang];
			$attrs['value'] = $value;
		}else{
			$attrs['value'] = "";
		}
	}
	
	return $attrs;
}

add_filter('cppress_the_editor', 'cpq_the_editor', 10, 4);

function cpq_the_editor($editor, $content, $settings, $instance){
	if(is_array($content)){
		$data_values = 'data-values="'.htmlspecialchars(json_encode($content, JSON_HEX_TAG)).'"';
		$editor = preg_replace("/(<textarea) (.*)(>%s<\/textarea>)/", "$1 $2 ".$data_values."$3", $editor);
	}
	return $editor;
}

add_filter('cppress_the_editor_content', 'cpq_the_editor_content', 9, 4);

function cpq_the_editor_content($content, $editor, $settings, $instance){
	return cpq_widget_content($content, $instance);
}

add_filter('cppress_widget_content', 'cpq_widget_content', 10, 2);

function cpq_widget_content($content, $instance){
	$lang = cpq_get_lang($instance);
	if(is_array($content)){
		if(isset($content[$lang])){
			return $content[$lang];
		}
	
		return "";
	}
	
	return $content;
}

function cpq_get_lang($instance){
	if(isset($instance['lang'])){
		return $instance['lang'];
	}
	
	return qtranxf_getLanguage();
}