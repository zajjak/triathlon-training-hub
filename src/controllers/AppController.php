<?php

declare(strict_types=1);

namespace App\Controller;

use RuntimeException;

abstract class AppController
{
    private const VIEW_PATH = __DIR__ . '/../../public/views';

    /**
     * Renderuje widok w wierszu opakowującym layout i wspólne partiale.
     *
     * @param array<string, mixed> $data
     */
    protected function render(string $view, array $data = [], int $status = 200): void
    {
        http_response_code($status);

        $viewFile = self::VIEW_PATH . '/' . $view . '.html.php';

        if (!is_file($viewFile)) {
            throw new RuntimeException(sprintf('Nie znaleziono widoku "%s".', $view));
        }

        $pageTitle = $data['pageTitle'] ?? 'Triathlon Training Hub';
        $activeNav = $data['activeNav'] ?? '';
        $metaDescription = $data['metaDescription']
            ?? 'Triathlon Training Hub — planowanie i analiza treningów.';

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = (string) ob_get_clean();

        require self::VIEW_PATH . '/layout.html.php';
    }

    public function notFound(string $message = 'Nie znaleziono strony o podanym adresie.'): void
    {
        $this->render('error', [
            'pageTitle' => 'Nie znaleziono strony',
            'activeNav' => '',
            'statusCode' => 404,
            'title' => 'Nie znaleziono strony',
            'message' => $message,
        ], 404);
    }

    public function badRequest(string $message = 'Nieprawidłowe żądanie.'): void
    {
        $this->render('error', [
            'pageTitle' => 'Nieprawidłowe żądanie',
            'activeNav' => '',
            'statusCode' => 400,
            'title' => 'Nieprawidłowe żądanie',
            'message' => $message,
        ], 400);
    }

    /**
     * @param list<string> $allowedMethods
     */
    public function methodNotAllowed(array $allowedMethods = []): void
    {
        $this->render('error', [
            'pageTitle' => 'Metoda niedozwolona',
            'activeNav' => '',
            'statusCode' => 405,
            'title' => 'Metoda niedozwolona',
            'message' => 'Ten adres obsługuje inną metodę HTTP.',
            'allowedMethods' => $allowedMethods,
        ], 405);
    }

    public function serverError(): void
    {
        $this->render('error', [
            'pageTitle' => 'Błąd serwera',
            'activeNav' => '',
            'statusCode' => 500,
            'title' => 'Błąd serwera',
            'message' => 'Coś poszło nie tak. Spróbuj ponownie później.',
        ], 500);
    }
}
