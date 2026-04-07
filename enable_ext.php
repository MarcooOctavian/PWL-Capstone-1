<?php
$ini_path = 'D:\xampp\php\php.ini';
if (file_exists($ini_path)) {
    $content = file_get_contents($ini_path);
    $count1 = $count2 = 0;
    
    // Aktifkan ext-gd
    $content = str_replace(';extension=gd', 'extension=gd', $content, $count1);
    
    // Aktifkan ext-zip
    $content = str_replace(';extension=zip', 'extension=zip', $content, $count2);
    
    if (file_put_contents($ini_path, $content)) {
        echo "Successfully enabled extensions. (gd: $count1, zip: $count2)\n";
    } else {
        echo "Failed to write to $ini_path\n";
    }
} else {
    echo "php.ini not found at $ini_path\n";
}
