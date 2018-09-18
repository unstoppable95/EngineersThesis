-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 18 Wrz 2018, 13:50
-- Wersja serwera: 10.1.34-MariaDB
-- Wersja PHP: 7.2.8

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
  `balance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

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
-- Wyzwalacze `child`
--
DELIMITER $$
CREATE TRIGGER `addChildAccount` AFTER INSERT ON `child` FOR EACH ROW insert into account (child_id,balance ) values (NEW.id,0)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delAccountChild` AFTER DELETE ON `child` FOR EACH ROW delete from account where child_id=OLD.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `class`
--

CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `parent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Wyzwalacze `class`
--
DELIMITER $$
CREATE TRIGGER `addClassAccount` AFTER INSERT ON `class` FOR EACH ROW INSERT into class_acount (balance,expenses, 	excpected_budget,class_id) values(0,0,0,NEW.id)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delClassAccount` AFTER DELETE ON `class` FOR EACH ROW delete from class_acount where class_id=OLD.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `class_acount`
--

CREATE TABLE `class_acount` (
  `id` int(11) NOT NULL,
  `balance` int(11) DEFAULT NULL,
  `expenses` int(11) DEFAULT NULL,
  `excpected_budget` int(11) DEFAULT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `price` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Wyzwalacze `event`
--
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
  `amount_paid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `account_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `username`
--

CREATE TABLE `username` (
  `login` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `type` varchar(1) COLLATE utf8_polish_ci NOT NULL,
  `first_login` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `username`
--

INSERT INTO `username` (`login`, `password`, `type`, `first_login`) VALUES
('admin', 'admin', 'a', 0),
('kasia', 'kasia', 't', 0),
('piter', 'piter', 'p', 0);

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
-- Indeksy dla tabeli `class_acount`
--
ALTER TABLE `class_acount`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_account_class_FK` (`class_id`);

--
-- Indeksy dla tabeli `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`login`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT dla tabeli `child`
--
ALTER TABLE `child`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT dla tabeli `class`
--
ALTER TABLE `class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT dla tabeli `class_acount`
--
ALTER TABLE `class_acount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT dla tabeli `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT dla tabeli `parent`
--
ALTER TABLE `parent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT dla tabeli `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

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
-- Ograniczenia dla tabeli `class_acount`
--
ALTER TABLE `class_acount`
  ADD CONSTRAINT `class_account_class_FK` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `participation`
--
ALTER TABLE `participation`
  ADD CONSTRAINT `participation_child_FK` FOREIGN KEY (`child_id`) REFERENCES `child` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_account_FK` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
