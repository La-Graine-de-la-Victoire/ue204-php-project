<?php

function showImage(string $path, string $description, $class = '') {
    if (!file_exists(dirname(__DIR__).$path) || empty($path)) {
        $path = '/assets/images/nf.png';
    }

    echo '<img src="'.$path.'" alt="'.$description.'" class="'.$class.'">';
}