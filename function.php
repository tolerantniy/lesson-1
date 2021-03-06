<?php
session_start();
include_once('config/const.php');

/**
 * @param $file string
 * @return array
 */
function getContent ($file) {
    $content = file_get_contents($file);
    return json_decode($content, true);
}

function getTpl ($file) {
    return file_get_contents($file);
}

function getTemplate() {
    return file_get_contents('template.html');
}

function parseNavigation($content, $data) {
    $content = str_replace("[navigation]", $data, $content);
    return $content;
}

function parseAdditional($content, $data) {
    $content = str_replace("[additional_content]", $data.'[additional_content]', $content);
    return $content;
}

function parseMessages($content, $data) {
    $content = str_replace("[messages]", $data, $content);
    return $content;
}

function parseContent($content, $data) {
    $content = str_replace("[messages]", '', $content);
    $content = str_replace("[title_page]", 'Page: '.$data['title'], $content);
    $content = str_replace("[title]", $data['title'], $content);
    $content = str_replace("[description]", $data['description'], $content);
    $content = str_replace("[additional_content]", '', $content);
    return $content;
}

function showContent($content) {
    echo $content;
}

function parseForm($content, $data, $action = '') {
    if ($action) {
        $action = ' action="' . $action . '"';
    }
    $form = '<form method="post" class="form"'.$action.' enctype="multipart/form-data">';
    foreach ($data as $value) {
        switch ($value['type']) {
            case 'password':
            case 'text':
            case 'file':
                $form.= '<label>'.$value['title'].' <input name="'.$value['name'].'" type="'.$value['type'].'"></label>';
                break;
            case 'hidden':
                $form.= '<input name="'.$value['name'].'" type="'.$value['type'].'" value="'.$value['value'].'">';
                break;
            case 'textarea':
                $form.= '<label>'.$value['title'].' <textarea name="'.$value['name'].'"></textarea></label>';
                break;
            case 'button':
            case 'submit':
                $form.= '<input name="'.$value['name'].'" type="'.$value['type'].'">';
                break;
        }
    }
    $form.= '</form>';
    $content = str_replace("[additional_content]", $form.'[additional_content]', $content);
    return $content;
}
//function getGalleryData($file) {
//    $content = file_get_contents($file);
//}

function saveGallery($file, $gallery) {
    $fh = fopen($file, 'w+');
    $json_gallery = json_encode($gallery);
    fwrite($fh, $json_gallery);
    fclose($fh);
}

function setNewFileInGallery($data) {
    $file_gallery = './data/data_gallery.json';
    $gallery = getContent($file_gallery);
    if (!is_array($gallery)) {
        $gallery = array();
    }
    array_push($gallery, $data);
    saveGallery($file_gallery, $gallery);
}

/**
 * @param $data mixed
 */
function dump($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}


$phpFileUploadErrors = array(
    0 => 'There is no error, the file uploaded with success',
    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
    3 => 'The uploaded file was only partially uploaded',
    4 => 'No file was uploaded',
    6 => 'Missing a temporary folder',
    7 => 'Failed to write file to disk.',
    8 => 'A PHP extension stopped the file upload.',
);