drop table ArtOwner;

CREATE TABLE ArtOwner(
                OwnerID integer PRIMARY KEY,
                FirstName VARCHAR2(50),
                LastName VARCHAR2(50),
                Email VARCHAR2(50),
                UNIQUE (Email)
            );

insert into ArtOwner values(1, 'Tom', 'Nook', 'TomNook@islandmail.com');
insert into ArtOwner values(2, 'Timmy', 'Nook', 'Timmy@islandmail.com');
insert into ArtOwner values(3, 'Tommy', 'Nook', 'Tommy@islandmail.com');
insert into ArtOwner values(4, 'Isabelle', NULL, 'Isabelle@islandmail.com');
