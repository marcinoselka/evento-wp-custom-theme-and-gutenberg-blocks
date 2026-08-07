# Development

## Wymagania

Do lokalnego uruchomienia projektu wymagane są:

* Docker,
* Docker Compose,
* GNU Make,
* Git.

## Przygotowanie środowiska

Po sklonowaniu repozytorium utwórz lokalny plik konfiguracyjny:

```bash
cp .env.example .env
```

Plik `.env` nie jest wersjonowany.

## Zasady pracy

Zmiany powinny być:

* niewielkie,
* logicznie powiązane,
* możliwe do niezależnego przetestowania.

Po każdym etapie projekt powinien pozostawać w działającym stanie.

## Git

Główną gałęzią projektu jest:

```text
main
```

Preferowana konwencja commitów:

```text
feat: add event custom post type
fix: correct event date validation
docs: update development instructions
refactor: simplify event query
test: add event block tests
chore: update development environment
```

## Dokumentacja

Zmiana wpływająca na architekturę powinna zostać odzwierciedlona w:

```text
docs/architecture-decision-record.md
```

Zmiana wpływająca na aktualną strukturę techniczną powinna zostać odzwierciedlona w:

```text
docs/architecture.md
```

Dokumentacja powinna zawsze odpowiadać rzeczywistemu stanowi implementacji.
