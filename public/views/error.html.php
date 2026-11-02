<?php

declare(strict_types=1);
/**
 * @var int $statusCode
 * @var string $title
 * @var string $message
 * @var list<string> $allowedMethods
 */
$allowedMethods ??= [];
?>
<section class="error-page">
    <p class="error-page__code"><?= (int) $statusCode ?></p>
    <h1><?= e($title) ?></h1>
    <p class="error-page__message"><?= e($message) ?></p>

    <?php if ($allowedMethods !== []): ?>
        <p class="error-page__meta">
            Dozwolone metody: <code><?= e(implode(', ', $allowedMethods)) ?></code>
        </p>
    <?php endif; ?>

    <div class="error-page__actions">
        <a class="button button--primary" href="/">Wróć na stronę główną</a>
        <a class="button button--ghost" href="/sessions">Przejdź do sesji</a>
    </div>
</section>
