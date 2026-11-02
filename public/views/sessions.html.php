<?php

declare(strict_types=1);
/**
 * @var string $heading
 * @var list<array{id: int, sport: string, title: string, date: string, duration: int, distance: float, zone: string, description: string}> $sessions
 * @var string $sport
 * @var string $query
 */
$sportOptions = [
    'swim' => 'Pływanie',
    'bike' => 'Rower',
    'run' => 'Bieg',
];
?>
<section class="page-head">
    <p class="eyebrow">Plan treningowy</p>
    <h1><?= e($heading) ?></h1>
    <p class="page-head__lead">
        Filtruj sesje po dyscyplinie lub szukaj po nazwie. Filtry działają jako
        parametry zapytania, więc można je zachować w zakładce.
    </p>
</section>

<form class="filter-bar" method="get" action="/sessions" role="search">
    <div class="field">
        <label for="sport">Dyscyplina</label>
        <select id="sport" name="sport">
            <option value="">Wszystkie</option>
            <?php foreach ($sportOptions as $value => $label): ?>
                <option value="<?= e($value) ?>"<?= $sport === $value ? ' selected' : '' ?>>
                    <?= e($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field field--grow">
        <label for="q">Szukaj</label>
        <input
            id="q"
            name="q"
            type="search"
            value="<?= e($query) ?>"
            placeholder="np. tempo, technika, brick"
            maxlength="50"
        >
    </div>

    <div class="filter-bar__actions">
        <button class="button button--primary" type="submit">Filtruj</button>
        <?php if ($sport !== '' || $query !== ''): ?>
            <a class="button button--ghost" href="/sessions">Wyczyść</a>
        <?php endif; ?>
    </div>
</form>

<p class="result-count" role="status">
    Znaleziono <strong><?= count($sessions) ?></strong>
    <?= count($sessions) === 1 ? 'sesję' : 'sesji' ?>.
</p>

<?php if ($sessions === []): ?>
    <div class="empty-state">
        <h2>Brak wyników</h2>
        <p>Zmień dyscyplinę lub wyczyść frazę wyszukiwania.</p>
        <a class="button button--primary" href="/sessions">Pokaż wszystkie sesje</a>
    </div>
<?php else: ?>
    <div class="session-grid">
        <?php foreach ($sessions as $session): ?>
            <?php require __DIR__ . '/_session-card.html.php'; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
