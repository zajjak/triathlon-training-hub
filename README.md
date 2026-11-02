# Triathlon Training Hub

Aplikacja do planowania i analizy treningów triathlonowych: pływania, roweru i biegu
w jednym miejscu.

Projekt jest budowany etapami. **Aktualny etap to prototyp szkieletu MVC** — routing,
kontrolery, widoki, autoloading Composera i stylowanie. Baza PostgreSQL, logowanie,
JavaScript i pozostałe funkcje pojawią się w kolejnych krokach.

## Autor

Jakub Zając

## Zakres prototypu

- własny router z obsługą metod HTTP,
- trasy statyczne, wzorce regex i parametry ścieżki,
- walidacja parametrów i kontrolowane odpowiedzi 400 / 404 / 405,
- query params do filtrowania i wyszukiwania sesji,
- kontrolery i wspólny layout z partialami,
- repozytorium w pamięci z przykładowymi sesjami triathlonowymi,
- autoloading PSR-4 przez Composer,
- responsywny interfejs w czystym CSS.

## Wymagania

- Docker Desktop lub Docker Engine z Docker Compose.
- Wolny port `8080` (lub inny ustawiony w `.env`).
- PHP i Composer nie muszą być instalowane lokalnie — wszystko działa w kontenerach.

## Uruchomienie

```powershell
Copy-Item .env.example .env
docker compose up --build -d
```

Aplikacja: <http://localhost:8080>

Jeśli port `8080` jest zajęty, zmień `APP_PORT` w `.env`, na przykład na `8081`,
i uruchom ponownie:

```powershell
docker compose up -d
```

Przydatne polecenia:

```powershell
docker compose ps
docker compose logs -f
docker compose down
```

Kontener `php` przy pierwszym starcie sam uruchamia `composer install`, jeśli brakuje
katalogu `vendor/`.

## Struktura projektu

```text
.
├─ public/
│  ├─ index.php              # front controller i rejestracja tras
│  ├─ css/
│  │  ├─ variables.css       # tokeny designu
│  │  └─ style.css           # layout, komponenty, responsywność
│  └─ views/                 # layout, partiale i widoki
├─ src/
│  ├─ Routing.php            # dopasowanie metod, wzorców i parametrów
│  ├─ helpers.php            # e(), sportLabel(), formatDate()
│  ├─ controllers/           # AppController, DefaultController, ...
│  └─ repositories/          # SessionRepository (dane w pamięci)
├─ docker/
│  ├─ php/                   # Dockerfile i entrypoint
│  └─ nginx/                 # konfiguracja serwera
├─ composer.json
├─ docker-compose.yaml
└─ .env.example
```

## Routing

| Metoda | Wzorzec | Opis | Status |
|---|---|---|---|
| GET | `/` | strona główna, trasa statyczna | 200 |
| GET | `/about` | opis projektu, trasa statyczna | 200 |
| GET | `/sessions` | lista sesji, query params `?sport=&q=` | 200 / 400 |
| GET | `/sessions/{id}` | parametr ścieżki z walidacją | 200 / 400 / 404 |
| GET | `/sports/{sport:swim\|bike\|run}` | regex z alternatywą | 200 / 404 |
| GET | `/reports/{year:\d{4}}` | regex czterocyfrowego roku | 200 / 400 / 404 |
| POST | `/sessions` | brak trasy POST | 405 + `Allow: GET` |

Przykłady zachowania:

- `/sessions/1` → 200,
- `/sessions/abc` → 400 (identyfikator nie jest liczbą),
- `/sessions/999` → 404 (sesja nie istnieje),
- `/sessions?sport=tennis` → 400 (niedozwolona dyscyplina),
- `/sports/tennis` → 404 (regex nie pasuje),
- `/reports/0001` → 400 (rok poza zakresem 2000–2100),
- `POST /sessions` → 405 z nagłówkiem `Allow: GET`.

## Autoloading i `require_once`

`public/index.php` dołącza autoloader dokładnie raz:

```php
require_once dirname(__DIR__) . '/vendor/autoload.php';
```

Mapowanie PSR-4 w `composer.json`:

- `App\Routing` → `src/Routing.php`,
- `App\Controller\...` → `src/controllers/...`,
- `App\Repository\...` → `src/repositories/...`.

Dzięki temu klasy nie wymagają ręcznego `require`. Widoki i partiale są dołączane
zwykłym `require`, ponieważ mogą być renderowane wielokrotnie w jednym żądaniu.

## Dane demonstracyjne

Na tym etapie dane są przechowywane w `src/repositories/SessionRepository.php`
(8 sesji: pływanie, rower, bieg). Repozytorium ma wspólny interfejs metod
`all()`, `find()`, `filter()`, `upcoming()`, `forYear()` i `summary()`, więc
w kolejnym etapie podmiana na PostgreSQL nie będzie wymagała zmian w widokach.

## Stylowanie

- tokeny kolorów, typografii i odstępów w `public/css/variables.css`,
- komponenty i układ w `public/css/style.css`,
- brak stylów inline,
- siatki `auto-fit`/`minmax` i media queries dla 900 px i 600 px,
- wspólny layout, nagłówek, nawigacja i stopka w partialach,
- skip link, widoczny fokus, etykiety formularzy i semantyczny HTML.

## Weryfikacja

Składnia wszystkich plików PHP:

```powershell
docker compose exec -T php sh -c "find src public -name '*.php' -exec php -l {} \;"
```

Szybki test statusów:

```powershell
curl.exe -s -o NUL -w "%{http_code}`n" http://localhost:8080/
curl.exe -s -o NUL -w "%{http_code}`n" http://localhost:8080/sessions/abc
curl.exe -s -o NUL -w "%{http_code}`n" http://localhost:8080/sessions/999
curl.exe -s -o NUL -w "%{http_code}`n" -X POST http://localhost:8080/sessions
```

## Kolejne etapy

1. PostgreSQL: schemat, dane demo, eksport `.sql`.
2. Logowanie, sesje i role użytkowników.
3. JavaScript, Fetch API, tryb ciemny i tłumaczenia PL/EN.
4. Testy jednostkowe, CRON, e-mail, upload zdjęć.
5. Raport WCAG, Security Bingo, dokumentacja końcowa i deploy.
