<?php

declare(strict_types=1);

namespace App;

use App\Controller\AppController;
use RuntimeException;

final class Routing
{
    /**
     * @var list<array{
     *     method: string,
     *     path: string,
     *     pattern: string,
     *     controller: class-string<AppController>,
     *     action: string
     * }>
     */
    private array $routes = [];

    public function get(string $path, string $controller, string $action): void
    {
        $this->add('GET', $path, $controller, $action);
    }

    public function post(string $path, string $controller, string $action): void
    {
        $this->add('POST', $path, $controller, $action);
    }

    public function dispatch(string $method, string $uri): bool
    {
        $path = $this->pathFromUri($uri);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method || !preg_match($route['pattern'], $path, $matches)) {
                continue;
            }

            $parameters = array_filter(
                $matches,
                static fn (int|string $key): bool => is_string($key),
                ARRAY_FILTER_USE_KEY,
            );

            $controllerClass = $route['controller'];
            $controller = new $controllerClass();

            if (!$controller instanceof AppController) {
                throw new RuntimeException(
                    sprintf('Kontroler %s musi dziedziczyć po %s.', $controllerClass, AppController::class),
                );
            }

            $controller->{$route['action']}(...array_values($parameters));

            return true;
        }

        return false;
    }

    /**
     * Zwraca metody obsługiwane dla danego adresu — używane do odpowiedzi 405.
     *
     * @return list<string>
     */
    public function allowedMethods(string $uri): array
    {
        $path = $this->pathFromUri($uri);
        $methods = [];

        foreach ($this->routes as $route) {
            if (preg_match($route['pattern'], $path) === 1) {
                $methods[] = $route['method'];
            }
        }

        return array_values(array_unique($methods));
    }

    private function add(string $method, string $path, string $controller, string $action): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $this->compilePattern($path),
            'controller' => $controller,
            'action' => $action,
        ];
    }

    /**
     * Zamienia segmenty typu {id} i {rok:\d{4}} na nazwane grupy wyrażenia regularnego.
     */
    private function compilePattern(string $path): string
    {
        if ($path === '/') {
            return '~^/$~';
        }

        $segments = explode('/', trim($path, '/'));
        $pattern = '';

        foreach ($segments as $segment) {
            $pattern .= '/';

            if (preg_match('/^\{([a-zA-Z_][a-zA-Z0-9_]*)(?::(.+))?\}$/', $segment, $matches) === 1) {
                $name = $matches[1];
                $constraint = $matches[2] ?? '[^/]+';
                $pattern .= sprintf('(?P<%s>%s)', $name, $constraint);
                continue;
            }

            $pattern .= preg_quote($segment, '~');
        }

        return '~^' . $pattern . '/?$~';
    }

    private function pathFromUri(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH);

        return is_string($path) && $path !== '' ? $path : '/';
    }
}
