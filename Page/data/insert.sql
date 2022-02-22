INSERT INTO TypeReservation VALUES ('location','',0.0,'');
INSERT INTO TypeReservation VALUES ('CoWorking','',20,'');

-- Création d'un Administrateur avec le mot de passe : Motdepasse1
Select creerAdmin('admin@gmail.com','Garcia','Jean','0123456789','787d21798c70d06fa4816f05f775d614');

-- Création d'un client lambda
SELECT creerClient('utilisateur@gmail.com','Dupont','Michel','0123456789',' 2, Place Doyen Gosse, 38031 Grenoble','IUT2','b948536d0ffd434a49c0c40ab27d8a36');

-- Création d'un article par défaut
SELECT gestionArticle('Site web','Bienvenue sur le nouveau site web de Elan. Vous pouvez créer, réserver, et rejoindre des reservations !','admin@gmail.com','insert');

--Création d'un lieu par défaut
INSERT INTO Lieux VALUES ('Bureau',13,2,'Bureau confortable pour travailler tout seul ou à plusieurs',100);

-- Création de la session co-working du jour
SELECT coWorking('admin@gmail.com','Bureau');
SELECT calculPrixTotal(1);
