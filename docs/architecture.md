# Architektura EVENTO

## Cel dokumentu

Ten dokument opisuje **aktualnie zaimplementowaną architekturę techniczną** projektu EVENTO.

Zaakceptowane decyzje architektoniczne, również te jeszcze niewdrożone, znajdują się w:

`docs/architecture-decision-record.md`

Plan dalszej implementacji znajduje się w:

`docs/roadmap.md`

## Aktualne środowisko

Projekt jest rozwijany w środowisku:

* WSL2,
* Ubuntu,
* Docker,
* Docker Compose,
* MariaDB,
* WordPress,
* phpMyAdmin,
* WP-CLI.

## Aktualna struktura projektu

```text
evento/
├── demo/
│   ├── data/
│   └── images/
├── docs/
├── theme/
├── .env.example
├── .gitignore
├── docker-compose.yml
├── Makefile
└── README.md
```

## WordPress

WordPress działa w kontenerze Docker na oficjalnym obrazie WordPress.

WordPress Core nie znajduje się w repozytorium.

Instalacja WordPressa jest przechowywana w Docker Volume:

```text
wordpress_data
```

Katalog `theme/` jest montowany do:

```text
/var/www/html/wp-content/themes/evento-cbt
```

Katalog motywu używa sluga `evento-cbt`, aby uniknąć kolizji z istniejącym motywem `evento` w repozytorium WordPress.org.

## MariaDB

MariaDB działa jako osobny serwis Docker Compose.

Dane bazy są przechowywane w Docker Volume i nie są wersjonowane w Git.

Port `3306` nie jest publikowany na hoście. WordPress komunikuje się z MariaDB wewnątrz sieci Docker.

## phpMyAdmin

phpMyAdmin jest dostępny jako osobny serwis developerski.

Domyślny port hosta:

```text
9051
```

## Konfiguracja

Lokalna konfiguracja projektu znajduje się w:

```text
.env
```

Plik ten jest ignorowany przez Git.

Repozytorium zawiera:

```text
.env.example
```

## Motyw

Kod własnego motywu będzie rozwijany w:

```text
theme/
```

Motyw jest własnym Block Theme tworzonym od zera.

Aktualnie zawiera strukturę:

```text
theme/
├── style.css
├── functions.php
├── theme.json
├── package.json
├── package-lock.json
├── inc/
│   ├── post-types.php
│   ├── taxonomies.php
│   ├── meta.php
│   └── blocks.php
├── src/
│   └── blocks/
│       ├── upcoming-events/
│       └── event-categories/
├── templates/
│   ├── index.html
│   └── front-page.html
└── parts/
    ├── header.html
    └── footer.html
```

Nazwa motywu widoczna w WordPressie to `EVENTO`.

Slug katalogu motywu to:

```text
evento-cbt
```

Plik `theme.json` definiuje aktualnie:

* szerokości layoutu `contentSize` i `wideSize`,
* obsługę fluid typography,
* ustawienia spacingu dla paddingu, marginesów i `blockGap`,
* dwie rodziny fontów: `sans` (Manrope, tekst) i `display` (Unbounded, nagłówki) oraz skalę rozmiarów tekstu (`small` … `xx-large`),
* paletę kolorów opartą na trzech akcentach — `park` (zielony, główny), `sky` (niebieski), `sun` (żółty) — oraz neutralnych `stone-bg`, `stone-surface`, `stone-line`, `ink`, `ink-soft`,
* domyślne style elementów `link`, `button` i `heading`,
* template parts `header` i `footer` widoczne w Site Editorze jako `EVENTO Header` i `EVENTO Footer`.

Fonty Manrope i Unbounded są ładowane z Google Fonts przez `wp_enqueue_style()` w `functions.php` (ta sama usługa, z której korzysta wizualny wzorzec referencyjny motywu).

Plik `theme/style.css` jest jawnie kolejkowany przez `wp_enqueue_style()` w `functions.php` (blok theme nie robi tego automatycznie) i zawiera niestandardowe style layoutu strony (header, hero, sekcje, CTA, stopka) oraz współdzielone klasy komponentów (`evento-btn-primary`, `evento-btn-secondary`, `evento-badge-pill`, `evento-card-surface`, `evento-logo-mark`), które wykraczają poza to, co wyraża `theme.json`.

