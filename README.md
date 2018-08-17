# EngineersThesis
XAMPP
a)	Instalacja
Ze strony https://www.apachefriends.org/pl/download.html należy pobrać serwer XAMPP 
b)	uruchomienie
Kiedy serwer jest już zainstalowany, należy go otworzyć i uruchomić moduł Apache i MySQL (podświetlą się wtedy na zielono). Aby sprawdzić czy serwer został poprawnie uruchomiony i działa należy w przeglądarce wejść pod adres http://localhost/
2.	Utworzenie bazy danych
a)	Utworzenie nowej bazy danych
Aby utworzyć bazę danych, kiedy serwer jest już uruchomiony należy wejść na stronę http://localhost/phpmyadmin/ i po lewej stronie zaznaczyć dodanie nowej bazy danych.
Następnie podajemy nazwę bazy danych  school_schema , ustawiamy kodowanie na utf8_polish_ci i klikamy Utwórz.
b)	Import bazy danych
Po utworzeniu bazy danych wyświetli  się nowostworzona baza danych i informacja o tym, że nie znaleziono w niej żadnych tabel. Aby dodać tabele należy je importować z pliku school_schema.sql znajdującego się w katalogu Database.
