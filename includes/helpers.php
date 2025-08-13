<?php

function generate_file_preview($filename, $filepath) {
    // Use the filepath to reliably get the extension
    $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
    $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    $safe_filepath = htmlspecialchars($filepath);
    $safe_filename = htmlspecialchars($filename);

    if (in_array($ext, $image_extensions)) {
        return '<img src="' . $safe_filepath . '" class="card-img-top" alt="' . $safe_filename . '">';
    } 
    
    if ($ext === 'pdf') {
        return '<embed src="' . $safe_filepath . '#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" class="pdf-preview">';
    }

    // Fallback to icon for other file types
    $icon_path = get_file_icon($filepath); // Pass filepath to get_file_icon
    return '<div class="file-icon-preview d-flex align-items-center justify-content-center"><img src="' . $icon_path . '" alt="' . $safe_filename . ' icon" class="file-icon-large"></div>';
}

function get_file_icon($filepath) { // Changed parameter to filepath
    // Use the filepath to reliably get the extension
    $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
    $icon_name = 'file'; // Default icon

    // List of extensions that have a corresponding icon
    $icon_extensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'];

    if (in_array($ext, $icon_extensions)) {
        $icon_name = $ext;
    }

    // The config file where BASE_URL is defined is included before this helper.
    return BASE_URL . 'images/' . $icon_name . '.png';
}
