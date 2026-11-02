<?php

declare(strict_types=1);
/**
 * @var list<array{id: int, sport: string, title: string, date: string, duration: int, distance: float, zone: string, description: string}> $upcomingSessions
 * @var array{total: int, minutes: int, distance: float, bySport: array<string, int>} $summary
 */
$sports = [
    ['code' => 'swim', 'name' => 'Pływanie', 'description' => 'Technika, interwały i spokojne kilometry w wodzie.'],
    ['code' => 'bike', 'name' => 'Rower', 'description' => 'Sweet spot, długie wyjazdy i praca nad kadencją.'],
    ['code' => 'run', 'name' => 'Bieg', 'description' => 'Tempo progowe, wybiegania i treningi brick.'],
];
?>
<section class="hero">
    <div class="hero__content">
        <p class="eyebrow">Trenuj mądrzej. Startuj mocniej.</p>
        <h1>Twój triathlon.<br><span>Jeden plan.</span></h1>
        <p class="hero__lead">
            Pływanie, rower i bieg w jednym miejscu. Prototyp aplikacji do planowania
            sesji triathlonowych i analizy tygodniowego obciążenia.
        </p>
        <div class="hero__actions">
            <a class="button button--primary" href="/sessions">Zobacz sesje</a>
            <a class="button button--ghost" href="/about">Poznaj projekt</a>
        </div>
    </div>

    <aside class="hero__panel" aria-label="Podsumowanie planu">
        <p class="hero__panel-label">Cały plan</p>
        <p class="hero__panel-value"><?= (int) $summary['total'] ?> <span>sesji</span></p>

        <dl class="hero__stats">
            <div>
                <dt>Czas</dt>
                <dd><?= (int) $summary['minutes'] ?> min</dd>
            </div>
            <div>
                <dt>Dystans</dt>
                <dd><?= e(number_format($summary['distance'], 1, ',', ' ')) ?> km</dd>
            </div>
            <div>
                <dt>Dyscypliny</dt>
                <dd>3</dd>
            </div>
        </dl>

        <ul class="sport-summary" aria-label="Liczba sesji w dyscyplinach">
            <?php foreach ($sports as $sport): ?>
                <li>
                    <span class="sport-summary__dot sport-summary__dot--<?= e($sport['code']) ?>" aria-hidden="true"></span>
                    <?= e($sport['name']) ?>
                    <strong><?= (int) $summary['bySport'][$sport['code']] ?></strong>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>
</section>

<section class="section" aria-labelledby="sports-heading">
    <div class="section__header">
        <div>
            <p class="eyebrow">Trzy dyscypliny</p>
            <h2 id="sports-heading">Jeden organizm, trzy akcenty</h2>
        </div>
        <p class="section__lead">Wybierz dyscyplinę i zobacz przykładowe sesje z planu.</p>
    </div>

    <div class="sport-grid">
        <?php foreach ($sports as $sport): ?>
            <a class="sport-card sport-card--<?= e($sport['code']) ?>" href="/sports/<?= e($sport['code']) ?>">
                <span class="sport-card__count"><?= (int) $summary['bySport'][$sport['code']] ?> sesji</span>
                <h3><?= e($sport['name']) ?></h3>
                <p><?= e($sport['description']) ?></p>
                <span class="sport-card__link">Przeglądaj <span aria-hidden="true">→</span></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="section" aria-labelledby="upcoming-heading">
    <div class="section__header">
        <div>
            <p class="eyebrow">Najbliższe dni</p>
            <h2 id="upcoming-heading">Nadchodzące sesje</h2>
        </div>
        <a class="text-link" href="/sessions">Wszystkie sesje <span aria-hidden="true">→</span></a>
    </div>

    <div class="session-grid">
        <?php foreach ($upcomingSessions as $session): ?>
            <?php require __DIR__ . '/_session-card.html.php'; ?>
        <?php endforeach; ?>
    </div>
</section>
