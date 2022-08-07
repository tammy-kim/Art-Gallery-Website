drop table ArtOwner;
drop table Art3;

CREATE TABLE ArtOwner(
  OwnerID int PRIMARY KEY,
  FirstName VARCHAR2(50),
  LastName VARCHAR2(50),
  Email VARCHAR2(50),
  UNIQUE (Email)
);

CREATE TABLE Art3(
	IdentificationNumber integer PRIMARY KEY,
  Title VARCHAR2(50),
  Year integer,
  Price float(2) DEFAULT (0.00),
  ArtistID integer,
  OwnerID integer,
  RoomNumber integer DEFAULT (0),
  ExhibitionFee float(2) DEFAULT(0.00)
);
insert into Art3 values(1, 'Mona Lisa', 2020, 10.00, 1, 1, 0, 5.00);
insert into Art3 values(2, 'Mama Mia', 2020, 10.00, 1, 1, 0, 5.00);
insert into Art3 values(3, 'David', 2020, 10.00, 1, 2, 0, 5.00);

insert into ArtOwner values(1, 'Tom', 'Nook', 'TomNook@islandmail.com');
insert into ArtOwner values(2, 'Timmy', 'Nook', 'Timmy@islandmail.com');
insert into ArtOwner values(3, 'Tommy', 'Nook', 'Tommy@islandmail.com');
insert into ArtOwner values(4, 'Isabelle', NULL, 'Isabelle@islandmail.com');
insert into ArtOwner values(5, 'Blanca', 'Neko', 'blankaiscat@gmail.com');