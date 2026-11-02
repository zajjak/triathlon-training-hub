<?php

declare(strict_types=1);
$routes = [
    ['GET', '/', 'Strona główna — trasa statyczna', '200'],
    ['GET', '/about', 'Ta strona — trasa statyczna', '200'],
    ['GET', '/sessions', 'Lista sesji + query params ?sport=&q=', '200 / 400'],
    ['GET', '/sessions/{id}', 'Parametr ścieżki + walidacja identyfikatora', '200 / 400 / 404'],
    ['GET', '/sports/{sport:swim|bike|run}', 'Regex z alternatywą dyscyplin', '200 / 404'],
    ['GET', '/reports/{year:\d{4}}', 'Regex czterocyfrowego roku', '200 / 400 / 404'],
    ['POST', '/sessions', 'Brak trasy POST — odpowiedź 405', '405'],
];
?>
<section class="page-head">
    <p class="eyebrow">Prototyp MVC</p>
    <h1>Jak działa ta aplikacja?</h1>
    <p class="page-head__lead">
        Projekt jest lekkim szkieletem MVC w PHP 8.3. Router dopasowuje metodę HTTP
        i wzorzec adresu, kontroler przygotowuje dane, a widok odpowiada wyłącznie
        za prezentację.
    </p>
</section>

<section class="section" aria-labelledby="flow-heading">
    <div class="section__header">
        <div>
            <p class="eyebrow">Przepływ żądania</p>
            <h2 id="flow-heading">Od adresu do HTML</h2>
        </div>
    </div>

    <ol class="flow-list">
        <li>
            <strong>public/index.php</strong>
            <span>Punkt wejścia. Dołącza <code>vendor/autoload.php</code> przez <code>require_once</code> i rejestruje trasy.</span>
        </li>
        <li>
            <strong>App\Routing</strong>
            <span>Zamienia <code>{id}</code> i <code>{year:\d{4}}</code> na nazwane grupy regex, dopasowuje metodę i adres.</span>
        </li>
        <li>
            <strong>Kontroler</strong>
            <span>Waliduje parametry, pobiera dane z repozytorium i wybiera widok lub odpowiedź błędu.</span>
        </li>
        <li>
            <strong>Widok + layout</strong>
            <span>Wspólny layout, nagłówek, nawigacja i stopka są wydzielone do partiali. Dane są escapowane funkcją <code>e()</code>.</span>
        </li>
    </ol>
</section>

<section class="section" aria-labelledby="routes-heading">
    <div class="section__header">
        <div>
            <p class="eyebrow">Routing</p>
            <h2 id="routes-heading">Trasy prototypu</h2>
        </div>
        <p class="section__lead">Kolejność ma znaczenie — wygrywa pierwsza pasująca trasa.</p>
    </div>

    <div class="table-wrap">
        <table class="route-table">
            <caption class="visually-hidden">Lista tras, ich opis i możliwe odpowiedzi HTTP</caption>
            <thead>
                <tr>
                    <th scope="col">Metoda</th>
                    <th scope="col">Wzorzec</th>
                    <th scope="col">Opis</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($routes as [$method, $path, $description, $status]): ?>
                    <tr>
                        <td><code><?= e($method) ?></code></td>
                        <td><code><?= e($path) ?></code></td>
                        <td><?= e($description) ?></td>
                        <td><?= e($status) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="section" aria-labelledby="structure-heading">
    <div class="section__header">
        <div>
            <p class="eyebrow">Kod</p>
            <h2 id="structure-heading">Warstwy i odpowiedzialności</h2>
        </div>
    </div>

    <div class="info-grid">
        <article class="info-card">
            <h3>Public</h3>
            <p>Front controller, style CSS i widoki. Nic więcej nie powinno być dostępne z przeglądarki.</p>
        </article>
        <article class="info-card">
            <h3>Controllers</h3>
            <p>Przygotowują dane i wybierają widok. Nie zawierają zapytań SQL ani rozbudowanego HTML.</p>
        </article>
        <article class="info-card">
            <h3>Repositories</h3>
            <p>Jedno miejsce dostępu do danych. W prototypie działa repozytorium w pamięci, później zamieni je PDO.</p>
        </article>
        <article class="info-card">
            <h3>Composer</h3>
            <p>PSR-4 mapuje namespace <code>App\</code> na katalog <code>src/</code>. Klasy ładują się automatycznie, bez ręcznych <code>require</code>.</p>
        </article>
    </div>

    <aside class="callout">
        <h2>Dlaczego <code>require_once</code> tylko w punkcie wejścia?</h2>
        <p>
            <code>require_once</code> gwarantuje, że bootstrap i autoloader zostaną dołączone
            dokładnie raz. Klasy są ładowane przez Composer, a widoki przez zwykłe
            <code>require</code> — bo szablon może być renderowany wielokrotnie w jednym żądaniu.
        </p>
    </aside>
</section>
