-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 14 Sty 2019, 19:21
-- Wersja serwera: 10.1.36-MariaDB
-- Wersja PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `school_schema`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `balance` decimal(11,2) NOT NULL,
  `cash` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `account`
--

INSERT INTO `account` (`id`, `child_id`, `balance`, `cash`) VALUES
(1, 1, '0.00', '0.00'),
(2, 2, '0.00', '0.00'),
(3, 3, '0.00', '0.00'),
(4, 4, '0.00', '0.00'),
(5, 5, '0.00', '0.00'),
(6, 6, '0.00', '0.00'),
(7, 7, '0.00', '0.00'),
(8, 8, '0.00', '0.00'),
(9, 9, '0.00', '0.00'),
(14, 14, '10.00', '0.00'),
(15, 15, '0.00', '0.00'),
(16, 16, '-14.00', '15.00'),
(17, 17, '25.00', '0.00'),
(18, 18, '0.00', '25.00'),
(19, 19, '7.00', '0.00'),
(20, 20, '0.00', '0.00'),
(21, 21, '0.00', '0.00'),
(22, 22, '0.00', '0.00'),
(23, 23, '0.00', '0.00'),
(24, 24, '0.00', '0.00'),
(25, 25, '0.00', '0.00'),
(26, 26, '5.00', '0.00'),
(27, 27, '0.00', '0.00'),
(28, 28, '14.00', '0.00'),
(29, 29, '0.00', '0.00'),
(30, 30, '5.00', '0.00'),
(31, 31, '0.00', '0.00'),
(32, 32, '0.00', '0.00'),
(33, 33, '0.00', '0.00'),
(34, 34, '0.00', '0.00'),
(35, 35, '0.00', '0.00'),
(36, 36, '0.00', '0.00'),
(37, 37, '0.00', '0.00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `child`
--

CREATE TABLE `child` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `surname` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `child`
--

INSERT INTO `child` (`id`, `name`, `surname`, `date_of_birth`, `parent_id`, `class_id`) VALUES
(1, 'Tomek', 'Żurczak', '1999-12-05', 1, NULL),
(2, 'Basia', 'Żurczak', '1999-12-05', 1, NULL),
(3, 'Aleksandra', 'Olejniczak', '1999-02-20', 2, NULL),
(4, 'Piotr Jr.', 'Pawlaczyk', '1999-07-31', 3, NULL),
(5, 'Tomek', 'Pawlaczyk', '2000-02-01', 4, NULL),
(6, 'Joanna', 'Glapiak', '2000-03-04', 5, NULL),
(7, 'Bartosz', 'Tomczak', '2000-08-05', 6, NULL),
(8, 'Katarzyna', 'Kluczyk', '2000-09-05', 7, NULL),
(9, 'Krystian', 'Chudy', '2000-08-12', 8, NULL),
(14, 'Kacper', 'Żurczak', '5200-05-05', 1, 9),
(15, 'Marek', 'Pocieszny', '2006-07-30', 13, 11),
(16, 'Ula', 'Kieroń', '2006-03-26', 14, 11),
(17, 'Jędrzej', 'Olejnik', '2006-11-30', 15, 11),
(18, 'Lidka', 'Kaczmarek', '2006-06-01', 16, 11),
(19, 'Krzysztof', 'Hazard', '2006-09-14', 17, 11),
(20, 'Wanesa', 'Wagon', '2006-10-10', 18, 11),
(21, 'Faustyna', 'Kajoch', '2006-08-29', 19, 11),
(22, 'Klaudia', 'Kozica', '2006-05-23', 20, 11),
(23, 'Kacper', 'Rzepecki', '2006-12-04', 21, 11),
(24, 'Edward', 'Lisowski', '2006-08-03', 22, 11),
(25, 'Kinga', 'Gwarek', '2006-10-29', 23, 11),
(26, 'Maksymilian', 'Grzywa', '2006-01-05', 24, 11),
(27, 'Maciej', 'Malepszy', '2006-10-08', 25, 11),
(28, 'Jordan', 'Restow', '2006-06-25', 26, 11),
(29, 'Martyna', 'Krychowiak', '2006-04-23', 27, 11),
(30, 'Mikołaj', 'Owies', '2006-05-09', 28, 11),
(31, 'Mieszko', 'Mazur', '2006-12-14', 29, 11),
(32, 'Klara', 'Iwaszczuk', '2006-09-15', 30, 11),
(33, 'Weronika', 'Dembska', '2006-12-29', 12, 11),
(34, 'Filip', 'Wirgiliusz', '2008-12-12', 31, 12),
(35, 'Urszula', 'Walenciak', '2008-10-15', 32, 12),
(36, 'Alfred', 'Nowicki', '2008-04-18', 33, 12),
(37, 'Aleksandra', 'Konieczna', '2008-03-05', 34, 12);

--
-- Wyzwalacze `child`
--
DELIMITER $$
CREATE TRIGGER `addChildAccountChildParticipation` AFTER INSERT ON `child` FOR EACH ROW begin
DECLARE v_count, v_loop_counter, v_id ,v_child_count INTEGER;
DECLARE v_date,curDate DATE;
DECLARE v_monthly_fee INTEGER;
DECLARE classEvents cursor for select id,date from event where class_id=NEW.class_id;

insert into account (child_id,balance ) values (NEW.id,0);
select monthly_fee into v_monthly_fee from class_account where class_id=NEW.class_id;
select count(*) into v_child_count from child where class_id = NEW.class_id;

update class_account set expected_budget=expected_budget+10*v_monthly_fee where class_id=NEW.class_id;


SET v_loop_counter=0;
select count(*) into v_count from event where class_id=NEW.class_id;
Select CURDATE() into curDate;
if v_count>0 THEN
	open classEvents;
    
 myLOOP: LOOP    
    SET v_loop_counter =v_loop_counter+1; 
    fetch classEvents into v_id,v_date;
	
    if(v_date>curDate) then
	insert into participation (event_id,child_id,amount_paid) values (v_id,NEW.id,0);
	end if;
    
   IF v_loop_counter=v_count THEN
       leave myLOOP;
   END IF; 
 end loop myLOOP;  
    close classEvents;
    
end if;


end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delAccountChildChangeClassAccount` BEFORE DELETE ON `child` FOR EACH ROW begin
DECLARE differenceInMonths INTEGER;
DECLARE currentMonth  INTEGER;
DECLARE sumPayment INTEGER;
DECLARE charge,monthlyFee INTEGER;
DECLARE classaccountID INTEGER;
DECLARE school_year INTEGER;

select MONTH(CURDATE()) into currentMonth from dual;

select sum(IFNULL(amount,0)) into sumPayment from class_account_payment where child_id=OLD.id;

select monthly_fee into monthlyFee from class_account where class_id =OLD.class_id;

select max(id) into school_year from school_year;
select id into classaccountID from class_account where class_id =OLD.class_id;

if currentMonth >=1 and currentMonth <=6 THEN
set currentMonth=currentMonth+12;
end if;
set differenceInMonths = currentMonth -8;
set charge= differenceInMonths*monthlyFee;

delete from class_account_payment where child_id=OLD.id;

update class_account set expected_budget=expected_budget-10*monthlyFee+charge where class_id=classaccountID;

insert into class_account_payment (amount,type,class_account_id,school_year_id) values (charge,"auto",classaccountID,school_year);


delete from account where child_id=OLD.id;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `class`
--

CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `bank_account_number` varchar(26) COLLATE utf8_polish_ci NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `next_class` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `class`
--

INSERT INTO `class` (`id`, `name`, `parent_id`, `bank_account_number`, `school_year_id`, `next_class`) VALUES
(9, '1a', 1, '', 1, NULL),
(11, '2B', 12, '32105001163679369545139779', 1, NULL),
(12, '3C', 31, '', 1, NULL);

--
-- Wyzwalacze `class`
--
DELIMITER $$
CREATE TRIGGER `addClassAccount` AFTER INSERT ON `class` FOR EACH ROW INSERT into class_account (balance,expenses, 	expected_budget,class_id) values(0,0,0,NEW.id)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `class_before_insert` BEFORE INSERT ON `class` FOR EACH ROW BEGIN
DECLARE v_actual_year int;

SELECT max(id) INTO v_actual_year FROM school_year;

set NEW.school_year_id := v_actual_year;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delClassAccount` AFTER DELETE ON `class` FOR EACH ROW delete from class_account where class_id=OLD.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `class_account`
--

CREATE TABLE `class_account` (
  `id` int(11) NOT NULL,
  `balance` decimal(11,2) DEFAULT NULL,
  `cash` decimal(11,2) NOT NULL,
  `monthly_fee` decimal(11,2) NOT NULL DEFAULT '4.00',
  `expenses` int(11) DEFAULT NULL,
  `expected_budget` int(11) DEFAULT '0',
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `class_account`
--

INSERT INTO `class_account` (`id`, `balance`, `cash`, `monthly_fee`, `expenses`, `expected_budget`, `class_id`) VALUES
(9, '11.00', '25.00', '4.00', 0, 104, 9),
(11, '60.00', '10.00', '4.00', 0, 760, 11),
(12, '0.00', '0.00', '4.00', 0, 160, 12);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `class_account_payment`
--

CREATE TABLE `class_account_payment` (
  `id` int(11) NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `class_account_id` int(11) NOT NULL,
  `child_id` int(11) DEFAULT NULL,
  `school_year_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `class_account_payment`
--

INSERT INTO `class_account_payment` (`id`, `amount`, `date`, `type`, `class_account_id`, `child_id`, `school_year_id`) VALUES
(2, '16.00', '2018-12-27 13:43:38', 'auto', 9, NULL, 1),
(3, '16.00', '2018-12-27 13:43:52', 'auto', 9, NULL, 1),
(4, '16.00', '2018-12-27 13:47:43', 'auto', 9, NULL, 1),
(6, '16.00', '2018-12-27 13:52:16', 'auto', 9, NULL, 1),
(7, '12.00', '2018-12-27 19:04:49', 'gotowka', 9, 14, 1),
(8, '20.00', '2019-01-06 21:05:37', 'gotowka', 11, 33, 1),
(9, '10.00', '2019-01-06 21:50:15', 'konto', 11, 26, 1),
(10, '14.00', '2019-01-06 21:50:27', 'gotowka', 11, 18, 1),
(11, '5.00', '2019-01-06 21:50:44', 'gotowka', 11, 27, 1),
(12, '10.00', '2019-01-06 21:50:48', 'konto', 11, 15, 1),
(13, '40.00', '2019-01-06 21:54:38', 'gotowka', 11, 18, 1),
(14, '40.00', '2019-01-06 21:54:42', 'gotowka', 11, 23, 1),
(15, '40.00', '2019-01-06 21:54:48', 'gotowka', 11, 17, 1),
(16, '40.00', '2019-01-06 21:54:54', 'gotowka', 11, 15, 1),
(17, '16.00', '2019-01-06 21:55:09', 'gotowka', 11, 29, 1),
(18, '40.00', '2019-01-06 21:55:34', 'gotowka', 11, 30, 1),
(19, '15.00', '2019-01-07 08:43:30', 'gotowka', 11, 18, 1),
(20, '25.00', '2019-01-07 08:43:38', 'gotowka', 11, 16, 1),
(21, '15.00', '2019-01-07 08:43:47', 'konto', 11, 17, 1);

--
-- Wyzwalacze `class_account_payment`
--
DELIMITER $$
CREATE TRIGGER `before_insert_class_account_payment` BEFORE INSERT ON `class_account_payment` FOR EACH ROW BEGIN DECLARE v_actual_year int; SELECT max(id) INTO v_actual_year FROM school_year; set NEW.school_year_id := v_actual_year; END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `price` decimal(11,2) NOT NULL,
  `date` date NOT NULL,
  `class_id` int(11) NOT NULL,
  `completed` tinyint(1) NOT NULL,
  `school_year_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `event`
--

INSERT INTO `event` (`id`, `name`, `price`, `date`, `class_id`, `completed`, `school_year_id`) VALUES
(1, 'Kulig', '20.00', '2018-12-29', 9, 0, 1),
(2, 'Kino', '25.00', '2019-01-19', 11, 0, 1),
(3, 'Kwiaty', '10.00', '2019-03-16', 11, 0, 1),
(4, 'Teatr', '45.00', '2019-03-18', 11, 0, 1),
(5, 'Festyn na dzień dziecka', '25.00', '2019-06-01', 11, 0, 1),
(6, 'Pizza', '15.00', '2019-01-25', 11, 0, 1),
(7, 'Wyjście do teatru', '35.00', '2019-02-09', 11, 0, 1);

--
-- Wyzwalacze `event`
--
DELIMITER $$
CREATE TRIGGER `addBalanceUpdateEvent` AFTER UPDATE ON `event` FOR EACH ROW begin 
DECLARE v_childID,v_amountPaid,v_balance, v_count,v_loop_counter INTEGER;
DECLARE childs_details cursor for select p.child_id,p.amount_paid,a.balance  from participation p join account a on p.child_id=a.child_id where p.event_id=NEW.id;

Select count(*) into v_count from participation where event_id=NEW.id;

SET v_loop_counter=0;

if v_count >0 THEN
open childs_details;

 myLOOP: LOOP
 SET v_loop_counter =v_loop_counter+1;

 fetch childs_details into v_childID,v_amountPaid,v_balance;
 
 if (NEW.price<OLD.price ) then
	
	if (OLD.price=v_amountPaid) then
	update participation set amount_paid=NEW.price where child_id=v_childID and event_id=NEW.id;
	update account set balance=balance+(OLD.price-NEW.price) where child_id=v_childID;
	end if;
	
	if (OLD.price>v_amountPaid) then
		
		if(NEW.price<v_amountPaid) then
		update participation set amount_paid=NEW.price where child_id=v_childID and event_id=NEW.id;
		update account set balance=balance+(v_amountPaid-NEW.price) where child_id=v_childID;
		end if;
				
		
	end if;
	
	
 end if;
 
 
 
IF v_loop_counter=v_count THEN
       leave myLOOP;
   END IF; 
   
 end loop myLOOP;


end if;
close childs_details;


end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_event` BEFORE INSERT ON `event` FOR EACH ROW BEGIN DECLARE v_actual_year int; SELECT max(id) INTO v_actual_year FROM school_year; set NEW.school_year_id := v_actual_year; END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delParticipationAfterDelEvent` BEFORE DELETE ON `event` FOR EACH ROW begin 
 DECLARE curDate DATE; 
 DECLARE eventDate DATE;
DECLARE v_child_id, v_event_id , v_amount_paid INTEGER;
 DECLARE v_count INTEGER;
 DECLARE v_loop_counter INTEGER;
declare my_cursor cursor for select * from participation where event_id=OLD.id;
 

Select CURDATE() into curDate;
Select old.date into eventDate;
Select count(*) into v_count from participation where event_id=OLD.id;
SET v_loop_counter =0;
 
 if v_count >0 then
 open my_cursor;
 
 
 myLOOP: LOOP
 fetch my_cursor into  v_event_id, v_child_id, v_amount_paid;
  SET v_loop_counter =v_loop_counter+1;
  IF(select datediff(eventDate,curDate) >=0) THEN
update account set balance=balance+v_amount_paid where 	child_id=v_child_id; 
END IF;
 
  delete from participation where event_id=v_event_id and child_id =v_child_id;
  
  
  
   IF v_loop_counter=v_count THEN
      
        leave myLOOP;
   END IF;
  

 end loop myLOOP;

 close my_cursor;
 end if;
 
 end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expense`
--

CREATE TABLE `expense` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `price` decimal(11,2) NOT NULL,
  `date` date NOT NULL,
  `class_account_id` int(11) NOT NULL,
  `type` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `school_year_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `expense`
--

INSERT INTO `expense` (`id`, `name`, `price`, `date`, `class_account_id`, `type`, `school_year_id`) VALUES
(1, 'Papier ksero', '30.00', '2019-01-06', 11, 'gotowka', 1),
(2, 'Piłka klasowa', '80.00', '2019-01-06', 11, 'gotowka', 1),
(3, 'Pizza na dzień dziecka', '120.00', '2019-01-06', 11, 'konto', 1),
(4, 'Herbata', '20.00', '2019-01-11', 11, 'gotowka', 1),
(5, 'Zabawki', '10.00', '2019-01-11', 11, 'gotowka', 1);

--
-- Wyzwalacze `expense`
--
DELIMITER $$
CREATE TRIGGER `addExpenseSetDate` BEFORE INSERT ON `expense` FOR EACH ROW BEGIN 
DECLARE v_actual_year int; 
SELECT max(id) INTO v_actual_year FROM school_year; 
set NEW.school_year_id := v_actual_year;
set NEW.date=NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `parent`
--

CREATE TABLE `parent` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `surname` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `type` varchar(1) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `parent`
--

INSERT INTO `parent` (`id`, `name`, `surname`, `email`, `type`) VALUES
(1, 'Anna', 'Żurczak', 'annazurczak1@gmail.com', 't'),
(2, 'Robert', 'Olejniczak', 'robert23891@wp.pl', 't'),
(3, 'Piotr', 'Pawlaczyk', 'pawlaykpiotrxd@onet.pl', 'p'),
(4, 'Piotr', 'Pawlaczyk', 'piotrpawlaczyk21@gmail.com', 'p'),
(5, 'Patryk', 'Glapiak', 'nieznany012@wp.pl', 'p'),
(6, 'Grazyna', 'Tomczak', 'gzurczak@wp.pl', 'p'),
(7, 'Witold', 'Kluczyk', 'wkhdhd@wp.pl', 'p'),
(8, 'Patrcja', 'Chuda', 'pati3285485@onet.pl', 'p'),
(9, 'Waldemar', 'Nowacki', 'waldeknowackihehe@gmail.com', 'p'),
(10, 'Joanna', 'Kluczyk', 'kluczykmamamamamma@gmail.com', 'p'),
(11, 'Janusz', 'Kowalski', 'januszkowalski@gmail.com', 't'),
(12, 'Filip', 'Dembski', 'fdembski@gmail.com', 't'),
(13, 'Grażyna', 'Pocieszna', 'gpocieszna@gmail.com', 'p'),
(14, 'Ambroży', 'Kieroń', 'akieron@gmail.com', 'p'),
(15, 'Bożena', 'Olejnik', 'olejnikbozena@gmail.com', 'p'),
(16, 'Wiktoria', 'Kaczmarek', 'wiktoriakaczmarek@gmail.com', 'p'),
(17, 'Rafał', 'Hazard', 'rafalhazard1977@gmail.com', 'p'),
(18, 'Karolina', 'Wagon', 'wagonkarolina181@gmail.com', 'p'),
(19, 'Mikołaj', 'Kajoch', 'mkajoch55@gmail.com', 'p'),
(20, 'Iga', 'Kozica', 'ikozica@wp.pl', 'p'),
(21, 'Mirosław', 'Rzepecki', 'mirekrzepeckiyy@gmail.com', 'p'),
(22, 'Kalina', 'Lisowka', 'kalinalisowska678@gmail.com', 'p'),
(23, 'Klarysa', 'Gwarek', 'kgwarek123@gmail.com', 'p'),
(24, 'Maja', 'Grzywa', 'grzywam@gmail.com', 'p'),
(25, 'Jerzy', 'Malepszy', 'jmalepszy@gmail.com', 'p'),
(26, 'Karol', 'Restow', 'restowkarol@gmail.com', 'p'),
(27, 'Tadeusz', 'Krychowiak', 'tkrycha@gmail.com', 'p'),
(28, 'Adam', 'Owies', 'adamowies@gmail.com', 'p'),
(29, 'Ludwik', 'Mazur', 'ludwikmazur@gmail.com', 'p'),
(30, 'Klaudyna', 'Iwaszczuk', 'kiwaszczuk@gmail.com', 'p'),
(31, 'Antoni', 'Wirgiliusz', 'aw@gmail.com', 't'),
(32, 'Krzysztof', 'Walenciak', 'kwalenciak@gmail.com', 'p'),
(33, 'Zygfryd', 'Nowicki', 'zygrfrydnowicki@wp.pl', 'p'),
(34, 'Jolanta', 'Konieczna', 'koniecznajolanta@gmail.com', 'p'),
(35, 'Jan', 'Kowalski', 'jkowalski@onet.pl', 't');

--
-- Wyzwalacze `parent`
--
DELIMITER $$
CREATE TRIGGER `delParentUsername` AFTER DELETE ON `parent` FOR EACH ROW delete from username where login=OLD.email
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updateTypeParentUsername` AFTER UPDATE ON `parent` FOR EACH ROW UPDATE username set type=NEW.type where login=NEW.email
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_login_in_username` BEFORE UPDATE ON `parent` FOR EACH ROW UPDATE username set login=NEW.email where parent_id=NEW.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `participation`
--

CREATE TABLE `participation` (
  `event_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `amount_paid` decimal(11,2) NOT NULL,
  `cash` decimal(11,2) NOT NULL,
  `balance` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `participation`
--

INSERT INTO `participation` (`event_id`, `child_id`, `amount_paid`, `cash`, `balance`) VALUES
(1, 14, '20.00', '5.00', '15.00'),
(2, 16, '25.00', '0.00', '25.00'),
(2, 17, '0.00', '0.00', '0.00'),
(2, 18, '0.00', '0.00', '0.00'),
(2, 19, '25.00', '0.00', '25.00'),
(2, 20, '25.00', '0.00', '25.00'),
(2, 21, '0.00', '0.00', '0.00'),
(2, 22, '25.00', '0.00', '25.00'),
(2, 23, '25.00', '0.00', '25.00'),
(2, 24, '25.00', '0.00', '25.00'),
(2, 25, '0.00', '0.00', '0.00'),
(2, 26, '25.00', '0.00', '25.00'),
(2, 27, '0.00', '0.00', '0.00'),
(2, 28, '0.00', '0.00', '0.00'),
(2, 29, '0.00', '0.00', '0.00'),
(2, 30, '25.00', '0.00', '25.00'),
(2, 31, '0.00', '0.00', '0.00'),
(2, 32, '0.00', '0.00', '0.00'),
(3, 16, '0.00', '0.00', '0.00'),
(3, 17, '0.00', '0.00', '0.00'),
(3, 18, '0.00', '0.00', '0.00'),
(3, 19, '10.00', '0.00', '10.00'),
(3, 20, '0.00', '0.00', '0.00'),
(3, 21, '0.00', '0.00', '0.00'),
(3, 22, '0.00', '0.00', '0.00'),
(3, 23, '0.00', '0.00', '0.00'),
(3, 24, '0.00', '0.00', '0.00'),
(3, 25, '0.00', '0.00', '0.00'),
(3, 26, '10.00', '0.00', '10.00'),
(3, 27, '0.00', '0.00', '0.00'),
(3, 28, '0.00', '0.00', '0.00'),
(3, 29, '0.00', '0.00', '0.00'),
(3, 30, '0.00', '0.00', '0.00'),
(3, 31, '0.00', '0.00', '0.00'),
(3, 32, '0.00', '0.00', '0.00'),
(4, 17, '0.00', '0.00', '0.00'),
(4, 18, '5.00', '0.00', '5.00'),
(4, 19, '33.00', '0.00', '33.00'),
(4, 20, '0.00', '0.00', '0.00'),
(4, 21, '0.00', '0.00', '0.00'),
(4, 22, '0.00', '0.00', '0.00'),
(4, 23, '0.00', '0.00', '0.00'),
(4, 24, '0.00', '0.00', '0.00'),
(4, 25, '0.00', '0.00', '0.00'),
(4, 26, '0.00', '0.00', '0.00'),
(4, 27, '0.00', '0.00', '0.00'),
(4, 28, '0.00', '0.00', '0.00'),
(4, 29, '0.00', '0.00', '0.00'),
(4, 30, '45.00', '0.00', '45.00'),
(4, 31, '0.00', '0.00', '0.00'),
(4, 32, '0.00', '0.00', '0.00'),
(5, 15, '0.00', '0.00', '0.00'),
(5, 16, '0.00', '0.00', '0.00'),
(5, 17, '0.00', '0.00', '0.00'),
(5, 18, '25.00', '0.00', '25.00'),
(5, 19, '25.00', '0.00', '25.00'),
(5, 20, '25.00', '0.00', '25.00'),
(5, 21, '0.00', '0.00', '0.00'),
(5, 22, '0.00', '0.00', '0.00'),
(5, 23, '5.00', '0.00', '5.00'),
(5, 24, '15.00', '0.00', '15.00'),
(5, 25, '0.00', '0.00', '0.00'),
(5, 26, '0.00', '0.00', '0.00'),
(5, 27, '0.00', '0.00', '0.00'),
(5, 28, '0.00', '0.00', '0.00'),
(5, 29, '0.00', '0.00', '0.00'),
(5, 30, '25.00', '0.00', '25.00'),
(5, 31, '0.00', '0.00', '0.00'),
(5, 32, '0.00', '0.00', '0.00'),
(6, 15, '0.00', '0.00', '0.00'),
(6, 16, '0.00', '0.00', '0.00'),
(6, 17, '0.00', '0.00', '0.00'),
(6, 18, '0.00', '0.00', '0.00'),
(6, 19, '0.00', '0.00', '0.00'),
(6, 20, '0.00', '0.00', '0.00'),
(6, 21, '0.00', '0.00', '0.00'),
(6, 22, '0.00', '0.00', '0.00'),
(6, 23, '0.00', '0.00', '0.00'),
(6, 24, '0.00', '0.00', '0.00'),
(6, 25, '0.00', '0.00', '0.00'),
(6, 26, '0.00', '0.00', '0.00'),
(6, 27, '0.00', '0.00', '0.00'),
(6, 28, '0.00', '0.00', '0.00'),
(6, 29, '0.00', '0.00', '0.00'),
(6, 30, '0.00', '0.00', '0.00'),
(6, 31, '0.00', '0.00', '0.00'),
(6, 32, '0.00', '0.00', '0.00'),
(7, 15, '0.00', '0.00', '0.00'),
(7, 16, '0.00', '0.00', '0.00'),
(7, 17, '0.00', '0.00', '0.00'),
(7, 18, '0.00', '0.00', '0.00'),
(7, 19, '0.00', '0.00', '0.00'),
(7, 20, '0.00', '0.00', '0.00'),
(7, 21, '0.00', '0.00', '0.00'),
(7, 22, '0.00', '0.00', '0.00'),
(7, 23, '0.00', '0.00', '0.00'),
(7, 24, '0.00', '0.00', '0.00'),
(7, 25, '0.00', '0.00', '0.00'),
(7, 26, '0.00', '0.00', '0.00'),
(7, 27, '0.00', '0.00', '0.00'),
(7, 28, '0.00', '0.00', '0.00'),
(7, 29, '0.00', '0.00', '0.00'),
(7, 30, '0.00', '0.00', '0.00'),
(7, 31, '0.00', '0.00', '0.00'),
(7, 32, '0.00', '0.00', '0.00'),
(7, 33, '0.00', '0.00', '0.00');

--
-- Wyzwalacze `participation`
--
DELIMITER $$
CREATE TRIGGER `update_amount` BEFORE UPDATE ON `participation` FOR EACH ROW set NEW.amount_paid=NEW.cash+NEW.balance
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `account_id` int(11) NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `type` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `school_year_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `payment`
--

INSERT INTO `payment` (`id`, `date`, `account_id`, `amount`, `type`, `school_year_id`) VALUES
(1, '2018-12-27 19:05:05', 14, '10.00', 'gotowka', 1),
(2, '2018-12-27 19:05:10', 14, '30.00', 'konto', 1),
(3, '2019-01-06 21:06:44', 33, '40.00', 'gotowka', 1),
(4, '2019-01-06 21:50:15', 26, '40.00', 'konto', 1),
(5, '2019-01-06 21:50:20', 19, '60.00', 'konto', 1),
(6, '2019-01-06 21:50:36', 22, '25.00', 'konto', 1),
(7, '2019-01-06 21:50:41', 24, '40.00', 'konto', 1),
(8, '2019-01-06 21:50:53', 28, '14.00', 'konto', 1),
(9, '2019-01-06 21:51:01', 20, '50.00', 'konto', 1),
(10, '2019-01-06 21:51:16', 16, '11.00', 'konto', 1),
(11, '2019-01-06 21:54:35', 33, '30.00', 'konto', 1),
(12, '2019-01-06 21:55:39', 30, '100.00', 'konto', 1),
(13, '2019-01-06 22:13:00', 23, '30.00', 'konto', 1),
(14, '2019-01-07 00:41:09', 19, '40.00', 'konto', 1),
(15, '2019-01-07 00:41:14', 18, '30.00', 'konto', 1),
(16, '2019-01-07 08:43:30', 18, '25.00', 'gotowka', 1),
(17, '2019-01-07 08:43:38', 16, '15.00', 'gotowka', 1),
(18, '2019-01-07 08:43:47', 17, '25.00', 'konto', 1);

--
-- Wyzwalacze `payment`
--
DELIMITER $$
CREATE TRIGGER `before_insert_payment` BEFORE INSERT ON `payment` FOR EACH ROW BEGIN 
DECLARE v_actual_year int; 
SELECT max(id) INTO v_actual_year FROM school_year; 
set NEW.school_year_id := v_actual_year;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `school_year`
--

CREATE TABLE `school_year` (
  `id` int(11) NOT NULL,
  `start_year` int(11) NOT NULL,
  `end_year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `school_year`
--

INSERT INTO `school_year` (`id`, `start_year`, `end_year`) VALUES
(1, 2018, 2019);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `transfer`
--

CREATE TABLE `transfer` (
  `id` int(11) NOT NULL,
  `cash` decimal(11,2) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `class_account` tinyint(1) DEFAULT '1',
  `school_year` int(11) DEFAULT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `transfer`
--

INSERT INTO `transfer` (`id`, `cash`, `date`, `class_account`, `school_year`, `class_id`) VALUES
(2, '5.00', '2018-12-28 20:03:03', 1, 1, 9),
(3, '-5.00', '2018-12-28 20:34:13', 1, 1, 9),
(4, '-3.00', '2018-12-28 20:34:20', 1, 1, 9),
(5, '2.00', '2018-12-28 20:35:04', 1, 1, 9),
(6, '-70.00', '2019-01-06 22:58:45', 1, 1, 11),
(7, '-70.00', '2019-01-06 22:59:11', 1, 1, 11),
(8, '10.00', '2019-01-06 23:20:23', 1, 1, 11),
(9, '23.00', '2019-01-07 09:41:17', 1, 1, 11),
(10, '-38.00', '2019-01-07 09:41:58', 1, 1, 11);

--
-- Wyzwalacze `transfer`
--
DELIMITER $$
CREATE TRIGGER `transfer_school_year` BEFORE INSERT ON `transfer` FOR EACH ROW BEGIN
DECLARE v_actual_year int;

SELECT max(id) INTO v_actual_year FROM school_year;

set NEW.school_year := v_actual_year;
set new.date := (select sysdate());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `username`
--

CREATE TABLE `username` (
  `login` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `hashedPassword` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `type` varchar(1) COLLATE utf8_polish_ci NOT NULL,
  `first_login` tinyint(1) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `username`
--

INSERT INTO `username` (`login`, `password`, `hashedPassword`, `type`, `first_login`, `parent_id`) VALUES
('adamowies@gmail.com', 'Gruby506123', '$2y$10$rtj6juxlajM0VKlNRMNazuzlUgJnZc2zrP5qbAHCtoh0Sae56NCdi', 'p', 0, 28),
('admin', 'admin', '$2y$10$z35.UJTgzfxRye8qFvy4iePTud1v5KfAoTT4ZP2II71HC1kkYEst6', 'a', 0, NULL),
('akieron@gmail.com', 'TheKing99#', '$2y$10$T8bu1Qh3rer2bqZE6/40be/VSvUZO6kveUSLzV2GQq4mqa8mM71HG', 'p', 0, 14),
('annazurczak1@gmail.com', 'Dollares$54', '$2y$10$syqinYoXAA9H7ylBcDt3B.GHGxjjBdp1FqhSU323ke5IMAde0eS5u', 't', 0, 1),
('aw@gmail.com', 'Posortowane8*', '$2y$10$lWhDN3hbJITJZ2BcpkeKF.c84SflBHnwvq1j9wBrpIXjcQNwnZljW', 't', 0, 31),
('fdembski@gmail.com', 'Dollars44$a', '$2y$10$dmevasmiYCbyGb.bAsyoEu3w4zpcvDYjGpVxi1iRZcvq9bt9pDqSC', 't', 0, 12),
('gpocieszna@gmail.com', 'Lumpeks', '$2y$10$WEuXviG.t2a810c4sTx1COHu9IIrC4AdldgqwOHpjKyLQNaBXDyVG', 'p', 0, 13),
('grzywam@gmail.com', 'Rudolf77*', '$2y$10$UXHWqSM8W4WwabBbWDh4yeOvcxEwbfqS1HO7gb.afUOB6Lrp43/W6', 'p', 0, 24),
('gzurczak@wp.pl', '1MXMDSeq', '$2y$10$FlNpQBGiAhucjqo70g15..qGztRhLqHPNIe4voNfM3Ynn5ZA3vJmW', 'p', 1, 6),
('ikozica@wp.pl', 'Superduper33#', '$2y$10$BMZPamGmdmj.D6N1S8N0LePhiRkY84PAJFcwheYysvMTDUQYU3HM2', 'p', 0, 20),
('januszkowalski@gmail.com', '3Edcvfr4$', '$2y$10$Jc8teBFFxwcok5gVJsU4SO1UZZ5i3wUNSYLrMGu/maWpJi07k0WJ2', 't', 0, 11),
('jkowalski@onet.pl', 'v3v0hdpz', '$2y$10$UOBtjUQulzeNRK2oQMUgM.MetVgPvnv60Fr8A68/mf6EPwNyQIOr2', 't', 1, 35),
('jmalepszy@gmail.com', 'Simba77$', '$2y$10$/JxhsEcEaEth8.KBWTb69.4LAlzq3UXks1KWLPSYBSfNavM78C4wy', 'p', 0, 25),
('kalinalisowska678@gmail.com', 'NUqpaLrx', '$2y$10$G7bRWxbs1sPFofJyDQ6mje64BSsU.elrTIKWIBN3NzoUDp76D1heG', 'p', 1, 22),
('kgwarek123@gmail.com', 'Haslo123!', '$2y$10$CFa.nYmwrBVDyk8.3fd5IubsfSCrwIXsRowJcSGmSElMMw1H39TyS', 'p', 0, 23),
('kiwaszczuk@gmail.com', 'i', '$2y$10$6Q1DDvUo.tsMUzNw.m13wO.GtzeEZpCO6g7yeP9.jpLNj3PdRzDT.', 'p', 0, 30),
('kluczykmamamamamma@gmail.com', 'W9xjFwcI', '$2y$10$XDZvpevGs74iGYfh7bPlPeKGe6MXYz6Jhqf2GXg54jwwJg2/yTwZS', 'p', 1, 10),
('ludwikmazur@gmail.com', 'Euroko89&amp;', '$2y$10$rxJpc2gezATeHMUydF5JQOlITIkDAmBbmVXST/6nefbzKm8c4UvTi', 'p', 0, 29),
('mirekrzepeckiyy@gmail.com', 'Onetonet22@', '$2y$10$Rv5THTPAfkgdbKzlzhSPjuUTWtnd5411iSvWnynmLyrH/S3iFKsva', 'p', 0, 21),
('mkajoch55@gmail.com', 'Grazyna77$', '$2y$10$eyStTRT4upsXPSLNxNUIFOKEuHGmsa.JcDG/6luJz2WVMfo/XCGEe', 'p', 0, 19),
('nieznany012@wp.pl', 'd', '$2y$10$rLHwI0/Mw1/IqQ82Pj7E3OBU18/nsyFbRbbtTKTojK1whzFdEn3vu', 'p', 0, 5),
('olejnikbozena@gmail.com', 'Zupagoraca55$', '$2y$10$IbSbIf1ADKrRMUgVxajlEeYfHqo4RVP6euJu4C3HYAmbmB8c9rX8.', 'p', 0, 15),
('pati3285485@onet.pl', 'Suchar567&lt;&gt;&#039;', '$2y$10$oAtqIEPOT1w7zgjoc/qaqeeRgzdxKYFdevdfj0yhqlRKq3RDHnj4u', 'p', 0, 8),
('pawlaykpiotrxd@onet.pl', 'O9hDxrsy', '$2y$10$3Dj6rwBDlBzg4b8L8hQLHu66oRKka9Td.J0YmjyIeoZys0O4RyVYi', 'p', 1, 3),
('piotrpawlaczyk21@gmail.com', 'sQSiaWr8', '$2y$10$dJe/IMMfH2GJaMxIk.fUTeD4pspID0aeb7IgGRGYwdITpbJ85zHUC', 'p', 1, 4),
('rafalhazard1977@gmail.com', '0zROYATC', '$2y$10$1BcNcQ2v/XcBLVyWC129UOWCz8iB7ZYMgSfrWG5zrgG/cE6GcA3NG', 'p', 1, 17),
('restowkarol@gmail.com', 'Romeo66^', '$2y$10$2Sr3hPGceOmxwZ6BVsp.q.Q/GLFYVS8daqGXi/gJbq6VjvxiiXZdC', 'p', 0, 26),
('robert23891@wp.pl', 'robciu', '$2y$10$KCcHzwiGA80sg5xlJCfSY.Vje2JBUAgmfF1bxATN0yg988A9uttAC', 't', 0, 2),
('tkrycha@gmail.com', 's', '$2y$10$mxxG6Qo2Wsn41g01xA8fGOeThk3BiumLS/Y27CogDmhQcRUWMBQ0W', 'p', 0, 27),
('wagonkarolina181@gmail.com', 'NIieayHU', '$2y$10$fP.bZJ6Pzf9nZSAMPuzBkuGb0O79/d7PBbvmgC1Xz5.O2spbLgsHO', 'p', 1, 18),
('waldeknowackihehe@gmail.com', '1QARsjnm', '$2y$10$P1Gftd.HXkGotUIa4DYMDu3pyQXGkhz0IlNna4z.srAC889t7QIHS', 'p', 1, 9),
('wiktoriakaczmarek@gmail.com', '3ip24Ahu', '$2y$10$g2oME1UmXJaDTRqVR43KCuc0gcnBpawwzBshVsxaGrpjyhfILzp0.', 'p', 1, 16),
('wkhdhd@wp.pl', 'UqexXx5B', '$2y$10$VsByIxyE0P/RGMdW3kOYJ.wW9HZyC6CoenClS/hqfyWuOxtMlko6e', 'p', 1, 7);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_child_FK` (`child_id`);

--
-- Indeksy dla tabeli `child`
--
ALTER TABLE `child`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `child_class_FK` (`class_id`),
  ADD KEY `child_parent_FK` (`parent_id`);

--
-- Indeksy dla tabeli `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_parent_FK` (`parent_id`),
  ADD KEY `school_year_FK` (`school_year_id`);

--
-- Indeksy dla tabeli `class_account`
--
ALTER TABLE `class_account`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_account_class_FK` (`class_id`);

--
-- Indeksy dla tabeli `class_account_payment`
--
ALTER TABLE `class_account_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_account_payment_child_FK` (`child_id`),
  ADD KEY `class_account_payment_class_account_FK` (`class_account_id`),
  ADD KEY `school_year_FK` (`school_year_id`);

--
-- Indeksy dla tabeli `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_class_FK` (`class_id`),
  ADD KEY `school_year_FK` (`school_year_id`);

--
-- Indeksy dla tabeli `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_class_account_FK` (`class_account_id`),
  ADD KEY `school_year_FK` (`school_year_id`);

--
-- Indeksy dla tabeli `parent`
--
ALTER TABLE `parent`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UniqueEmail` (`email`);

--
-- Indeksy dla tabeli `participation`
--
ALTER TABLE `participation`
  ADD PRIMARY KEY (`event_id`,`child_id`) USING BTREE,
  ADD KEY `participation_child_FK` (`child_id`);

--
-- Indeksy dla tabeli `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_account_FK` (`account_id`),
  ADD KEY `school_year_FK` (`school_year_id`);

--
-- Indeksy dla tabeli `school_year`
--
ALTER TABLE `school_year`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `transfer`
--
ALTER TABLE `transfer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_year_transfer_FK` (`school_year`);

--
-- Indeksy dla tabeli `username`
--
ALTER TABLE `username`
  ADD PRIMARY KEY (`login`),
  ADD KEY `username_parent_FK` (`parent_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT dla tabeli `child`
--
ALTER TABLE `child`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT dla tabeli `class`
--
ALTER TABLE `class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT dla tabeli `class_account`
--
ALTER TABLE `class_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT dla tabeli `class_account_payment`
--
ALTER TABLE `class_account_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT dla tabeli `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT dla tabeli `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `parent`
--
ALTER TABLE `parent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT dla tabeli `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT dla tabeli `school_year`
--
ALTER TABLE `school_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `transfer`
--
ALTER TABLE `transfer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_child_FK` FOREIGN KEY (`child_id`) REFERENCES `child` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `child`
--
ALTER TABLE `child`
  ADD CONSTRAINT `child_class_FK` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `child_parent_FK` FOREIGN KEY (`parent_id`) REFERENCES `parent` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_parent_FK` FOREIGN KEY (`parent_id`) REFERENCES `parent` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `school_year_FK` FOREIGN KEY (`school_year_id`) REFERENCES `school_year` (`id`) ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `class_account`
--
ALTER TABLE `class_account`
  ADD CONSTRAINT `class_account_class_FK` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `class_account_payment`
--
ALTER TABLE `class_account_payment`
  ADD CONSTRAINT `class_account_payment_child_FK` FOREIGN KEY (`child_id`) REFERENCES `child` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `class_account_payment_class_account_FK` FOREIGN KEY (`class_account_id`) REFERENCES `class_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `school_year_class_account_payment_FK` FOREIGN KEY (`school_year_id`) REFERENCES `school_year` (`id`);

--
-- Ograniczenia dla tabeli `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_class_FK` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_school_year_FK` FOREIGN KEY (`school_year_id`) REFERENCES `school_year` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `expense`
--
ALTER TABLE `expense`
  ADD CONSTRAINT `expense_class_account_FK` FOREIGN KEY (`class_account_id`) REFERENCES `class_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expense_school_year_FK` FOREIGN KEY (`school_year_id`) REFERENCES `school_year` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `participation`
--
ALTER TABLE `participation`
  ADD CONSTRAINT `participation_child_FK` FOREIGN KEY (`child_id`) REFERENCES `child` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `participation_event_FK` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_account_FK` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_school_year_FK` FOREIGN KEY (`school_year_id`) REFERENCES `school_year` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `transfer`
--
ALTER TABLE `transfer`
  ADD CONSTRAINT `school_year_transfer_FK` FOREIGN KEY (`school_year`) REFERENCES `school_year` (`id`) ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `username`
--
ALTER TABLE `username`
  ADD CONSTRAINT `username_parent_FK` FOREIGN KEY (`parent_id`) REFERENCES `parent` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
