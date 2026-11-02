<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\SessionRepository;

final class SessionController extends AppController
{
    private const SPORTS = ['swim', 'bike', 'run'];

    private SessionRepository $sessions;

    public function __construct()
    {
        $this->sessions = new SessionRepository();
    }

    public function index(): void
    {
        $sport = $_GET['sport'] ?? '';
        $query = $_GET['q'] ?? '';

        if (!is_string($sport) || !is_string($query)) {
            $this->badRequest('Parametry zapytania muszą być pojedynczymi wartościami tekstowymi.');
            return;
        }

        $sport = trim($sport);
        $query = trim($query);

        if ($sport !== '' && !in_array($sport, self::SPORTS, true)) {
            $this->badRequest('Nieznana dyscyplina. Dozwolone wartości: swim, bike, run.');
            return;
        }

        if (strlen($query) > 50) {
            $this->badRequest('Fraza wyszukiwania może mieć maksymalnie 50 znaków.');
            return;
        }

        $sessions = $this->sessions->filter(
            $sport !== '' ? $sport : null,
            $query !== '' ? $query : null,
        );

        $this->render('sessions', [
            'pageTitle' => 'Sesje treningowe',
            'activeNav' => 'sessions',
            'metaDescription' => 'Lista sesji triathlonowych z filtrowaniem po dyscyplinie i frazie.',
            'heading' => 'Wszystkie sesje',
            'sessions' => $sessions,
            'sport' => $sport,
            'query' => $query,
        ]);
    }

    public function show(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->badRequest('Identyfikator sesji musi być liczbą całkowitą.');
            return;
        }

        $session = $this->sessions->find((int) $id);

        if ($session === null) {
            $this->notFound('Nie znaleziono sesji o podanym identyfikatorze.');
            return;
        }

        $this->render('session', [
            'pageTitle' => $session['title'],
            'activeNav' => 'sessions',
            'metaDescription' => $session['description'],
            'session' => $session,
        ]);
    }

    public function bySport(string $sport): void
    {
        if (!in_array($sport, self::SPORTS, true)) {
            $this->badRequest('Nieznana dyscyplina. Dozwolone wartości: swim, bike, run.');
            return;
        }

        $sessions = $this->sessions->filter($sport);

        $this->render('sessions', [
            'pageTitle' => 'Sesje: ' . sportLabel($sport),
            'activeNav' => 'sessions',
            'metaDescription' => 'Sesje triathlonowe dla dyscypliny: ' . sportLabel($sport) . '.',
            'heading' => 'Sesje: ' . sportLabel($sport),
            'sessions' => $sessions,
            'sport' => $sport,
            'query' => '',
        ]);
    }
}
