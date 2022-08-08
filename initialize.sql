drop table Ticket;
drop table Buyer_Purchase;
drop table Sculpture;
drop table Painting;
drop table Art3;
drop table Art2;
drop table Art1;
drop table Room;
drop table Exhibition;
drop table ArtOwner;
drop table Artist;
drop table ArtPeriod;


CREATE TABLE ArtPeriod
	(PeriodName VARCHAR2(50) PRIMARY KEY,
	StartYear int NOT NULL,
	EndYear int NOT NULL);
grant select on ArtPeriod to public;

CREATE TABLE Artist
	(ArtistID int PRIMARY KEY,
	FirstName VARCHAR2(50),
	LastName VARCHAR2(50),
	DateOfBirth date,
	DateOfDeath date);
grant select on Artist to public;

CREATE TABLE ArtOwner
	(OwnerID int PRIMARY KEY,
  FirstName VARCHAR2(50),
  LastName VARCHAR2(50),
  Email VARCHAR2(50) NOT NULL,
  UNIQUE (Email));
grant select on ArtOwner to public;

CREATE TABLE Exhibition
	(ExhibitionName VARCHAR2(50),
	StartDate date NOT NULL,
	EndDate date NOT NULL,
	PRIMARY KEY (ExhibitionName, StartDate));
grant select on Exhibition to public;

CREATE TABLE Room
	(RoomNumber int PRIMARY KEY,
	FloorNumber int NOT NULL,
	ExhibitionName VARCHAR2(50),
	StartDate date,
	FOREIGN KEY (ExhibitionName, StartDate) REFERENCES Exhibition (ExhibitionName, StartDate) ON DELETE CASCADE);
grant select on Room to public;

CREATE TABLE Art1
	(YearCreated int PRIMARY KEY,
	ArtPeriod_Name VARCHAR2(50),
	FOREIGN KEY (ArtPeriod_Name) REFERENCES ArtPeriod);
grant select on Art1 to public;

CREATE TABLE Art2
	(ArtistID int PRIMARY KEY,
	ArtPeriod_Name VARCHAR2(50),
	FOREIGN KEY (ArtPeriod_Name) REFERENCES ArtPeriod);
grant select on Art2 to public;

CREATE TABLE Art3
	(IdentificationNumber int PRIMARY KEY,
  Title VARCHAR2(50),
  YearCreated int,
  Price int DEFAULT 0.00,
  ArtistID int,
  OwnerID int,
  RoomNumber int DEFAULT 0,
  ExhibitionFee int DEFAULT 0.00,
	FOREIGN KEY (ArtistID) REFERENCES Artist,
	FOREIGN KEY (OwnerID) REFERENCES ArtOwner ON DELETE CASCADE,
	FOREIGN KEY (RoomNumber) REFERENCES Room ON DELETE CASCADE);
grant select on Art3 to public;

CREATE TABLE Painting
	(IdentificationNumber int PRIMARY KEY,
	FOREIGN KEY (IdentificationNumber) REFERENCES Art3 ON DELETE CASCADE);
grant select on Painting to public;

CREATE TABLE Sculpture
	(IdentificationNumber int PRIMARY KEY,
	FOREIGN KEY (IdentificationNumber) REFERENCES Art3 ON DELETE CASCADE);
grant select on Sculpture to public;

CREATE TABLE Buyer_Purchase
	(FirstName VARCHAR2(50),
	LastName VARCHAR2(50),
	Email VARCHAR2(50) NOT NULL,
	Commission int DEFAULT 0.00,
	IdentificationNumber int,
	PRIMARY KEY (FirstName, LastName, IdentificationNumber),
	FOREIGN KEY (IdentificationNumber) REFERENCES Art3 ON DELETE CASCADE);
grant select on Buyer_Purchase to public;

CREATE TABLE Ticket
	(TicketNumber int PRIMARY KEY,
	EntranceFee int DEFAULT 5.00,
	ValidDate date,
	ExhibitionName VARCHAR2(50) NOT NULL,
	StartDate date NOT NULL,
	FOREIGN KEY (ExhibitionName, StartDate) REFERENCES Exhibition (ExhibitionName, StartDate) ON DELETE CASCADE);
