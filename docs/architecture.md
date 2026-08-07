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
* phpMyAdmin.

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

Katalog `theme/` jest montowany do:

```text
/var/www/html/wp-content/themes/evento
```

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

Motyw nie został jeszcze zaimplementowany.

## WP-CLI

WP-CLI jest zaakceptowanym elementem docelowej architektury, ale nie został jeszcze dodany do aktualnej konfiguracji Docker Compose.

Jego implementacja znajduje się w roadmapie.

## Automatyczna instalacja

Komenda:

```bash
make install
```

nie została jeszcze zaimplementowana.

Docelowo będzie instalowała i konfigurowała WordPress za pomocą WP-CLI.

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

Obecnie projekt nie posiada mechanizmu automatycznego odtwarzania WordPress Media Library.

Mechanizm importowania i odtwarzania mediów nie został jeszcze zaimplementowany.

## Aktualizacja dokumentu

Ten dokument należy aktualizować po wdrożeniu kolejnych elementów architektury.

Nie powinien opisywać funkcjonalności jako istniejącej, dopóki nie została faktycznie zaimplementowana.
