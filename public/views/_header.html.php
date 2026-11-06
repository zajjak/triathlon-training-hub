<?php

declare(strict_types=1);
/**
 * @var string $activeNav
 */
$navigation = [
    ['key' => 'home', 'href' => '/', 'label' => 'Start'],
    ['key' => 'sessions', 'href' => '/sessions', 'label' => 'Sesje'],
    ['key' => 'about', 'href' => '/about', 'label' => 'O projekcie'],
];
?>
<header class="site-header">
    <div class="container site-header__inner">
        <a class="brand" href="/" aria-label="Triathlon Training Hub — strona główna">
            <span class="brand__mark" aria-hidden="true">T</span>
            <span class="brand__text">Tri<span>Hub</span></span>
        </a>

        <nav class="site-nav" aria-label="Główna nawigacja">
            <?php foreach ($navigation as $item): ?>
                <a
                    class="site-nav__link"
                    href="<?= e($item['href']) ?>"
                    <?= $activeNav === $item['key'] ? 'aria-current="page"' : '' ?>
                ><?= e($item['label']) ?></a>
            <?php endforeach; ?>
            <button
                class="theme-toggle"
                type="button"
                aria-label="Włącz ciemny motyw"
                aria-pressed="false"
                title="Zmień motyw"
            >
                <span class="theme-toggle__icon" aria-hidden="true">☾</span>
            </button>
        </nav>
    </div>
</header>
