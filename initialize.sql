drop table ArtOwner;

CREATE TABLE ArtOwner(
                OwnerID integer PRIMARY KEY,
                FirstName char(50),
                LastName char(50),
                Email char(50),
                UNIQUE (Email)
            );

insert into ArtOwner values(1, 'Tom', 'Nook', 'TomNook@islandmail.com');
insert into ArtOwner values(2, 'Timmy', 'Nook', 'Timmy@islandmail.com');
insert into ArtOwner values(3, 'Tommy', 'Nook', 'Tommy@islandmail.com');
insert into ArtOwner values(4, 'Isabelle', NULL, 'Isabelle@islandmail.com');
