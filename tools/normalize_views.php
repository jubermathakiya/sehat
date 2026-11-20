<?php

$root = dirname(__DIR__).'/resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));

foreach ($iterator as $file) {
    if (! $file->isFile() || ! str_ends_with($file->getFilename(), '.blade.php')) {
        continue;
    }

    $path = $file->getPathname();
    $content = file_get_contents($path);
    $original = $content;

    $content = str_replace(
        ['â€”', 'â€¦', 'â€™', 'â€œ', 'â€', 'Signing inâ€¦', 'Updatingâ€¦', 'Verifyingâ€¦'],
        ['—', '…', "'", '"', '"', 'Signing in…', 'Updating…', 'Verifying…'],
        $content
    );

    // Forgot-password / login primary links: teal → orange
    $content = str_replace(
        'class="small text-sehat-teal text-decoration-none"',
        'class="small text-sehat-orange text-decoration-none"',
        $content
    );

    // OTP resend was orange (accent); after swap green/teal is accent
    $content = str_replace(
        'class="btn btn-link text-sehat-orange text-decoration-none disabled"',
        'class="btn btn-link text-sehat-teal text-decoration-none disabled"',
        $content
    );

    // Cache-bust theme CSS references
    $content = preg_replace(
        "/asset\\('assets\\/css\\/sehat-theme\\.css'\\)/",
        "asset('assets/css/sehat-theme.css').'?v=2'",
        $content
    );

    // Add page_title when only title is defined on app pages
    if ((str_contains($content, "layouts.superadmin") || str_contains($content, "layouts.tenant"))
        && preg_match("/@section\\('title'\\s*,\\s*(.+?)\\)/", $content, $m)
        && ! str_contains($content, "@section('page_title'")) {
        $titleExpr = trim($m[1]);
        $content = preg_replace(
            "/(@section\\('title'\\s*,\\s*".preg_quote($titleExpr, '/')."\\))/",
            "$1\n@section('page_title', {$titleExpr})",
            $content,
            1
        );
    }

    // Also handle @section('title','X') without spaces
    if ((str_contains($content, "layouts.superadmin") || str_contains($content, "layouts.tenant"))
        && preg_match("/@section\\('title',(.+?)\\)/", $content, $m)
        && ! str_contains($content, "@section('page_title'")) {
        $titleExpr = trim($m[1]);
        $content = preg_replace(
            "/(@section\\('title',".preg_quote($titleExpr, '/')."\\))/",
            "$1\n@section('page_title',{$titleExpr})",
            $content,
            1
        );
    }

    if ($content !== $original) {
        file_put_contents($path, $content);
        echo 'Updated '.str_replace(dirname(__DIR__).DIRECTORY_SEPARATOR, '', $path).PHP_EOL;
    }
}

echo "Done\n";
