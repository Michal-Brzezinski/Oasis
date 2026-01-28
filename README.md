# 🌿 Oasis Dashboard  
Nowoczesny panel do zarządzania inteligentnym systemem nawadniania

![Screenshot aplikacji](./docs/screenshot.png)
<!-- Wstaw tu obrazek swojej aplikacji -->

---

## 📌 Opis projektu

**Oasis Dashboard** to webowa aplikacja stworzona w PHP, która umożliwia właścicielom ogrodów i administratorom systemu zarządzać:

- czujnikami wilgotności,
- harmonogramami podlewania,
- kamerami podglądu,
- regionami ogrodu,
- ręcznym i automatycznym podlewaniem,
- powiadomieniami o niskiej wilgotności,
- ustawieniami użytkownika.

Aplikacja posiada również **panel administratora**, który pozwala zarządzać użytkownikami systemu.

Całość działa w architekturze MVC, z wykorzystaniem repozytoriów, kontrolerów i widoków.  
Interfejs jest w pełni responsywny i zoptymalizowany pod urządzenia mobilne.

---

## 🚀 Funkcje

### 👤 Logowanie i role użytkowników
- Logowanie z weryfikacją hasła (`password_verify`)
- Role:
  - **OWNER** – standardowy użytkownik
  - **ADMIN** – dostęp do panelu zarządzania użytkownikami
- Sesje przechowują dane użytkownika i jego rolę

### 📊 Dashboard użytkownika
- Podgląd regionów i ich wilgotności
- Powiadomienia o niskiej wilgotności
- Podgląd kamer (snapshoty generowane automatycznie)
- Harmonogramy podlewania
- Ręczne sterowanie podlewaniem

### 🛠 Panel administratora
- Lista wszystkich użytkowników
- Usuwanie użytkowników
- Dostęp tylko dla roli `ADMIN`

### 🔔 System powiadomień
- Automatyczne powiadomienia o niskiej wilgotności
- Oznaczanie powiadomień jako przeczytane
- Licznik nieprzeczytanych powiadomień w topbarze

### 🌱 Symulacja
- Symulacja czujników wilgotności
- Symulacja harmonogramów (CRON)
- Symulacja snapshotów kamer

---

## 🧱 Struktura projektu


---

## ⚙️ Wymagania

- PHP 8.1+
- PostgreSQL
- Apache / Nginx
- Composer (opcjonalnie)

---

## ▶️ Uruchomienie

1. Sklonuj repozytorium  
2. Skonfiguruj bazę danych  
3. Ustaw połączenie w `Database.php`  
4. Uruchom serwer lokalny:


---

## 📄 Licencja

Projekt prywatny / edukacyjny.

---

## ✨ Autor

Michał – pasjonat czystej architektury, wzorców projektowych i skalowalnych systemów.
