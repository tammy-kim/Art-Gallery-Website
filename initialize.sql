CREATE TABLE ArtOwner(
                OwnerID integer PRIMARY KEY,
                FirstName char(50),
                LastName char(50),
                Email char(50),
                UNIQUE (Email)
            ),
INSERT ALL
            INTO ArtOwner VALUES (1, 'Tom', 'Nook', 'TomNook@islandmail.com')
            INTO ArtOwner VALUES (2, 'Timmy', 'Nook', 'Timmy@islandmail.com')
            INTO ArtOwner VALUES (3, 'Tommy', 'Nook', 'Tommy@islandmail.com')
            INTO ArtOwner VALUES (4, 'Isabelle', NULL, 'Isabelle@islandmail.com')
            SELECT 1 FROM DUAL