Proces budowania assetów motywu jest skonfigurowany w:

```text
theme/package.json
theme/package-lock.json
```

Motyw korzysta z pakietu `@wordpress/scripts` oraz skryptów:

```bash
npm run build
npm run start
```

`@wordpress/scripts` automatycznie wykrywa pliki `block.json` w `theme/src/blocks/*` i buduje każdy blok do odpowiadającego katalogu w `theme/build/blocks/*` (katalog `build/` nie jest wersjonowany).

Motyw rejestruje aktualnie Custom Post Type:

```text
event
venue
```

Typ `event` jest publiczny, widoczny w REST API z bazą `events`, posiada archiwum pod adresem `events` i obsługuje tytuł, edytor, Featured Image oraz custom fields.

Typ `venue` jest publiczny, widoczny w REST API z bazą `venues`, posiada archiwum pod adresem `venues` i obsługuje tytuł, edytor, Featured Image oraz custom fields.

Motyw rejestruje aktualnie taksonomię:

```text
event_category
district
```

Taksonomia `event_category` jest przypisana do typu `event`, jest hierarchiczna, publiczna i widoczna w REST API z bazą `event-categories`.

Taksonomia `district` jest przypisana do typu `venue`, jest hierarchiczna, publiczna i widoczna w REST API z bazą `districts`.

Motyw rejestruje aktualnie meta fields dla typu `event`:

```text
_event_start_datetime
_event_end_datetime
_event_venue_id
_event_organizer
_event_price_from
_event_is_free
_event_ticket_url
```

Pola meta typu `event` są rejestrowane przez `register_post_meta()`, posiadają jawne typy, są pojedynczymi wartościami i są widoczne w REST API.

Daty wydarzeń są przechowywane jako string w formacie:

```text
YYYY-MM-DD HH:MM:SS
```

Pole `_event_price_from` jest przechowywane jako decimal string z dwoma miejscami po przecinku.

Pole `_event_is_free` jest przechowywane jako `0` albo `1`.

Pole `_event_venue_id` reprezentuje relację `event -> venue`.

Wartość `0` oznacza brak przypisanego miejsca. Wartość dodatnia jest zachowywana tylko wtedy, gdy wskazuje na istniejący post typu `venue`.

Motyw rejestruje aktualnie meta fields dla typu `venue`:

```text
_venue_address
_venue_latitude
_venue_longitude
_venue_website
```

Pola meta typu `venue` są rejestrowane przez `register_post_meta()`, posiadają jawne typy, są pojedynczymi wartościami i są widoczne w REST API.

Pola `_venue_latitude` i `_venue_longitude` są przechowywane jako wartości liczbowe.

## Customowe bloki Gutenberg

Motyw rejestruje aktualnie własne, dynamiczne (server-side rendered) bloki Gutenberg:

```text
evento/upcoming-events
evento/event-categories
evento/district-map
```

Źródła bloków znajdują się w `theme/src/blocks/*`, są budowane przez `@wordpress/scripts` do `theme/build/blocks/*` i rejestrowane w `theme/inc/blocks.php` przez `register_block_type()` wywoływane dla każdego katalogu z plikiem `block.json` w `theme/build/blocks`.

Blok `evento/upcoming-events` renderuje siatkę kart nadchodzących wydarzeń (`post_type=event`, `_event_start_datetime >= teraz`, sortowanie rosnąco po dacie), z limitem liczby wydarzeń ustawianym atrybutem `count`. Karta pokazuje zdjęcie, kategorie i cenę/„Bezpłatne” jako plakietki nad zdjęciem, czas wydarzenia oraz miejsce z dzielnicą.

Blok `evento/event-categories` renderuje kategorie taksonomii `event_category` jako plakietki (pills) z liczbą przypisanych wydarzeń, linkujące do archiwów kategorii.

