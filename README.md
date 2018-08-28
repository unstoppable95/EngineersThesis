# EngineersThesis
  
XAMPP  
a)	Instalacja  
Ze strony https://www.apachefriends.org/pl/download.html należy pobrać serwer XAMPP   
b)	Uruchomienie  
Kiedy serwer jest już zainstalowany, należy go otworzyć i uruchomić moduł Apache i MySQL (podświetlą się wtedy na zielono). Aby sprawdzić czy serwer został poprawnie uruchomiony i działa należy w przeglądarce wejść pod adres http://localhost/  
  
MySQL  
2.	Utworzenie bazy danych  
a)	Utworzenie nowej bazy danych  
Aby utworzyć bazę danych, kiedy serwer jest już uruchomiony należy wejść na stronę http://localhost/phpmyadmin/ i po lewej stronie zaznaczyć dodanie nowej bazy danych.  
Następnie podajemy nazwę bazy danych  school_schema , ustawiamy kodowanie na utf8_polish_ci i klikamy Utwórz.  
b)	Import bazy danych  
Po utworzeniu bazy danych wyświetli  się nowostworzona baza danych i informacja o tym, że nie znaleziono w niej żadnych tabel. Aby dodać tabele należy je importować z pliku school_schema.sql znajdującego się w katalogu Database.  

MAIL:
3.	Konfiguracja serwera do wysyłania maili
Aby aplikacja mogła wysyłać maile z hasłem pierwszego logowania dla skarbników i rodziców a także maili z informacjami o dodaniu nowego wydarzenia do wszystkich rodziców, których dzieci dotyczy to wydarzenie, konieczna jest dodatkowa konfiguracja serwera.
Pierwszą rzeczą jest znalezienie w folderze xampp (powinien być zapisany w c:\xampp) pliku: xampp\php\php.ini, a w nim linijki extension=php_openssl.dll. Jeżeli przed nią znajduje się średnik „;” – czyli komentarz – należy go usunąć po to, by umożliwić działanie SSL. Może okazać się, że ta linia kodu już będzie odkomentowana.
Następnym krokiem jest w tym samym pliku znalezienie znacznik [mail function] i jego zawartość zamienić na: 
SMTP=smtp.gmail.com
	smtp_port=587
sendmail_from=systemskarbnikklasowy@gmail.com
	sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"   

(oczywiście wtedy, kiedy ścieżką, pod którą zainstalowano XAMPP’a jest C:\xampp, w przeciwnym wypadku ścieżkę dostępu w ostatniej linii należy zmienić) 

Mail systemskarbnikklasowy@gmail.com jest specjalnym mailem założonym na potrzeby działania systemu. Hasło do tego miala to „skarbnik321”. W przypadku chęci korzystania z innego maila, administrator może go zmienić.
Kolejną czynnością jest zamiana całej zawartości pliku C:\xampp\sendmail\sendmail.ini na poniższą:
[sendmail]

smtp_server=smtp.gmail.com
smtp_port=587
error_logfile=error.log
debug_logfile=debug.log
auth_username=systemskarbnikklasowy@gmail.com
auth_password=skarbnik321
force_sender=systemskarbnikklasowy@gmail.com

Oczywiście w przypadku chęci zmiany maila na inny należy odpowiednio zmodyfikować ostatnie 3 linie powyższego kodu.
Ostatnim krokiem jest zresetowanie serwera używając pakietu XAMPP – czyli innymi słowy ponowne uruchomienie XAMPP’a.
