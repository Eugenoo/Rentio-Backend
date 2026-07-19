# Rentio Backend

Backend aplikacji **Rentio** służącej do zarządzania wypożyczalnią **pojazdów**. Projekt został zbudowany z wykorzystaniem **Laravel** i udostępnia REST API wykorzystywane przez aplikację frontendową.

---

## Technologie

- Laravel
- PHP 8.x
- MySQL
- Laravel Sanctum (autoryzacja)
- Eloquent ORM
- REST API

---

## Wymagania

Przed uruchomieniem projektu upewnij się, że posiadasz:

- PHP 8.2 lub nowszy
- Composer
- MySQL / MariaDB
- Node.js (opcjonalnie – do kompilacji assetów)

Sprawdzenie wersji:

```bash
php -v
composer -V
```

---

## Instalacja

Sklonuj repozytorium:

```bash
git clone <adres_repozytorium>
```

Przejdź do katalogu projektu:

```bash
cd Rentio-Backend
```

Zainstaluj zależności:

```bash
composer install
```

Skopiuj plik konfiguracyjny:

```bash
cp .env.example .env
```

Wygeneruj klucz aplikacji:

```bash
php artisan key:generate
```

Skonfiguruj połączenie z bazą danych w pliku `.env`.

Uruchom migracje:

```bash
php artisan migrate
```

Jeżeli projekt zawiera seedy:

```bash
php artisan db:seed
```

---

## Uruchomienie aplikacji

Uruchom serwer developerski:

```bash
php artisan serve
```

Domyślnie aplikacja będzie dostępna pod adresem:

```
http://127.0.0.1:8000
```

---

## Dostępne komendy

| Komenda | Opis |
|----------|------|
| `php artisan serve` | Uruchamia serwer developerski |
| `php artisan migrate` | Wykonuje migracje bazy danych |
| `php artisan migrate:fresh --seed` | Odtwarza bazę danych i uruchamia seedy |
| `php artisan db:seed` | Wypełnia bazę przykładowymi danymi |
| `php artisan route:list` | Wyświetla listę endpointów API |
| `php artisan optimize:clear` | Czyści cache aplikacji |

---

## Struktura projektu

```
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
├── Helpers/

database/
├── migrations/
├── seeders/
└── factories/

routes/
├── api.php
└── web.php

config/
public/
storage/
```

---

## Konfiguracja środowiska

Przykładowa konfiguracja pliku `.env`:

```env
APP_NAME=Rentio
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rentio
DB_USERNAME=root
DB_PASSWORD=
```

---

## Główne funkcjonalności

Backend udostępnia REST API umożliwiające:

- rejestrację i logowanie użytkowników,
- autoryzację z wykorzystaniem tokenów,
- zarządzanie pojazdami,
- zarządzanie kategoriami pojazdów,
- obsługę rezerwacji,
- zarządzanie płatnościami,
- dodawanie opinii o pojazdach,
- zarządzanie użytkownikami,
- panel administracyjny,
- walidację danych wejściowych.

---

## Architektura

Projekt został zrealizowany zgodnie z architekturą MVC (Model–View–Controller).

- **Controllers** – obsługa żądań HTTP i logika aplikacji,
- **Models** – komunikacja z bazą danych przy użyciu Eloquent ORM,
- **Requests** – walidacja danych,
- **Middleware** – autoryzacja i kontrola dostępu,
- **Routes** – definicja endpointów API.

---

## Integracja z frontendem

Backend komunikuje się z aplikacją frontendową poprzez REST API, zwracając odpowiedzi w formacie JSON.

---

## Autor

Projekt został przygotowany jako backend aplikacji **Rentio** do zarządzania wypożyczalnią pojazdów.

---

## Licencja

Projekt przeznaczony do celów edukacyjnych lub rozwoju własnego. W razie potrzeby można dodać odpowiednią licencję (np. MIT).
