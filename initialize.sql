drop table Art1;
drop table Art2;
drop table Art3;
drop table ArtOwner;
drop table Painting;
drop table Sculpture;
drop table Artist;
drop table Buyer_Purchase;
drop table Room;
drop table Exhibition;
drop table ArtPeriod;
drop table Ticket;

CREATE TABLE Art1(
	Year int PRIMARY KEY,
	ArtPeriod_Name VARCHAR2(50),
	FOREIGN KEY (ArtPeriod_Name) REFERENCES ArtPeriod
);

grant select on Art1 to public;

CREATE TABLE Art2(
	ArtistID int PRIMARY KEY,
	ArtPeriod_Name VARCHAR2(50),
	FOREIGN KEY (ArtPeriod_Name) REFERENCES ArtPeriod
);

grant select on Art2 to public;

CREATE TABLE Art3(
	IdentificationNumber int PRIMARY KEY,
  Title VARCHAR2(50),
  Year int,
  Price int DEFAULT (0.00),
  ArtistID int,
  OwnerID int,
  RoomNumber int DEFAULT (0),
  ExhibitionFee int DEFAULT(0.00),
	FOREIGN KEY (ArtistID) REFERENCES Artist ON UPDATE CASCADE,
	FOREIGN KEY (OwnerID) REFERENCES ArtOwner ON DELETE CASCADE,
	FOREIGN KEY (RoomNumber) REFERENCES Room ON DELETE SET DEFAULT,
);

grant select on Art3 to public;

