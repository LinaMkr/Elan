CREATE TABLE Utilisateur(
  email text PRIMARY KEY,
  nom text,
  prenom text,
  telephone text
);

CREATE TABLE Client(
  emailC text PRIMARY KEY,
  adresse text,
  entreprise text,
  motDePasse text,
  FOREIGN KEY (emailC) REFERENCES Utilisateur(email)
);

CREATE TABLE Admin(
  emailA text PRIMARY KEY,
  motDePasse text,
  FOREIGN KEY (emailA) REFERENCES Utilisateur(email)
);

CREATE TABLE Article(
  titre text PRIMARY KEY,
  description text,
  emailA text,
  FOREIGN KEY (emailA) REFERENCES Admin(emailA)
);

CREATE TABLE NewsLetter(
  emailN text PRIMARY KEY,
  FOREIGN KEY (emailN) REFERENCES Utilisateur(email)
);

CREATE TABLE Expert(
  nom text,
  prenom text,
  nbJoursDispo integer,
  prix real,
  PRIMARY KEY(nom,prenom)
);

CREATE TABLE TypeReservation(
  type text,
  nom text,
  prix real,
  description text,
  PRIMARY KEY(type,nom)
);

CREATE TABLE TypeExperts(
  nomExpert text,
  prenomExpert text,
  type text,
  nomType text,
  FOREIGN KEY (nomExpert,prenomExpert) REFERENCES Expert(nom,prenom),
  FOREIGN KEY (type,nomType) REFERENCES TypeReservation(type,nom),
  PRIMARY KEY(nomExpert,prenomExpert,type,nomType)
);

CREATE TABLE Lieux(
  nomLocal text PRIMARY KEY,
  surface integer,
  nb_place integer,
  infos TEXT,
  prix real
);

CREATE TABLE Reservation(
  idR SERIAL PRIMARY KEY,
  email text,
  nomLocal text,
  type text,
  nom text,
  dateDeb timestamp,
  dateFin timestamp check (dateFin >= dateDeb),
  nb_pers integer,
  placeRest integer,
  prixT real,
  FOREIGN KEY(email) REFERENCES Utilisateur(email),
  FOREIGN KEY(nomLocal) REFERENCES Lieux(nomLocal),
  FOREIGN KEY(type,nom) REFERENCES TypeReservation(type,nom)
);

CREATE TABLE ClientReservation(
  idR integer,
  emailC text,
  FOREIGN KEY(emailC) REFERENCES Client(emailC),
  FOREIGN KEY(idR) REFERENCES Reservation(idR),
  PRIMARY KEY(idR,emailC)
);

CREATE TABLE Paiement(
  iDR integer,
  emailC text,
  prix real,
  somme real check (somme <= prix),
  restant real check (restant >= 0.0),
  FOREIGN KEY(iDR) REFERENCES Reservation(iDR),
  FOREIGN KEY(emailC) REFERENCES Client(emailC),
  PRIMARY KEY(iDR,emailC)
);

CREATE TABLE Equipement(
  nomEquip text PRIMARY KEY,
  nb_Disponible integer check (nb_Disponible > 0),
  prixUnite real
);

CREATE TABLE LieuxEquipements(
  nomLocal text,
  nomEquip text,
  FOREIGN KEY(nomLocal) REFERENCES Lieux(nomLocal),
  FOREIGN KEY(nomEquip) REFERENCES Equipement(nomEquip),
  PRIMARY KEY(nomLocal,nomEquip)
);

CREATE TABLE Service(
  intitule text PRIMARY KEY,
  prix real
);

CREATE TABLE ReservationServices(
  idR integer,
  service text,
  PRIMARY KEY(idR,service),
  FOREIGN KEY(idR) REFERENCES Reservation(idR),
  FOREIGN KEY(service) REFERENCES Service(intitule)
);


CREATE TABLE Prestataire(
  nom text PRIMARY KEY,
  prix real
);

CREATE TABLE ServicePrestataires(
  service text,
  prestataire text,
  FOREIGN KEY(service) REFERENCES Service(intitule),
  FOREIGN KEY(prestataire) REFERENCES Prestataire(nom),
  PRIMARY KEY(service,prestataire)
);

CREATE TABLE Statistiques(
  dateStockage date PRIMARY KEY,
  nbLocations integer,
  nbFormations integer,
  nbEvenements integer,
  nbCoworkings integer,
  nbClients integer,
  benefices real
);

/*
Client (emailC, nom, prénom, téléphone, entreprise, mdp, admin) // Admin = boolean
Lieux (nomLocal, surface, nb_place, info_complementaires)
Réservation (idR, #emailC, #nomLocal,dateDeb, dateFin, transport, nb_pers, prix)
Prix (service, prix) // Le service peut être un lieu
Service_Choisi (#idR, #service)
NewsLetter (emailN)
*/
