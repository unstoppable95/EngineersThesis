-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 17 Sty 2019, 22:17
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
CREATE TRIGGER `addChildAccountChildParticipation` AFTER INSERT ON `child` FOR EACH ROW begin
DECLARE v_count, v_loop_counter, v_id ,v_child_count INTEGER;
DECLARE v_date,curDate DATE;
DECLARE v_monthly_fee INTEGER;
DECLARE classEvents cursor for select id,date from event where class_id=NEW.class_id;

insert into account (child_id,balance ) values (NEW.id,0);
select monthly_fee into v_monthly_fee from class_account where class_id=NEW.class_id;
select count(*) into v_child_count from child where class_id = NEW.class_id;



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
-- Wyzwalacze `class`
--
DELIMITER $$
CREATE TRIGGER `addClassAccount` AFTER INSERT ON `class` FOR EACH ROW INSERT into class_account (balance,cash,class_id) values(0,0,NEW.id)
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
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

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
DECLARE v_balance , v_cash decimal;
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
 fetch my_cursor into  v_event_id, v_child_id, v_amount_paid,v_cash,v_balance;
  SET v_loop_counter =v_loop_counter+1;
  IF(select datediff(eventDate,curDate) >=0) THEN
update account set balance=balance+v_amount_paid, cash=cash+v_cash where 	child_id=v_child_id; 
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
  `hashedPassword` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `type` varchar(1) COLLATE utf8_polish_ci NOT NULL,
  `first_login` tinyint(1) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `username`
--

INSERT INTO `username` (`login`, `hashedPassword`, `type`, `first_login`, `parent_id`) VALUES
('admin', '$2y$10$z35.UJTgzfxRye8qFvy4iePTud1v5KfAoTT4ZP2II71HC1kkYEst6', 'a', 0, NULL);

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
  ADD KEY `school_year_transfer_FK` (`school_year`),
  ADD KEY `transfer_class_fk` (`class_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `child`
--
ALTER TABLE `child`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `class`
--
ALTER TABLE `class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `class_account`
--
ALTER TABLE `class_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `class_account_payment`
--
ALTER TABLE `class_account_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `parent`
--
ALTER TABLE `parent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `school_year`
--
ALTER TABLE `school_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `transfer`
--
ALTER TABLE `transfer`
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
  ADD CONSTRAINT `school_year_transfer_FK` FOREIGN KEY (`school_year`) REFERENCES `school_year` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transfer_class_fk` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `username`
--
ALTER TABLE `username`
  ADD CONSTRAINT `username_parent_FK` FOREIGN KEY (`parent_id`) REFERENCES `parent` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