grant select on Ticket to public;

insert into ArtPeriod values('Gothic', 1140, 1600);
insert into ArtPeriod values('Renaissance', 1400, 1600);
insert into ArtPeriod values('Rococo', 1720, 1760);
insert into ArtPeriod values('Realism', 1840, 1870);
insert into ArtPeriod values('Contemporary Art', 1978, NULL);

insert into Artist values(1, 'John', 'Doe', TO_DATE('4/5/1955', 'MM/DD/YYYY'), NULL);
insert into Artist values(2, 'Thomas', 'Nook', TO_DATE('3/1/1100', 'MM/DD/YYYY'), TO_DATE('10/15/1200', 'MM/DD/YYYY'));
insert into Artist values(3, 'Emily', 'Carr', TO_DATE('12/13/1820', 'MM/DD/YYYY'), TO_DATE('3/2/1880', 'MM/DD/YYYY'));
insert into Artist values(4, 'Bernard', 'Brown', TO_DATE('5/24/1701', 'MM/DD/YYYY'), TO_DATE('12/12/1770', 'MM/DD/YYYY'));
insert into Artist values(5, 'Julia', 'Fox', TO_DATE('1/1/1410', 'MM/DD/YYYY'), TO_DATE('2/4/1460', 'MM/DD/YYYY'));

insert into ArtOwner values(1, 'Tom', 'Nook', 'TomNook@islandmail.com');
insert into ArtOwner values(2, 'Timmy', 'Nook', 'Timmy@islandmail.com');
insert into ArtOwner values(3, 'Tommy', 'Nook', 'Tommy@islandmail.com');
insert into ArtOwner values(4, 'Isabelle', NULL, 'Isabelle@islandmail.com');
insert into ArtOwner values(5, 'Blanca', 'Neko', 'blankaiscat@gmail.com');

insert into Exhibition values('Kids Take Over', TO_DATE('4/1/2022', 'MM/DD/YYYY'), TO_DATE('8/31/2022', 'MM/DD/YYYY'));
insert into Exhibition values('In Memory of Andrew Green', TO_DATE('5/20/2022', 'MM/DD/YYYY'), TO_DATE('7/15/2022', 'MM/DD/YYYY'));
insert into Exhibition values('Art Connects', TO_DATE('5/20/2022', 'MM/DD/YYYY'), TO_DATE('7/31/2022', 'MM/DD/YYYY'));
insert into Exhibition values('Canadian Women Artists in Modern Moment', TO_DATE('6/1/2022', 'MM/DD/YYYY'), TO_DATE('8/31/2022', 'MM/DD/YYYY'));
insert into Exhibition values('Spotlight: John Doe', TO_DATE('8/2/2022', 'MM/DD/YYYY'), TO_DATE('12/1/2022', 'MM/DD/YYYY'));

insert into Room values(101, 1, 'Kids Take Over', TO_DATE('4/1/2022', 'MM/DD/YYYY'));
insert into Room values(201, 2, 'In Memory of Andrew Green', TO_DATE('5/20/2022', 'MM/DD/YYYY'));
insert into Room values(301, 3, 'Art Connects', TO_DATE('5/20/2022', 'MM/DD/YYYY'));
insert into Room values(401, 4, 'Spotlight: John Doe', TO_DATE('8/2/2022', 'MM/DD/YYYY'));
insert into Room values(402, 4, 'Spotlight: John Doe', TO_DATE('8/2/2022', 'MM/DD/YYYY'));

insert into Art1 values(1150, 'Gothic');
insert into Art1 values(1160, 'Gothic');
insert into Art1 values(1170, 'Gothic');
insert into Art1 values(1459, 'Renaissance');
insert into Art1 values(1470, 'Renaissance');
insert into Art1 values(1744, 'Rococo');
insert into Art1 values(1860, 'Realism');
insert into Art1 values(1865, 'Realism');
insert into Art1 values(1980, 'Contemporary Art');
insert into Art1 values(1981, 'Contemporary Art');

insert into Art2 values(1, 'Contemporary Art');
insert into Art2 values(2, 'Gothic');
insert into Art2 values(3, 'Realism');
insert into Art2 values(4, 'Rococo');
insert into Art2 values(5, 'Renaissance');

