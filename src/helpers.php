<?php

declare(strict_types=1);

/**
 * Bezpieczne wypisanie wartości w HTML.
 */
function e(string|int|float|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Czytelna nazwa dyscypliny używana w widokach i kontrolerach.
 */
function sportLabel(string $sport): string
{
    return match ($sport) {
        'swim' => 'Pływanie',
        'bike' => 'Rower',
        'run' => 'Bieg',
        default => ucfirst($sport),
    };
}

/**
 * Format daty używany w widokach.
 */
function formatDate(string $date): string
{
    $timestamp = strtotime($date);

    return $timestamp === false ? $date : date('d.m.Y', $timestamp);
}
