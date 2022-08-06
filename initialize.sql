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
	Year integer PRIMARY KEY,
	ArtPeriod_Name char(50),
	FOREIGN KEY (ArtPeriod_Name) REFERENCES ArtPeriod
);

CREATE TABLE Art2(
	ArtistID integer PRIMARY KEY,
	ArtPeriod_Name char(50),
	FOREIGN KEY (ArtPeriod_Name) REFERENCES ArtPeriod
);

CREATE TABLE Art3(
	IdentificationNumber integer PRIMARY KEY,
    Title Char(50),
    Year integer,
    Price float(2) DEFAULT (0.00),
    ArtistID integer,
    OwnerID integer,
    RoomNumber integer DEFAULT (0),
    ExhibitionFee float(2) DEFAULT(0.00),
	FOREIGN KEY (ArtistID) REFERENCES Artist ON UPDATE CASCADE,
	FOREIGN KEY (OwnerID) REFERENCES ArtOwner ON DELETE CASCADE,
	FOREIGN KEY (RoomNumber) REFERENCES Room ON DELETE SET DEFAULT,
);

CREATE TABLE Painting(
	IdentificationNumber integer PRIMARY KEY,
	FOREIGN KEY (IdentificationNumber) REFERENCES Art3 
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Sculpture(
	IdentificationNumber integer PRIMARY KEY,
	FOREIGN KEY (IdentificationNumber) REFERENCES Art3
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE ArtOwner(
                OwnerID integer PRIMARY KEY,
                FirstName VARCHAR2(50),
                LastName VARCHAR2(50),
                Email VARCHAR2(50),
                UNIQUE (Email)
            );

CREATE TABLE Artist(
	ArtistID integer PRIMARY KEY,
	FirstName char(50),
	LastName char(50),
	DateOfBirth date,
	DateOfDeath date
);

CREATE TABLE Buyer_Purchase(
	FirstName char(50),
	LastName char(50),
	Email char(50) NOT NULL,
	Commission float(2) DEFAULT (0.00),
	IdentificationNumber integer,
	PRIMARY KEY (FirstName, LastName, IdentificationNumber),
	FOREIGN KEY (IdentificationNumber) REFERENCES Art3
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE Room(
	RoomNumber integer PRIMARY KEY,
	FloorNumber integer NOT NULL,
	ExhibitionName char(50),
	StartDate date
	FOREIGN KEY (ExhibitionName, StartDate) REFERENCES Exhibition (ExhibitionName, StartDate)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE Exhibition(
	ExhibitionName char(50),
	StartDate date NOT NULL,
	EndDate date NOT NULL,
	PRIMARY KEY (ExhibitionName, StartDate)
);

CREATE TABLE ArtPeriod(
	Name char(50) PRIMARY KEY,
	StartYear integer NOT NULL,
	EndYear integer NOT NULL,
);

CREATE TABLE Ticket(
	TicketNumber integer PRIMARY KEY,
	EntranceFee float(2) DEFAULT (5.00),
	ValidDate date,
	ExhibitionName char(50) NOT NULL,
	StartDate date NOT NULL,
	FOREIGN KEY (ExhibitionName, StartDate) REFERENCES Exhibition (ExhibitionName, StartDate)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);



insert into ArtOwner values(1, 'Tom', 'Nook', 'TomNook@islandmail.com');
insert into ArtOwner values(2, 'Timmy', 'Nook', 'Timmy@islandmail.com');
insert into ArtOwner values(3, 'Tommy', 'Nook', 'Tommy@islandmail.com');
insert into ArtOwner values(4, 'Isabelle', NULL, 'Isabelle@islandmail.com');

// comment comment comment