Blok `evento/district-map` renderuje wszystkie terminy taksonomii `district` jako kafelki, których kolor tła jest interpolowany między kolorem `sky` a `sun` proporcjonalnie do liczby nadchodzących wydarzeń w miejscach (`venue`) przypisanych do danej dzielnicy — im więcej wydarzeń, tym „cieplejszy” kolor. Każdy kafelek linkuje do archiwum danej dzielnicy.

Wszystkie trzy bloki renderują swoje dane po stronie serwera (`render.php`), a w edytorze Gutenberg wykorzystują `ServerSideRender` do podglądu na żywo rzeczywistych danych.

## Szablony i wygląd strony głównej

Strona główna korzysta z szablonu Site Editora:

```text
theme/templates/front-page.html
```

Szablon łączy natywne bloki Gutenberg (nagłówki, przyciski, grupy) z customowymi blokami `evento/upcoming-events`, `evento/event-categories` i `evento/district-map`.

Nagłówek (`parts/header.html`) zawiera oznaczenie marki (`evento-logo-mark` + `site-title`), blok `navigation` z linkami do archiwów `/events/` i `/venues/` oraz przycisk CTA (ukrywany poniżej 600px, gdzie nawigacja zwija się do menu hamburgerowego). Stopka (`parts/footer.html`) to trzykolumnowa siatka (marka, kategorie przez natywny blok `core/categories` z `taxonomy: event_category`, nawigacja) zwijana do jednej kolumny poniżej 600px, celowo bez bloku `navigation` w stopce, aby uniknąć mobilnego menu typu hamburger tam, gdzie linki powinny być zawsze widoczne.

## WP-CLI

WP-CLI działa jako osobny serwis Docker Compose na oficjalnym obrazie:

```text
wordpress:cli-php8.3
```

Serwis `cli` współdzieli z serwisem `wordpress` ten sam wolumen:

```text
wordpress_data:/var/www/html
```

Dzięki temu WP-CLI widzi tę samą instalację WordPressa, co kontener aplikacji.

WP-CLI jest uruchamiany jako narzędzie developerskie, na przykład przez:

```bash
make wp ARGS="--info"
```

Interaktywny shell w kontenerze WP-CLI jest dostępny przez:

```bash
make wpcli
```

Serwis `cli` działa z uprawnieniami zgodnymi z plikami WordPressa w wolumenie `wordpress_data`, dzięki czemu WP-CLI może modyfikować pliki WordPressa, w tym `wp-content/uploads`.

Cache WP-CLI jest kierowany do:

```text
/tmp/.wp-cli/cache
```

## Automatyczna instalacja

Komenda:

```bash
make install
```

instaluje WordPressa za pomocą WP-CLI.

Instalacja korzysta ze zmiennych środowiskowych:

```text
WP_URL
WP_TITLE
WP_ADMIN_USER
WP_ADMIN_PASSWORD
WP_ADMIN_EMAIL
```

Komenda jest idempotentna. Jeżeli WordPress jest już zainstalowany, ponowne uruchomienie `make install` nie wykonuje instalacji ponownie.

Komenda ustawia również strukturę permalinków:

```text
/%postname%/
```

Dzięki temu REST API jest dostępne pod czytelnymi adresami `/wp-json/...`, a własne typy treści mogą korzystać z przyjaznych adresów URL.

Na tym etapie `make install` nie aktywuje jeszcze motywu.

## Dane demonstracyjne

Katalogi:

```text
demo/data/
demo/images/
```

są przygotowane jako miejsce na źródła danych demonstracyjnych.

Mechanizm ich importu nie został jeszcze zaimplementowany.

## `make seed`

Komenda:

```bash
make seed
```

nie została jeszcze zaimplementowana.

Docelowo będzie generowała dane demonstracyjne i importowała obrazy do WordPress Media Library.

## Media

Pliki WordPressa, w tym `wp-content/uploads/`, są przechowywane w wolumenie:

```text
wordpress_data
```

Mechanizm automatycznego importowania i odtwarzania mediów nie został jeszcze zaimplementowany.

## Aktualizacja dokumentu

Ten dokument należy aktualizować po wdrożeniu kolejnych elementów architektury.

Nie powinien opisywać funkcjonalności jako istniejącej, dopóki nie została faktycznie zaimplementowana.
