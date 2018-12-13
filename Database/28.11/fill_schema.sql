-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 28 Lis 2018, 22:51
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
(2, 2, '5.00', '5.00'),
(3, 3, '0.00', '0.00'),
(4, 4, '20.00', '0.00'),
(5, 5, '5.00', '5.00'),
(6, 6, '30.00', '5.00'),
(7, 7, '0.00', '0.00'),
(8, 8, '25.00', '15.00'),
(9, 9, '10.00', '0.00'),
(10, 10, '3.00', '4.00'),
(11, 11, '0.00', '0.00'),
(12, 12, '0.00', '5.00');

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
(1, 'Tomek', 'Olejniczak', '2006-12-01', 2, 1),
(2, 'Basia', 'Pawlaczyk', '2006-08-15', 3, 1),
(3, 'Aleksandra', 'Kowalska', '2006-06-09', 4, 1),
(4, 'Katrzyna', 'Jóźwiak', '2006-02-25', 5, 1),
(5, 'Patryk', 'Glapiak', '2007-08-05', 6, 1),
(6, 'Krystian', 'Żurczak', '2006-05-05', 1, 1),
(7, 'Piotr', 'Jóźwiak', '2001-10-05', 7, 2),
(8, 'Tomasz', 'Nowak', '2001-08-05', 8, 2),
(9, 'Witold', 'Chudy', '2001-06-08', 9, 2),
(10, 'Kasper', 'Kowal', '2001-05-08', 10, 2),
(11, 'Karol', 'Michałowski', '2001-08-05', 11, 2),
(12, 'Bartosz', 'Górka', '2002-05-08', 12, 2);

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

select MONTH(CURDATE()) into currentMonth from dual;

select sum(IFNULL(amount,0)) into sumPayment from class_account_payment where child_id=OLD.id;

select monthly_fee into monthlyFee from class_account where class_id =OLD.class_id;


select id into classaccountID from class_account where class_id =OLD.class_id;

if currentMonth >=1 and currentMonth <=6 THEN
set currentMonth=currentMonth+12;
end if;
set differenceInMonths = currentMonth -8;
set charge= differenceInMonths*monthlyFee;

delete from class_account_payment where child_id=OLD.id;

update class_account set expected_budget=expected_budget-10*monthlyFee+charge where class_id=classaccountID;

insert into class_account_payment (amount,type,class_account_id) values (charge,"auto",classaccountID);


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
  `bank_account_number` varchar(26) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `class`
--

INSERT INTO `class` (`id`, `name`, `parent_id`, `bank_account_number`) VALUES
(1, '3a', 1, '71124041849500142373368890'),
(2, '5b', 7, '29150023705269650626818448');

--
-- Wyzwalacze `class`
--
DELIMITER $$
CREATE TRIGGER `addClassAccount` AFTER INSERT ON `class` FOR EACH ROW INSERT into class_account (balance,expenses, 	expected_budget,class_id) values(0,0,0,NEW.id)
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
(1, '21.00', '13.00', '4.00', 0, 240, 1),
(2, '25.00', '6.00', '4.00', 0, 240, 2);

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
  `child_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `class_account_payment`
--

INSERT INTO `class_account_payment` (`id`, `amount`, `date`, `type`, `class_account_id`, `child_id`) VALUES
(1, '5.00', '2018-11-28 21:02:53', 'gotowka', 1, 2),
(2, '5.00', '2018-11-28 21:02:53', 'konto', 1, 2),
(3, '8.00', '2018-11-28 21:03:19', 'gotowka', 1, 6),
(4, '8.00', '2018-11-28 21:03:19', 'konto', 1, 6),
(5, '8.00', '2018-11-28 21:15:03', 'gotowka', 1, 4),
(6, '8.00', '2018-11-28 21:15:03', 'konto', 1, 4),
(7, '5.00', '2018-11-28 21:17:26', 'gotowka', 1, 5),
(8, '5.00', '2018-11-28 21:17:26', 'konto', 1, 5),
(9, '8.00', '2018-11-28 21:38:41', 'gotowka', 2, 10),
(10, '8.00', '2018-11-28 21:38:41', 'konto', 2, 10),
(11, '3.00', '2018-11-28 21:38:48', 'gotowka', 2, 10),
(12, '3.00', '2018-11-28 21:38:48', 'konto', 2, 10),
(13, '5.00', '2018-11-28 21:41:50', 'gotowka', 2, 12),
(14, '5.00', '2018-11-28 21:41:50', 'konto', 2, 12),
(15, '8.00', '2018-11-28 21:43:52', 'gotowka', 2, 8),
(16, '8.00', '2018-11-28 21:43:52', 'konto', 2, 8);

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
  `completed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `event`
--

INSERT INTO `event` (`id`, `name`, `price`, `date`, `class_id`, `completed`) VALUES
(1, 'Kulig', '15.00', '2018-12-20', 1, 0),
(2, 'Zakopane 2 dni ', '100.00', '2019-01-30', 1, 0),
(3, 'Świąteczny Wrocław', '35.00', '2018-12-18', 2, 0),
(4, 'Ognisko', '5.00', '2018-11-30', 2, 1);

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
  `type` varchar(30) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `expense`
--

