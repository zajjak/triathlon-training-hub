<?php

declare(strict_types=1);
/**
 * @var int $year
 * @var list<array{id: int, sport: string, title: string, date: string, duration: int, distance: float, zone: string, description: string}> $sessions
 * @var array{total: int, minutes: int, distance: float, bySport: array<string, int>} $summary
 */
?>
<nav class="breadcrumbs" aria-label="Ścieżka nawigacji">
    <a href="/sessions">Sesje</a>
    <span aria-hidden="true">/</span>
    <span>Raport <?= (int) $year ?></span>
</nav>

<section class="page-head">
    <p class="eyebrow">Raport roczny</p>
    <h1>Rok <?= (int) $year ?></h1>
    <p class="page-head__lead">
        Ten adres jest obsługiwany przez trasę regex
        <code>/reports/{year:\d{4}}</code>. Rok spoza zakresu 2000–2100 zwraca kontrolowane 400.
    </p>
</section>

<dl class="detail-grid detail-grid--summary">
    <div>
        <dt>Sesje</dt>
        <dd><?= (int) $summary['total'] ?></dd>
    </div>
    <div>
        <dt>Łączny czas</dt>
        <dd><?= (int) $summary['minutes'] ?> min</dd>
    </div>
    <div>
        <dt>Łączny dystans</dt>
        <dd><?= e(number_format($summary['distance'], 1, ',', ' ')) ?> km</dd>
    </div>
    <div>
        <dt>Pływanie / rower / bieg</dt>
        <dd>
            <?= (int) $summary['bySport']['swim'] ?> /
            <?= (int) $summary['bySport']['bike'] ?> /
            <?= (int) $summary['bySport']['run'] ?>
        </dd>
    </div>
</dl>

<?php if ($sessions === []): ?>
    <div class="empty-state">
        <h2>Brak sesji w tym roku</h2>
        <p>W danych demonstracyjnych znajdują się sesje z listopada 2026 roku.</p>
        <a class="button button--primary" href="/reports/2026">Pokaż raport 2026</a>
    </div>
<?php else: ?>
    <div class="session-grid">
        <?php foreach ($sessions as $session): ?>
            <?php require __DIR__ . '/_session-card.html.php'; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
