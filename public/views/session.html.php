<?php

declare(strict_types=1);
/**
 * @var array{id: int, sport: string, title: string, date: string, duration: int, distance: float, zone: string, description: string} $session
 */
?>
<nav class="breadcrumbs" aria-label="Ścieżka nawigacji">
    <a href="/sessions">Sesje</a>
    <span aria-hidden="true">/</span>
    <span><?= e($session['title']) ?></span>
</nav>

<article class="session-detail">
    <header class="session-detail__header">
        <span class="badge badge--<?= e($session['sport']) ?>"><?= e(sportLabel($session['sport'])) ?></span>
        <h1><?= e($session['title']) ?></h1>
        <p class="session-detail__date">
            <time datetime="<?= e($session['date']) ?>"><?= e(formatDate($session['date'])) ?></time>
        </p>
    </header>

    <p class="session-detail__description"><?= e($session['description']) ?></p>

    <dl class="detail-grid">
        <div>
            <dt>Czas trwania</dt>
            <dd><?= (int) $session['duration'] ?> min</dd>
        </div>
        <div>
            <dt>Dystans</dt>
            <dd><?= e(number_format($session['distance'], 1, ',', ' ')) ?> km</dd>
        </div>
        <div>
            <dt>Strefa</dt>
            <dd><?= e($session['zone']) ?></dd>
        </div>
        <div>
            <dt>Dyscyplina</dt>
            <dd><?= e(sportLabel($session['sport'])) ?></dd>
        </div>
    </dl>

    <aside class="callout">
        <h2>Jak powstał ten widok?</h2>
        <p>
            Adres <code>/sessions/<?= (int) $session['id'] ?></code> został dopasowany przez router
            do trasy <code>/sessions/{id}</code>. Kontroler sprawdził, czy identyfikator jest liczbą,
            a następnie pobrał dane z repozytorium i przekazał je do widoku.
        </p>
    </aside>

    <a class="button button--ghost" href="/sessions">← Wróć do listy sesji</a>
</article>
