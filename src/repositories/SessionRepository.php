<?php

declare(strict_types=1);

namespace App\Repository;

final class SessionRepository
{
    /**
     * @var list<array{
     *     id: int,
     *     sport: string,
     *     title: string,
     *     date: string,
     *     duration: int,
     *     distance: float,
     *     zone: string,
     *     description: string
     * }>
     */
    private array $sessions = [
        [
            'id' => 1,
            'sport' => 'swim',
            'title' => 'Technika kraula — interwały',
            'date' => '2026-11-03',
            'duration' => 45,
            'distance' => 1.8,
            'zone' => 'Z2',
            'description' => 'Rozgrzewka, 8 × 100 m w tempie progowym, spokojne wyciszenie.',
        ],
        [
            'id' => 2,
            'sport' => 'bike',
            'title' => 'Rower — sweet spot',
            'date' => '2026-11-04',
            'duration' => 90,
            'distance' => 45.0,
            'zone' => 'Z3',
            'description' => '3 × 15 minut w strefie sweet spot z 5-minutowymi przerwami.',
        ],
        [
            'id' => 3,
            'sport' => 'run',
            'title' => 'Bieg — tempo progowe',
            'date' => '2026-11-05',
            'duration' => 50,
            'distance' => 10.0,
            'zone' => 'Z4',
            'description' => '2 km rozgrzewki, 3 × 2 km w tempie progowym, 2 km schłodzenia.',
        ],
        [
            'id' => 4,
            'sport' => 'swim',
            'title' => 'Pływanie — technika',
            'date' => '2026-11-06',
            'duration' => 40,
            'distance' => 1.5,
            'zone' => 'Z1',
            'description' => 'Ćwiczenia z pull buoyem, praca nad rotacją i oddechem.',
        ],
        [
            'id' => 5,
            'sport' => 'bike',
            'title' => 'Rower — długi wyjazd',
            'date' => '2026-11-07',
            'duration' => 150,
            'distance' => 75.0,
            'zone' => 'Z2',
            'description' => 'Spokojny wyjazd aerobowy z kontrolą kadencji i nawodnienia.',
        ],
        [
            'id' => 6,
            'sport' => 'run',
            'title' => 'Bieg — długie wybieganie',
            'date' => '2026-11-08',
            'duration' => 80,
            'distance' => 15.0,
            'zone' => 'Z2',
            'description' => 'Równe tempo, ostatnie 15 minut nieco szybciej.',
        ],
        [
            'id' => 7,
            'sport' => 'run',
            'title' => 'Brick — rower + bieg',
            'date' => '2026-11-09',
            'duration' => 100,
            'distance' => 32.0,
            'zone' => 'Z3',
            'description' => '60 minut roweru, natychmiast 20 minut biegu w tempie startowym.',
        ],
        [
            'id' => 8,
            'sport' => 'swim',
            'title' => 'Pływanie — start w wodzie',
            'date' => '2026-11-10',
            'duration' => 35,
            'distance' => 1.2,
            'zone' => 'Z2',
            'description' => 'Symulacja startu, nawigacja i zmiana tempa na boi.',
        ],
    ];

    /**
     * @return list<array{id: int, sport: string, title: string, date: string, duration: int, distance: float, zone: string, description: string}>
     */
    public function all(): array
    {
        return $this->sessions;
    }

    /**
     * @return array{id: int, sport: string, title: string, date: string, duration: int, distance: float, zone: string, description: string}|null
     */
    public function find(int $id): ?array
    {
        foreach ($this->sessions as $session) {
            if ($session['id'] === $id) {
                return $session;
            }
        }

        return null;
    }

    /**
     * @return list<array{id: int, sport: string, title: string, date: string, duration: int, distance: float, zone: string, description: string}>
     */
    public function filter(?string $sport = null, ?string $query = null): array
    {
        return array_values(array_filter(
            $this->sessions,
            static function (array $session) use ($sport, $query): bool {
                if ($sport !== null && $sport !== '' && $session['sport'] !== $sport) {
                    return false;
                }

                if ($query !== null && $query !== '') {
                    $haystack = strtolower($session['title'] . ' ' . $session['description']);

                    if (!str_contains($haystack, strtolower($query))) {
                        return false;
                    }
                }

                return true;
            },
        ));
    }

    /**
     * @return list<array{id: int, sport: string, title: string, date: string, duration: int, distance: float, zone: string, description: string}>
     */
    public function upcoming(int $limit = 4): array
    {
        return array_slice($this->sessions, 0, $limit);
    }

    /**
     * @return list<array{id: int, sport: string, title: string, date: string, duration: int, distance: float, zone: string, description: string}>
     */
    public function forYear(int $year): array
    {
        return array_values(array_filter(
            $this->sessions,
            static fn (array $session): bool => str_starts_with($session['date'], (string) $year),
        ));
    }

    /**
     * @param list<array{id: int, sport: string, title: string, date: string, duration: int, distance: float, zone: string, description: string}>|null $sessions
     * @return array{total: int, minutes: int, distance: float, bySport: array<string, int>}
     */
    public function summary(?array $sessions = null): array
    {
        $sessions ??= $this->sessions;
        $summary = [
            'total' => count($sessions),
            'minutes' => 0,
            'distance' => 0.0,
            'bySport' => ['swim' => 0, 'bike' => 0, 'run' => 0],
        ];

        foreach ($sessions as $session) {
            $summary['minutes'] += $session['duration'];
            $summary['distance'] += $session['distance'];
            $summary['bySport'][$session['sport']]++;
        }

        return $summary;
    }
}