insert into Art3 values(10001, 'Trees', 1150, 7000.00, 2, 1, 301, 5.00);
insert into Art3 values(10002, 'Sunflowers', 1160, 8000.99, 2, 1, 301, 5.00);
insert into Art3 values(10009, 'Last Piece', 1170, 4000.00, 2, 1, 101, 4.00);
insert into Art3 values(10003, 'wonderful farm', 1980, 100000.00, 1, 2, 401, 10.00);
insert into Art3 values(10007, 'Malicious Honesty', 1981, 200000.00, 1, 2, 402, 3.00);
insert into Art3 values(20041, 'Clock', 1860, 345000.00, 3, 3, 301, 5.00);
insert into Art3 values(20135, 'dramatic mirror', 1865, 4500.89, 3, 3, 301, 5.00);
insert into Art3 values(26951, 'The Starry Night', 1744, 5000.00, 4, 4, 301, 5.00);
insert into Art3 values(30019, 'Mona Lisa', 1744, 7000000.00, 4, 4, 301, 5.00);
insert into Art3 values(32107, 'Display of Patience', 1459, 100000.00, 5, 5, 101, 2.00);
insert into Art3 values(35555, 'Lesson', 1470, 150000.00, 5, 5, 101, 2.00);

insert into Painting values(10001);
insert into Painting values(10002);
insert into Painting values(10007);
insert into Painting values(10009);
insert into Painting values(20135);
insert into Painting values(30019);

insert into Sculpture values(10003);
insert into Sculpture values(20041);
insert into Sculpture values(26951);
insert into Sculpture values(32107);
insert into Sculpture values(35555);

insert into Buyer_Purchase values('Leonor', 'Coffey', 'leoisawesome@gmail.com', 5000.99, 10001);
insert into Buyer_Purchase values('Estelle', 'Leach', 'estelle.leach@gmail.com', 100000.99, 10007);
insert into Buyer_Purchase values('Traci', 'Bolton', 'traciBolts@gmail.com', 4200000.50, 30019);
insert into Buyer_Purchase values('Lynn', 'Horne', 'lynnhorne234@gmail.com', 20000.99, 32107);
insert into Buyer_Purchase values('Jessie', 'Good', 'jessielovesart@gmail.com', 55000.49, 35555);

insert into Ticket values(100, 5.00, TO_DATE('6/10/2022', 'MM/DD/YYYY'), 'Kids Take Over', TO_DATE('4/1/2022', 'MM/DD/YYYY'));
insert into Ticket values(101, 5.00, TO_DATE('6/14/2022', 'MM/DD/YYYY'), 'Kids Take Over', TO_DATE('4/1/2022', 'MM/DD/YYYY'));
insert into Ticket values(103, 5.00, TO_DATE('6/22/2022', 'MM/DD/YYYY'), 'Kids Take Over', TO_DATE('4/1/2022', 'MM/DD/YYYY'));
insert into Ticket values(104, 5.00, TO_DATE('7/1/2022', 'MM/DD/YYYY'), 'Kids Take Over', TO_DATE('4/1/2022', 'MM/DD/YYYY'));
insert into Ticket values(105, 10.00, TO_DATE('7/5/2022', 'MM/DD/YYYY'), 'Art Connects', TO_DATE('5/20/2022', 'MM/DD/YYYY'));
insert into Ticket values(106, 10.00, TO_DATE('7/11/2022', 'MM/DD/YYYY'), 'Art Connects', TO_DATE('5/20/2022', 'MM/DD/YYYY'));
insert into Ticket values(107, 7.00, TO_DATE('7/12/2022', 'MM/DD/YYYY'), 'Art Connects', TO_DATE('5/20/2022', 'MM/DD/YYYY'));
insert into Ticket values(108, 5.00, TO_DATE('8/3/2022', 'MM/DD/YYYY'), 'Spotlight: John Doe', TO_DATE('8/2/2022', 'MM/DD/YYYY'));
insert into Ticket values(109, 10.00, TO_DATE('8/3/2022', 'MM/DD/YYYY'), 'Spotlight: John Doe', TO_DATE('8/2/2022', 'MM/DD/YYYY'));
