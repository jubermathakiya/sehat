<?php

$files = glob(dirname(__DIR__).'/resources/views/tenant/**/{create,edit}.blade.php', GLOB_BRACE);
// Fallback for Windows glob
if (! $files) {
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(dirname(__DIR__).'/resources/views/tenant'));
    $files = [];
    foreach ($rii as $file) {
        if ($file->isFile() && in_array($file->getFilename(), ['create.blade.php', 'edit.blade.php'], true)) {
            $files[] = $file->getPathname();
        }
    }
}

foreach ($files as $path) {
    $content = file_get_contents($path);
    $original = $content;

    // Convert single-line compact create/edit templates into consistent structure
    if (preg_match("/<h1 class=\"page-title mb-4\">(.*?)<\\/h1>/s", $content, $m)) {
        $heading = trim(strip_tags($m[1]));
        // Keep dynamic blade in heading if present
        $headingHtml = $m[1];
        $content = preg_replace(
            "/<h1 class=\"page-title mb-4\">.*?<\\/h1>/s",
            "<div class=\"sehat-page-header\">\n    <div>\n        <h1 class=\"page-title\">{$headingHtml}</h1>\n    </div>\n</div>",
            $content,
            1
        );
    }

    // Ensure form cards have padded action footer
    $content = preg_replace(
        '/<button class="btn btn-sehat-primary(?: mt-3)?">(.*?)<\\/button><\\/form><\\/div>/s',
        '<div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top"><button type="submit" class="btn btn-sehat-primary">$1</button></div></form></div>',
        $content
    );

    // Expand cramped single-line card wrappers for readability (optional light touch)
    $content = str_replace(
        '<div class="sehat-card"><form',
        "<div class=\"sehat-card\">\n<form",
        $content
    );

    if ($content !== $original) {
        file_put_contents($path, $content);
        echo 'Polished '.basename(dirname($path)).'/'.basename($path).PHP_EOL;
    }
}

echo "Done\n";
