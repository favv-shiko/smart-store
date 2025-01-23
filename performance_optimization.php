<?php

// 1. Gzip Compression
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
    ob_start('ob_gzhandler');
} else {
    ob_start();
}

// 2. Minify CSS and JavaScript
function minifyFile($filePath) {
    $content = file_get_contents($filePath);
    $minifiedContent = preg_replace(['/\s+/'], [' '], $content); // Remove extra spaces
    return $minifiedContent;
}

// Example: Minify a CSS file
$cssPath = 'style.css';
$minifiedCSS = minifyFile($cssPath);
file_put_contents('style.min.css', $minifiedCSS);

// 3. Combine Files
function combineFiles($files, $outputFile) {
    $combinedContent = '';
    foreach ($files as $file) {
        $combinedContent .= file_get_contents($file) . "\n";
    }
    file_put_contents($outputFile, $combinedContent);
}

// Example: Combine CSS files
$cssFiles = ['reset.css', 'style.css'];
combineFiles($cssFiles, 'combined.css');

// 4. Browser Caching
header('Cache-Control: max-age=31536000, public');

// 5. Optimize Images
function optimizeImage($source, $destination, $quality = 75) {
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
        imagejpeg($image, $destination, $quality);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
        imagepng($image, $destination, floor($quality / 10));
    }

    imagedestroy($image);
}

// Example: Optimize a JPEG image
optimizeImage('input.jpg', 'output.jpg', 80);

// 6. Enable Lazy Loading for Images
function addLazyLoading($html) {
    return preg_replace('/<img(.*?)src="(.*?)"/i', '<img$1loading="lazy" src="$2"', $html);
}

// Example usage
$htmlContent = '<img src="image.jpg" alt="Example">';
$htmlWithLazyLoading = addLazyLoading($htmlContent);

// 7. Use a Content Delivery Network (CDN)
define('CDN_URL', 'https://cdn.example.com/');
function rewriteToCDN($filePath) {
    return CDN_URL . ltrim($filePath, '/');
}

// Example: Rewrite a file path to use CDN
$cdnFilePath = rewriteToCDN('images/example.jpg');

// 8. Database Query Optimization
function optimizeQuery($conn, $query) {
    $optimizedQuery = preg_replace('/\s+/', ' ', $query); // Remove extra spaces
    $result = $conn->query($optimizedQuery);
    return $result;
}

// 9. Asynchronous Loading for Scripts
function addAsyncToScripts($html) {
    return preg_replace('/<script(.*?)src="(.*?)"/i', '<script$1src="$2" async', $html);
}

// Example usage
$htmlScript = '<script src="script.js"></script>';
$htmlWithAsyncScript = addAsyncToScripts($htmlScript);

?>