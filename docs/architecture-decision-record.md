# EVENTO - Architecture Decision Record

## Cel dokumentu

Ten dokument jest głównym źródłem prawdy dotyczącym zaakceptowanych decyzji architektonicznych projektu EVENTO.

Historia zmian jest przechowywana w Git, dlatego nie prowadzimy numerowanych ADR-ów.

## 1. Projekt

EVENTO jest portalem wydarzeń w Warszawie.

Architektura powinna umożliwiać dodanie kolejnych miast w przyszłości, ale obecna implementacja skupia się wyłącznie na Warszawie.

Priorytety:

* czytelny i łatwy w utrzymaniu kod,
* prosta architektura,
* automatyzacja,
* powtarzalne środowisko,
* wydajność,
* dostępność,
* bezpieczeństwo.

## 2. Stabilność architektury

Zaakceptowanych decyzji nie zmieniamy tylko dlatego, że istnieje alternatywa.

Zmiana jest uzasadniona, gdy:

* obecne rozwiązanie jest błędne,
* pojawia się realne ograniczenie techniczne,
* występuje problem bezpieczeństwa lub kompatybilności,
* nowe rozwiązanie daje istotną, uzasadnioną korzyść.

Świadome zmiany architektury aktualizujemy w tym dokumencie.

## 3. Środowisko

Podstawowe środowisko developerskie:

* WSL2,
* Ubuntu,
* Docker,
* Docker Compose.

Projekt powinien działać również na innych środowiskach obsługujących Docker Compose.

## 4. Docker

Korzystamy z oficjalnych obrazów:

* WordPress,
* MariaDB,
* phpMyAdmin,
* WP-CLI.

WordPress Core nie jest przechowywany ani modyfikowany w repozytorium.

Domyślne porty hosta:

| Usługa     |   Port |
| ---------- | -----: |
| WordPress  | `9050` |
| phpMyAdmin | `9051` |

Port MariaDB `3306` pozostaje dostępny wyłącznie wewnątrz sieci Docker.

## 5. Konfiguracja i sekrety

Lokalna konfiguracja znajduje się w:

```text
.env
```

Plik `.env` nie jest wersjonowany.

Repozytorium zawiera:

```text
.env.example
```

`.env.example` powinien umożliwiać szybkie uruchomienie lokalnego środowiska po:

```bash
cp .env.example .env
```

Prawdziwe sekrety, klucze API i dane produkcyjne nigdy nie trafiają do Git.

## 6. WordPress i motyw

Projekt korzysta z aktualnego WordPressa dostarczanego przez oficjalny obraz Docker.

Własny motyw jest tworzony od podstaw i znajduje się w:

```text
theme/
```

Nie korzystamy z gotowego motywu ani starter theme jako podstawy projektu.

Preferujemy oficjalne API WordPressa zamiast bezpośrednich operacji na bazie danych.

## 7. Customowy Block Theme

EVENTO będzie wykorzystywać własny WordPress Block Theme tworzony od zera.

Nie korzystamy z:

* starter theme,
* parent theme,
* klasycznych template'ów PHP jako podstawy motywu.

Struktura motywu będzie oparta na natywnych mechanizmach Block Theme, w szczególności:

* `theme.json`,
* `templates/*.html`,
* `parts/*.html`,
* natywnych blokach WordPress,
* własnych customowych blokach Gutenberg.

Nie tworzymy klasycznych plików takich jak:

```text
header.php
footer.php
index.php
```

jeżeli nie pojawi się konkretna potrzeba techniczna.

`functions.php` pozostaje częścią motywu i będzie wykorzystywany tylko do funkcjonalności wymagających PHP, takich jak późniejsza rejestracja bloków, assetów lub innych elementów motywu.

Nie tworzymy katalogów ani mechanizmów wyłącznie na przyszłość. Elementy takie jak `patterns/` powstaną dopiero wtedy, gdy projekt zacznie faktycznie korzystać z Block Patterns.

## 8. Gutenberg i dane

Interfejs opiera się na natywnym Gutenbergu.

Customowe bloki są tworzone przy użyciu oficjalnego WordPress Block API.

Nie używamy ACF Blocks ani ACF jako wymaganej warstwy danych.

Dane domenowe wykorzystują natywne mechanizmy WordPressa, między innymi:

* Custom Post Types,
* taxonomies,
* post meta,
* REST API,
* block attributes.

## 9. WP-CLI i automatyzacja

WP-CLI będzie standardowym elementem środowiska Docker Compose i służy do automatyzacji między innymi:

* instalacji WordPressa,
* konfiguracji,
* aktywacji motywu,
* zarządzania ustawieniami,
* generowania danych demonstracyjnych,
* importu mediów.

Docelowa instalacja powinna działać bez ręcznego kreatora WordPressa.

## 10. `make install`

Komenda:

```bash
make install
```

powinna automatycznie:

* zainstalować WordPress,
* skonfigurować podstawowe ustawienia,
* utworzyć administratora,
* aktywować wymagane elementy projektu.

## 11. Dane demonstracyjne i `make seed`

Nie wersjonujemy:

* dumpów SQL,
* wygenerowanego `wp-content/uploads/`,
* danych wygenerowanych przez WordPress.

Źródła danych demonstracyjnych znajdują się w:

```text
demo/
├── data/
└── images/
```

Komenda:

```bash
make seed
```

powinna programowo utworzyć komplet danych demonstracyjnych, między innymi:

* wydarzenia,
* miejsca,
* organizatorów,
* kategorie,
* strony,
* menu,
* konfigurację strony głównej.

Dane tworzymy przez WordPress API i/lub WP-CLI, bez bezpośredniego importu SQL.

## 12. Media Library

Obrazy z `demo/images/` są materiałami źródłowymi.

`make seed` powinien importować je do WordPress Media Library, dzięki czemu WordPress będzie odpowiadał za:

* zapis w `wp-content/uploads/`,
* utworzenie attachmentów,
* metadane,
* miniatury,
* Featured Images.

Nie kopiujemy ręcznie danych demonstracyjnych do `uploads`.

## 13. Baza danych i odtwarzalność

MariaDB przechowuje lokalny stan WordPressa, ale baza nie jest częścią repozytorium.

Źródłem prawdy dla danych demonstracyjnych są pliki `demo/` oraz kod procesu seedowania.

Docelowo czyste środowisko powinno być możliwe do odtworzenia przez:

```bash
cp .env.example .env
make up
make install
make seed
```

## 14. Makefile

`Makefile` jest prostym interfejsem do operacji developerskich.

Docelowo:

```text
make up
make stop
make restart
make logs
make ps
make shell
make wp
make install
make seed
make destroy
```

Komenda nie powinna być przedstawiana w dokumentacji jako dostępna, dopóki nie została zaimplementowana.

## 15. Git

Główna gałąź:

```text
main
```

Preferujemy małe, logiczne commity, np.:

```text
feat:
fix:
refactor:
docs:
test:
chore:
```

Nie commitujemy sekretów, lokalnego `.env`, wygenerowanych uploadów ani lokalnej bazy danych.

## 16. Dokumentacja i prostota

Dokumentacja jest częścią projektu i znajduje się w:

```text
docs/
```

Ten plik jest nadrzędnym źródłem informacji o zaakceptowanych decyzjach architektonicznych.

Nie implementujemy funkcjonalności wyłącznie "na przyszłość". Preferujemy najprostsze rozwiązanie spełniające aktualne wymagania i pozostawiające rozsądną możliwość dalszego rozwoju.

## Status

Decyzje opisane w tym dokumencie są zaakceptowane i obowiązują podczas dalszego rozwoju projektu.
