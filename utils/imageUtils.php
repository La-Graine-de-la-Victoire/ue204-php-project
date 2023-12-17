<?php

/**
 * Check if image path exists : show the image in HTML format
 * Else show the default image : not found (nf.png)
 *
 * @param string $path
 * @param string $description
 * @param $class
 * @return void
 */
function showImage(string $path, string $description, $class = '') {
    if (!file_exists(dirname(__DIR__).$path) || empty($path)) {
        $path = '/assets/images/nf.png';
    }

    echo '<img src="'.$path.'" alt="'.$description.'" class="'.$class.'">';
}