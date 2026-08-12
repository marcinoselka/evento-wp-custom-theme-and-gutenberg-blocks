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

Aktualnie zawiera minimalną strukturę:

```text
theme/
├── style.css
├── functions.php
├── theme.json
├── templates/
│   └── index.html
└── parts/
    ├── header.html
    └── footer.html
```

Nazwa motywu widoczna w WordPressie to `EVENTO`.

Slug katalogu motywu to:

```text
evento-cbt
```

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
