<?php

function create_page(string $file_path, string $sub_title = '', array $heads = [], array $keywords = [], bool $use_footer = true, string $icon_path = '') : string
{

    global $root, $root_url;
    $base = file_get_contents($root . 'templates/base.html');
    $html = file_get_contents($file_path);
    $html = str_replace('{{body}}', $html, $base);
    $title = (include($root . 'config/config.php'))['TITLE'];
    $head_html = '';

    if($sub_title != ''){
        $title = $sub_title . ' | ' . $title;
    }

    $html = str_replace('{{title}}', $title, $html);

    foreach($heads as $head){
        $head_html = $head_html . $head;
    }

    $html = str_replace('{{head}}', $head_html, $html);

    if($use_footer){
        $footer_html = file_get_contents($root . 'templates/footer.html');
    }else{
        $footer_html = '';
    }

    if($icon_path != ''){
        $header_icon_html = file_get_contents($root . 'templates/header_icon.html');
        $header_icon_html = str_replace('{{icon_path}}', $icon_path, $header_icon_html);
    }else{
        $header_icon_html = '';
    }

    $html = str_replace('{{header_icon}}', $header_icon_html, $html);
    $html = str_replace('{{footer_html}}', $footer_html, $html);

    foreach($keywords as $key => $val){
        $html = str_replace('{{' . $key . '}}', $val, $html);
    }

    return $html;

}