INSERT INTO `expense` (`id`, `name`, `price`, `date`, `class_account_id`, `type`) VALUES
(1, 'mleko', '5.00', '2018-11-28', 1, 'gotowka'),
(2, 'ksero', '2.00', '2018-11-20', 1, 'konto'),
(3, 'kwiaty', '3.00', '2018-11-22', 1, 'gotowka'),
(4, 'mleko', '5.00', '2018-11-28', 1, 'gotowka'),
(5, 'mleko', '3.00', '2018-11-28', 1, 'konto'),
(6, 'kwiaty na dzień nauczyciela', '15.00', '2018-11-28', 2, 'gotowka'),
(7, 'kwiaty na dzień nauczyciela', '15.00', '2018-11-28', 2, 'konto'),
(8, 'herbata', '3.00', '2018-11-28', 2, 'gotowka');

--
-- Wyzwalacze `expense`
--
DELIMITER $$
CREATE TRIGGER `addExpenseSetDate` BEFORE INSERT ON `expense` FOR EACH ROW set NEW.date=NOW()
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
(2, 'Robert', 'Olejniczak', 'robert23891@wp.pl', 'p'),
(3, 'Jan', 'Pawlaczyk', 'pawlaykok@onet.pl', 'p'),
(4, 'Piotr', 'Kowalski', 'kowal85845@onet.pl', 'p'),
(5, 'Adrianna', 'Jóźwiak', 'jozwiakkasia258@wp.pl', 'p'),
(6, 'Zofia', 'Glapiak', 'glapiakpatrykkkk@gmail.com', 'p'),
(7, 'Kasia', 'Jóźwiak', 'kasjozw@gmail.com', 't'),
(8, 'Aurelia', 'Nowak', 'elaelaelaleasd@gmail.com', 'p'),
(9, 'Andrzej', 'Chudy', 'andriejchudy88@onet.pl', 'p'),
(10, 'Mateusz', 'Kowal', 'matikowalhyhy@wp.pl', 'p'),
(11, 'Jarosław', 'Michałowski', 'jareczekmichalowskihoho@wp.pl', 'p'),
(12, 'Bogumiła', 'Górka', 'bogulagorek@gmail.com', 'p');

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

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `participation`
--

CREATE TABLE `participation` (
  `event_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `amount_paid` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `participation`
--

INSERT INTO `participation` (`event_id`, `child_id`, `amount_paid`) VALUES
(1, 1, '0.00'),
(1, 2, '0.00'),
(1, 3, '0.00'),
(1, 4, '0.00'),
(1, 5, '0.00'),
(1, 6, '15.00'),
(2, 1, '0.00'),
(2, 2, '0.00'),
(2, 3, '0.00'),
(2, 4, '0.00'),
(2, 5, '0.00'),
(2, 6, '0.00'),
(3, 7, '0.00'),
(3, 8, '0.00'),
(3, 9, '35.00'),
(3, 10, '10.00'),
(3, 11, '0.00'),
(3, 12, '0.00'),
(4, 7, '5.00'),
(4, 8, '5.00'),
(4, 9, '5.00'),
(4, 10, '5.00'),
(4, 11, '5.00'),
(4, 12, '5.00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `account_id` int(11) NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `type` varchar(30) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `username`
--

CREATE TABLE `username` (
  `login` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `type` varchar(1) COLLATE utf8_polish_ci NOT NULL,
  `first_login` tinyint(1) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `username`
--

INSERT INTO `username` (`login`, `password`, `type`, `first_login`, `parent_id`) VALUES
('admin', 'admin', 'a', 0, NULL),
('andriejchudy88@onet.pl', 'VFvlaPpx', 'p', 1, 9),
('annazurczak1@gmail.com', 'anka', 't', 0, 1),
('bogulagorek@gmail.com', 'ArYX0WZs', 'p', 1, 12),
('elaelaelaleasd@gmail.com', 'FwF22txz', 'p', 1, 8),
('glapiakpatrykkkk@gmail.com', 'elcXfJKK', 'p', 1, 6),
('jareczekmichalowskihoho@wp.pl', 'WgSVSQVe', 'p', 1, 11),
('jozwiakkasia258@wp.pl', 'UlBlMeKJ', 'p', 1, 5),
('kasjozw@gmail.com', 'kasia', 't', 0, 7),
('kowal85845@onet.pl', 'jiUHkjUd', 'p', 1, 4),
('matikowalhyhy@wp.pl', 'CPf0Ih1F', 'p', 1, 10),
('pawlaykok@onet.pl', 'Zb84xC1s', 'p', 1, 3),
('robert23891@wp.pl', 'robert', 'p', 0, 2);

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
  ADD KEY `class_parent_FK` (`parent_id`);

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
  ADD KEY `class_account_payment_class_account_FK` (`class_account_id`);

--
-- Indeksy dla tabeli `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_class_FK` (`class_id`);

--
-- Indeksy dla tabeli `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_class_account_FK` (`class_account_id`);

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
  ADD KEY `payment_account_FK` (`account_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT dla tabeli `child`
--
ALTER TABLE `child`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT dla tabeli `class`
--
ALTER TABLE `class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `class_account`
--
ALTER TABLE `class_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `class_account_payment`
--
ALTER TABLE `class_account_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT dla tabeli `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT dla tabeli `parent`
--
ALTER TABLE `parent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT dla tabeli `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `class_parent_FK` FOREIGN KEY (`parent_id`) REFERENCES `parent` (`id`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `class_account_payment_class_account_FK` FOREIGN KEY (`class_account_id`) REFERENCES `class_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_class_FK` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `expense`
--
ALTER TABLE `expense`
  ADD CONSTRAINT `expense_class_account_FK` FOREIGN KEY (`class_account_id`) REFERENCES `class_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `payment_account_FK` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `username`
--
ALTER TABLE `username`
  ADD CONSTRAINT `username_parent_FK` FOREIGN KEY (`parent_id`) REFERENCES `parent` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
