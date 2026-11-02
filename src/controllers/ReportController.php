<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\SessionRepository;

final class ReportController extends AppController
{
    private SessionRepository $sessions;

    public function __construct()
    {
        $this->sessions = new SessionRepository();
    }

    public function show(string $year): void
    {
        if (!ctype_digit($year)) {
            $this->badRequest('Rok musi być liczbą.');
            return;
        }

        $yearNumber = (int) $year;

        if ($yearNumber < 2000 || $yearNumber > 2100) {
            $this->badRequest('Rok musi mieścić się w zakresie 2000–2100.');
            return;
        }

        $sessions = $this->sessions->forYear($yearNumber);

        $this->render('report', [
            'pageTitle' => 'Raport roczny ' . $yearNumber,
            'activeNav' => 'sessions',
            'metaDescription' => 'Roczny raport treningów triathlonowych dla roku ' . $yearNumber . '.',
            'year' => $yearNumber,
            'sessions' => $sessions,
            'summary' => $this->sessions->summary($sessions),
        ]);
    }
}
