<?php

declare(strict_types=1);
/**
 * @var array{id: int, sport: string, title: string, date: string, duration: int, distance: float, zone: string, description: string} $session
 */
?>
<article class="session-card">
    <div class="session-card__top">
        <span class="badge badge--<?= e($session['sport']) ?>"><?= e(sportLabel($session['sport'])) ?></span>
        <time datetime="<?= e($session['date']) ?>"><?= e(formatDate($session['date'])) ?></time>
    </div>

    <h3 class="session-card__title">
        <a href="/sessions/<?= (int) $session['id'] ?>"><?= e($session['title']) ?></a>
    </h3>

    <p class="session-card__description"><?= e($session['description']) ?></p>

    <dl class="session-meta">
        <div>
            <dt>Czas</dt>
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
    </dl>
</article>
