<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\SessionRepository;

final class DefaultController extends AppController
{
    private SessionRepository $sessions;

    public function __construct()
    {
        $this->sessions = new SessionRepository();
    }

    public function index(): void
    {
        $this->render('home', [
            'pageTitle' => 'Triathlon Training Hub',
            'activeNav' => 'home',
            'metaDescription' => 'Planuj pływanie, rower i bieg w jednym miejscu. Prototyp aplikacji do zarządzania treningiem triathlonowym.',
            'upcomingSessions' => $this->sessions->upcoming(4),
            'summary' => $this->sessions->summary(),
        ]);
    }

    public function about(): void
    {
        $this->render('about', [
            'pageTitle' => 'O projekcie',
            'activeNav' => 'about',
            'metaDescription' => 'Architektura MVC, routing i przykładowe trasy w prototypie Triathlon Training Hub.',
        ]);
    }
}