CREATE TABLE Painting(
	IdentificationNumber int PRIMARY KEY,
	FOREIGN KEY (IdentificationNumber) REFERENCES Art3
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

grant select on Painting to public;

CREATE TABLE Sculpture(
	IdentificationNumber int PRIMARY KEY,
	FOREIGN KEY (IdentificationNumber) REFERENCES Art3
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

grant select on Sculpture to public;

CREATE TABLE ArtOwner(
  OwnerID int PRIMARY KEY,
  FirstName VARCHAR2(50),
  LastName VARCHAR2(50),
  Email VARCHAR2(50),
  UNIQUE (Email)
);

grant select on ArtOwner to public;

CREATE TABLE Artist(
	ArtistID int PRIMARY KEY,
	FirstName VARCHAR2(50),
	LastName VARCHAR2(50),
	DateOfBirth date,
	DateOfDeath date
);

grant select on Artist to public;

CREATE TABLE Buyer_Purchase(
	FirstName VARCHAR2(50),
	LastName VARCHAR2(50),
	Email VARCHAR2(50) NOT NULL,
	Commission int DEFAULT (0.00),
	IdentificationNumber int,
	PRIMARY KEY (FirstName, LastName, IdentificationNumber),
	FOREIGN KEY (IdentificationNumber) REFERENCES Art3
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

grant select on Buyer_Purchase to public;

CREATE TABLE Room(
	RoomNumber int PRIMARY KEY,
	FloorNumber int NOT NULL,
	ExhibitionName VARCHAR2(50),
	StartDate date
	FOREIGN KEY (ExhibitionName, StartDate) REFERENCES Exhibition (ExhibitionName, StartDate)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

grant select on Room to public;

CREATE TABLE Exhibition(
	ExhibitionName VARCHAR2(50),
	StartDate date NOT NULL,
	EndDate date NOT NULL,
	PRIMARY KEY (ExhibitionName, StartDate)
);

grant select on Exhibition to public;

CREATE TABLE ArtPeriod(
	Name VARCHAR2(50) PRIMARY KEY,
	StartYear int NOT NULL,
	EndYear int NOT NULL,
);

grant select on ArtPeriod to public;

CREATE TABLE Ticket(
	TicketNumber int PRIMARY KEY,
	EntranceFee int DEFAULT (5.00),
	ValidDate date,
	ExhibitionName VARCHAR2(50) NOT NULL,
	StartDate date NOT NULL,
	FOREIGN KEY (ExhibitionName, StartDate) REFERENCES Exhibition (ExhibitionName, StartDate)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

grant select on Ticket to public;

insert into Art1 values(1150, 'Gothic');
insert into Art1 values(1160, 'Gothic');
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

insert into Art3 values(10001, 'Trees', 1150, 7000.00, 2, 1, 301);
insert into Art3 values(10002, 'Sunflowers', 1160, 8000.99, 2, 1, 301);
insert into Art3 values(10003, 'wonderful farm', 1980, 100000.00, 1, 2, 401);
insert into Art3 values(10007, 'Malicious Honesty', 1981, 200000.00, 1, 2, 402);
insert into Art3 values(20041, 'Clock', 1860, 345000.00, 3, 3, 301);
insert into Art3 values(20135, 'dramatic mirror', 1865, 4500.89, 3, 3, 301);
insert into Art3 values(26951, 'The Starry Night', 1744, 5000.00, 4, 4, 301);
insert into Art3 values(30019, 'Mona Lisa', 1744, 7000000.00, 4, 4, 301);
insert into Art3 values(32107, 'Display of Patience', 1459, 100000.00, 5, 5, 101);
insert into Art3 values(35555, 'Lesson', 1470, 150000.00, 5, 5, 101);

insert into Painting values(10001);
insert into Painting values(10002);
insert into Painting values(10007);
insert into Painting values(20135);
insert into Painting values(30019);

insert into Sculpture values(10003);
insert into Sculpture values(20041);
insert into Sculpture values(26951);
insert into Sculpture values(32107);
insert into Sculpture values(35555);

insert into ArtOwner values(1, 'Tom', 'Nook', 'TomNook@islandmail.com');
insert into ArtOwner values(2, 'Timmy', 'Nook', 'Timmy@islandmail.com');
insert into ArtOwner values(3, 'Tommy', 'Nook', 'Tommy@islandmail.com');
insert into ArtOwner values(4, 'Isabelle', NULL, 'Isabelle@islandmail.com');
insert into ArtOwner values(5, 'Blanca', 'Neko', 'blankaiscat@gmail.com');

insert into Artist(1, 'John', 'Doe', '4/5/1955', NULL);
insert into Artist(2, 'Thomas', 'Nook', '3/1/1100', '10/15/1200');
insert into Artist(3, 'Emily', 'Carr', '12/13/1820', '3/2/1880');
insert into Artist(4, 'Bernard', 'Brown', '5/24/1701', '12/12/1770');
insert into Artist(5, 'Julia', 'Fox', '1/1/1410', '2/4/1460');

insert into Buyer_Purchase('Leonor', 'Coffey', 'leoisawesome@gmail.com', 5000.99, 10001);
insert into Buyer_Purchase('Estelle', 'Leach', 'estelle.leach@gmail.com', 100000.99, 10007);
insert into Buyer_Purchase('Traci', 'Bolton', 'traciBolts@gmail.com', 4200000.50, 30019);
insert into Buyer_Purchase('Lynn', 'Horne', 'lynnhorne234@gmail.com', 20000.99, 32107);
insert into Buyer_Purchase('Jessie', 'Good', 'jessielovesart@gmail.com', 55000.49, 35555);

insert into Room(101, 1, 'Kids Take Over', '4/1/2022');
insert into Room(201, 2, 'In Memory of Andrew Green', '5/20/2022');
insert into Room(301, 3, 'Art Connects', '5/20/2022');
insert into Room(401, 4, 'Spotlight: John Doe', '8/2/2022');
insert into Room(402, 4, 'Spotlight: John Doe', '8/2/2022');

insert into Exhibition('Kids Take Over', '4/1/2022', '8/31/2022');
insert into Exhibition('In Memory of Andrew Green', '5/20/2022', '7/15/2022');
insert into Exhibition('Art Connects', '5/20/2022', '7/31/2022');
insert into Exhibition('Canadian Women Artists in Modern Moment', '6/1/2022', '8/31/2022');
insert into Exhibition('Spotlight: John Doe', '8/2/2022', '12/1/2022');

insert into ArtPeriod('Gothic', 1140, 1600);
insert into ArtPeriod('Renaissance', 1400, 1600);
insert into ArtPeriod('Rococo', 1720, 1760);
insert into ArtPeriod('Realism', 1840, 1870);
insert into ArtPeriod('Contemporary Art', 1978, NULL);

insert into Ticket(100, 5.00, '6/10/2022', 'Kids Take Over', '4/1/2022');
insert into Ticket(101, 5.00, '6/14/2022', 'Kids Take Over', '4/1/2022');
insert into Ticket(103, 5.00, '6/22/2022', 'Kids Take Over', '4/1/2022');
insert into Ticket(104, 5.00, '7/1/2022', 'Kids Take Over', '4/1/2022');
insert into Ticket(105, 10.00, '7/5/2022', 'Art Connects', '5/20/2022');
insert into Ticket(106, 10.00, '7/11/2022', 'Art Connects', '5/20/2022');
insert into Ticket(107, 7.00, '7/12/2022', 'Art Connects', '5/20/2022');
insert into Ticket(108, 5.00, '8/3/2022', 'Spotlight: John Doe', '8/2/2022');
insert into Ticket(109, 10.00, '8/3/2022', 'Spotlight: John Doe', '8/2/2022');
