# Roadmap EVENTO

Roadmapa przedstawia plan rozwoju oraz aktualny status projektu.

* `[x]` - wykonane
* `[ ]` - do wykonania

## Etap 1 - środowisko developerskie

* [x] Utworzenie struktury projektu
* [x] Inicjalizacja repozytorium Git
* [x] Przygotowanie `.env.example`
* [x] Konfiguracja `.gitignore`
* [x] Przygotowanie podstawowej konfiguracji Docker Compose
* [x] Finalizacja Docker Compose
* [x] Dodanie WP-CLI
* [x] Finalizacja Makefile
* [x] Implementacja `make install`
* [x] Weryfikacja instalacji od zera

## Etap 2 - podstawy motywu

* [ ] Utworzenie motywu EVENTO
* [ ] Dodanie `style.css`
* [ ] Dodanie `functions.php`
* [ ] Dodanie `theme.json`
* [ ] Konfiguracja struktury motywu
* [ ] Aktywacja motywu przez WP-CLI
* [ ] Konfiguracja procesu budowania assetów

## Etap 3 - model danych

* [ ] Zaprojektowanie modelu wydarzenia
* [ ] Custom Post Type wydarzeń
* [ ] Model miejsc
* [ ] Model organizatorów
* [ ] Kategorie wydarzeń
* [ ] Taksonomie
* [ ] Pola danych wydarzenia
* [ ] Integracja z REST API

## Etap 4 - podstawowy interfejs

* [ ] Header
* [ ] Nawigacja
* [ ] Footer
* [ ] Strona główna
* [ ] Lista wydarzeń
* [ ] Widok pojedynczego wydarzenia
* [ ] Widok kategorii
* [ ] Widok miejsca

## Etap 5 - Gutenberg

* [ ] Konfiguracja środowiska dla custom blocks
* [ ] Pierwszy własny blok Gutenberg
* [ ] Blok wyróżnionych wydarzeń
* [ ] Blok nadchodzących wydarzeń
* [ ] Blok kategorii
* [ ] Blok miejsc
* [ ] Block Patterns
* [ ] Dopracowanie doświadczenia edytora

## Etap 6 - wyszukiwanie i filtrowanie

* [ ] Wyszukiwarka wydarzeń
* [ ] Filtrowanie po kategorii
* [ ] Filtrowanie po dacie
* [ ] Filtrowanie po miejscu
* [ ] Obsługa braku wyników
* [ ] Przyjazne adresy URL

## Etap 7 - dane demonstracyjne

* [ ] Przygotowanie `demo/data/`
* [ ] Przygotowanie `demo/images/`
* [ ] Implementacja `make seed`
* [ ] Generowanie wydarzeń
* [ ] Generowanie miejsc
* [ ] Generowanie organizatorów
* [ ] Import obrazów do Media Library
* [ ] Przypisywanie Featured Images
* [ ] Generowanie menu i stron
* [ ] Weryfikacja pełnego odtworzenia projektu

## Etap 8 - jakość

* [ ] Responsywność
* [ ] Accessibility
* [ ] Technical SEO
* [ ] Core Web Vitals
* [ ] Optymalizacja obrazów
* [ ] Optymalizacja zapytań
* [ ] Walidacja PHP
* [ ] Linting JavaScript
* [ ] Testy

## Etap 9 - dokumentacja i wydanie

* [ ] Finalizacja README
* [ ] Aktualizacja dokumentacji technicznej
* [ ] Instrukcja instalacji
* [ ] Instrukcja developmentu
* [ ] Dokumentacja custom Gutenberg Blocks
* [ ] Screenshoty
* [ ] Test instalacji z czystego `git clone`
* [ ] Finalny przegląd repozytorium
