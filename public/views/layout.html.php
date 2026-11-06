<?php

declare(strict_types=1);
/**
 * Wspólny layout wszystkich widoków.
 *
 * @var string $content
 * @var string $pageTitle
 * @var string $metaDescription
 * @var string $activeNav
 */
$fullTitle = $pageTitle === 'Triathlon Training Hub'
    ? $pageTitle
    : $pageTitle . ' · Triathlon Training Hub';
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e($metaDescription) ?>">
    <title><?= e($fullTitle) ?></title>
    <script>
        (function () {
            var saved = null;

            try {
                saved = localStorage.getItem('theme');
            } catch (error) {
                saved = null;
            }

            var hasSaved = saved === 'light' || saved === 'dark';
            var systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            document.documentElement.dataset.theme = hasSaved
                ? saved
                : (systemDark ? 'dark' : 'light');
        })();
    </script>
    <link rel="stylesheet" href="/css/variables.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<a class="skip-link" href="#main">Przejdź do treści</a>

<?php require __DIR__ . '/_header.html.php'; ?>

<main id="main" class="site-main">
    <div class="container">
        <?= $content ?>
    </div>
</main>

<?php require __DIR__ . '/_footer.html.php'; ?>
<script src="/js/theme.js" defer></script>
</body>
</html>
