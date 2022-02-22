/* -----------PARTIE METHODES SQL---------------*/


/* Test le chevauchement des dates de réservation créer avec celles déjà existantes */
CREATE or Replace function valideDates(dateD timestamp, dateF timestamp, lieu TEXT) returns boolean as $$
    BEGIN
        /* selectionne les réservations de même lieu que lieu et qui ont un chevauchement avec DateD ou DateF */
        perform * from reservation where nomLocal = lieu and not(dateD > dateFin or dateF < dateDeb);
          if not found THEN
            return true;
          else return false;
          end if;
    END;
$$ LANGUAGE plpgsql;



/* -----------PARTIE CREATION UTILISATEUR CLIENT ADMININISTRATEUR---------------*/


--Créer un utilisateur à partir d'un email emailU non connu de la base
Create or Replace function creerUtilisateur(emailU text, nomU text,prenomU text, telU text) returns integer as $$
  BEGIN
    --Vérification que l'email n'est pas déjà présent dans la base
    Perform * from Utilisateur where email = emailU;
    if found THEN
      --Code d'erreur pour la présence de l'email dans la table Utilisateur
      return -1;
    else
      Insert into Utilisateur values(emailU,nomU,prenomU,telU);
      return 1;
    end if;
    EXCEPTION WHEN OTHERS THEN
      -- -2 correspond au numéro d'erreur universelle du site signalant une erreur inconnu
      return -2;
  END;
$$ LANGUAGE plpgsql;


--Créer un client à partir d'un email newEmail connu de la base dans la table Utilisateur sinon création au préalable de l'utilisateur
Create or replace function creerClient(newEmail TEXT,newNom TEXT,newPrenom TEXT,newTel TEXT,newAdresse TEXT,newEnt TEXT,newMdp TEXT) returns integer as $$
    BEGIN
      --Vérification que l'utilisateur n'est pas un administrateur
      Perform * from Admin where emailA = newEmail;

      if found THEN
        --Code d'erreur pour la présence de l'email dans la table Admin
        return -1;
      else

        --Vérification que l'émail n'est pas déjà présent dans la table client
        Perform * from Client where emailC = newEmail;

        if found then
          --Code d'erreur pour la présence de l'email dans la table client
          return -3;
        else

          --Vérification que l'émail soit déjà présent dans la table Utilisateur
          perform * from Utilisateur  where email = newEmail;

          --Insertion des informations email nom prenom tel dans la table Utilisateur
          if not found THEN
            Perform creerUtilisateur(newEmail,newNom,newPrenom,newTel);
          end if;

          --{l'email est présent dans la table Utilisateur} => Insertion dans la table client des informations email adresse entreprise motDePasse
          Insert into Client values (newEmail,newAdresse,newEnt,newMdp);
          --Si le client était déjà un utilisateur, on récupère les dernières versions des valeurs de nom prenom et telephone
          Update Utilisateur set nom = newNom, prenom = newPrenom, telephone = newTel where email = newEmail;
          --Le client à été créer et il est aussi utilisateur
          return 1;
        end if;

      end if;
      EXCEPTION WHEN OTHERS THEN
        -- -2 correspond au numéro d'erreur universelle du site signalant une erreur inconnu
        return -2;
    END;
$$ LANGUAGE plpgsql;


--Créer un administrateur à partir d'un email newEmail connu de la base dans la table Utilisateur sinon création au préalable de l'utilisateur
Create or replace function creerAdmin(newEmail TEXT,newNom TEXT,newPrenom TEXT,newTel TEXT,newMdp TEXT) returns integer as $$
    BEGIN

      --On s'assure que l'email n'appartient pas à un client
      Perform * from Client where emailC = newEmail;

      if found THEN
        return -1;
      else

        --Vérification que l'email n'appartient pas déjà à un administrateur
        Perform * from Admin where emailA = newEmail;

        if found then
          return -3;
        else

          --Vérification que l'email est bien présent dans la table utilisateur
          perform * from Utilisateur where email = newEmail;

          if not found THEN
            --Insertion dans la table utilisateur des valeurs email nom prenom tel
            Perform creerUtilisateur(newEmail,newNom,newPrenom,newTel);
          end if;

          --Insertion dans la table admin des valeurs email motDePasse
          Insert into Admin values (newEmail,newMdp);
          return 1;
        end if;

      end if;

      EXCEPTION WHEN OTHERS THEN
        -- -2 correspond au numéro d'erreur universelle du site signalant une erreur inconnu
        return -2;
    END;
$$ LANGUAGE plpgsql;


/* -----------PARTIE OBTENTION DES N-UPLETS UTILISATEUR CLIENT ADMININISTRATEUR POUR CREER DES OBJETS PHP---------------*/


--Méthodes d'obtention de l'utilisateur emailUtil pour objet PHP
Create or replace function getUtilisateur(emailUtil text) returns TABLE (emailC text, nom text, prenom text, tel text) as $$
  BEGIN
    return query select * from Utilisateur where email = emailUtil;
  END;
$$ LANGUAGE plpgsql;


--Méthodes d'obtention du client emailCLient pour objet PHP
Create or replace function getClient(emailClient text) returns TABLE (emailC text, nom text, prenom text, tel text, adresse text, entreprise text, motDePasse text) as $$
  BEGIN
    --On joint les informations contenus dans les tables utilisateur et client pour le même email emailClient
    return query select U.email,U.nom,U.prenom,U.telephone,C.adresse,C.entreprise,C.motDePasse from Utilisateur U inner join client C on (U.email = C.emailC) where U.email = emailClient;
  END;
$$ LANGUAGE plpgsql;


--Méthodes d'obtention de l'administrateur emailAdmin pour objet PHP
Create or replace function getAdmin(emailAdmin text) returns TABLE (emailA text, nom text, prenom text,telephone text, motDePasse text) as $$
  BEGIN
    --On joint les informations contenus dans les tables utilisateur et admin pour le même email emailAdmin
    return query select U.email,U.nom,U.prenom,U.telephone,A.motDePasse from Utilisateur U inner join Admin A on (U.email = A.emailA) where U.email = emailAdmin;
  END;
$$ LANGUAGE plpgsql;


/* -----------PARTIE CREER ET REJOINDRE UNE RESERVATION AVEC FONCTIONS ASSOCIEES---------------*/


--création d'une réservation pour un utilisateur (client ou administrateur), renvoie l'idR de la réservation ou un numéro d'erreur en fonction du problème
CREATE or Replace function creerReservation(emailOrga TEXT,nomLieu TEXT,type TEXT,nom TEXT, nb_part integer, dateD timestamp, dateF timestamp) returns integer as $$
    Declare
        --On stock le nombre de places maximal pour le lieu nomLieu
        nb numeric;
        --Record nécéssaire pour retenir la valeur de idR lors de l'insertion de la réservation qui se créer automatiquement (SERIAL)
        save record;
    BEGIN
        --On vérifie que le nombre de participants ne dépasse pas la capacité d'accueil du lieu
        Select nb_place from Lieux where nomLocal = nomLieu into nb;

        --On compte l'organisateur de la réservation s'il s'agit d'un client en retirant 1 place au lieu

        if nb < nb_part then
            -- -1 correspond au manque de place dans le lieu demandé
            return -1;
        end if;

        --On insére la nouvelle réservation dans la table réservation sans préciser l'idR qui se créer automatiquement
        -- et le prix qui dépend de variables encore non initialisées
        --On récupère l'ensemble des valeurs (avec idR) dans le record save
        Insert into Reservation(email,nomLocal,type,nom,dateDeb,dateFin,nb_pers,placeRest) values
        (emailOrga,nomLieu,type,nom,dateD,dateF,nb_part,nb-nb_part) returning * into save;

        --Si l'organisateur n'est pas un administrateur alors :
        --On ajoute l'organisateur à la table ClientReservation
        --On ajoute le prix de la réservation à la charge de l'organisateur dans la table Paiement
        if not estAdmin(emailOrga) then
          Insert into ClientReservation values (save.idr,save.email);
          Insert into Paiement values (save.idr,save.email,0.0,0.0,0.0);
        end if;
        --On peut enfin renvoyer l'idR de la réservation
        return save.idR;
        EXCEPTION WHEN OTHERS THEN
        --     -- -2 correspond au numéro d'erreur universelle du site signalant une erreur inconnu
             return -2;
    END;
$$ LANGUAGE plpgsql;


--Ajoute le client emailClient dans la réservation idReserv
CREATE or Replace function rejoindreReservation(emailClient TEXT, idReserv integer) returns integer as $$
    Declare
        --On retient le nombre de places restantes et le prix d'une place pour la réservation présent dans la table réservation au n-uplet prixT
        nb numeric;
    BEGIN
      --On vérifie que l'email n'est pas déjà inscrit à la réservation
      Perform * from ClientReservation where emailC = emailCLient and idR = idReserv;

      if found THEN
        --Code d'erreur correspondant à la présence du client parmi les inscrits à la réservation
        return -1;
      end if;

      --On récupère le nombre de places restantes
      Select placeRest into nb from reservation where idR = idReserv;
      if(placeRest = 0) THEN
        --Code d'erreur pour manque de place
        return -3;
      end if;

      --On récupère le prix d'un place
      select prixT from Reservation where idr = idReserv into nb;
      --On insère le client parmi les participants à la réservation
      Insert into ClientReservation values (idReserv,emailClient);
      --On ajoute le prix aux paiement du client
      Insert into Paiement values(idReserv, emailClient,nb,0.0,nb);
      --On retire 1 place aux nombres de places restantes de la réservation
      Update Reservation set placeRest = placeRest-1 where idr = idReserv;
      --Code de réussite
      return 1;

      EXCEPTION WHEN OTHERS THEN
        -- -2 correspond au numéro d'erreur universelle du site signalant une erreur inconnu
        return -2;
    END;
$$ LANGUAGE plpgsql;


--Fonction éxécuté automatiquement qui réserve du co-working si un lieu est disponible 24h après la date actuelle
Create or Replace function coWorking (emailOrga TEXT,nomLieu TEXT) returns integer as $$
  Declare
    --On stock les dates et horaires pour le co-working ainsi que la date de demain
    dated timestamp;
    datef timestamp;
    nextDay timestamp;
  BEGIN
    -- nextDay = demain
    nextDay = current_date + interval '1' DAY;
    --On vérifie qu'il n'y a pas déjà une réservation de faite pour le landemain
    Perform * from reservation where dateDeb = nextDay;

    if found THEN
      --Code d'erreur pour présence d'une réservation
      return -1;
    else
      --dated et datef prennent les valeurs de début et fin du co working (demain entre 9h30 et 17h30)
      dated = nextDay + interval '9' HOUR + interval '30' minute;
      datef = nextDay + interval '17' HOUR + interval '30' minute;
      --On créer le co-working grâce à la méthode creerReservation et on renvoie l'idR ou un code d'erreur
      return creerReservation(emailOrga,nomLieu,'CoWorking','',0,dated, datef);

    end if;
    EXCEPTION WHEN OTHERS THEN
      -- -2 correspond au numéro d'erreur universelle du site signalant une erreur inconnu
      return -2;
  END;
$$ LANGUAGE plpgsql;


--Fonction de calcul du prix Total pour une réservation
Create or Replace function calculPrixTotal(idResa integer) returns integer as $$
  Declare
    --Sauvegarde de la réservation
    resa record;
    --Sauvegarde et incrémentation du total
    total real;
    --Parcours du prix des composants de la réservation
    prixRec record;
    --Parcours du prix de chaque prestataires nécéssaires
    prestaRec record;
    --Calcul du nombres de jours de la réservation
    nbJours integer;
  BEGIN

    --Initialisation du prix
    total = 0.0;
    --Récupération des n-uplets de la réservation dans le record
    select * from reservation where idR = idResa into resa;
    --On vérifie si l'organisateur est un administrateur
    perform * from admin where emailA = resa.email;
    if found THEN

      --Le prix de la réservation représentera le prix d'une place pour qu'un client assiste à la formation ou à l'évenement
      total = (select prix from typeReservation where type = resa.type and nom = resa.nom);
      --Boucle sur le prix des experts présents au type de réservation de la réservation idResa
      for prixRec in select prix from expert, TypeExperts where nom = nomExpert and prenom = prenomExpert and type = resa.type and nom = resa.nom loop
        total = total + prixRec.prix;
      end loop;

    else

      --incrémentation du prix total avec le prix du lieu
      select prix into total from Lieux where nomLocal = resa.nomLocal;
      --Si la réservation se déroule sur 1 journée
      if(extract(day from resa.dateDeb) = extract(day from resa.dateFin)) THEN
        --Si la réservation ne dure que le matin ou l'après midi
        if(extract(HOUR from resa.dateFin) = 12 or extract(HOUR from resa.dateDeb) = 14) THEN
          total = total / 2;
        end if;
      --La réservation dure plusieurs jours
      else
        --On récupère la durée en jours
        nbJours = cast(resa.dateFin as DATE) - cast(resa.dateDeb as DATE);
        total = total * nbJours;
      end if;

      --Boucle sur les services demandés pour la réservation idResa
      for prixRec in select intitule,prix from Service where intitule in (select service from ReservationServices where idR = idResa) loop
        --On multiplie le prix du service par le nombres de participants
        total = total + (prixRec.prix * resa.nb_pers);

        --Boucle sur le prix des prestataires liés aux services demandés
        for prestaRec in select prix from Prestataire where nom in (select prestataire from ServicePrestataires where service = prixRec.intitule) loop
          total = total + prestaRec.prix;
        end loop;

      end loop;

      --Boucle sur le prix à l'unité de chaque équipement présent dans le lieu demandé pour la réservation idResa
      for prixRec in select prixUnite from Equipement where nomEquip in (select nomEquip from LieuxEquipements where nomLocal = resa.nomLocal) loop
        total = total + prixRec.prixUnite;
      end loop;

    end if;

    --Modification du prix de la réservation pour sauvegarder le bon prix
    update reservation set prixT = total where idR = idResa;
    --Modification du montant à payer et du restant pour la réservation idResa
    update paiement set prix = total, restant = total where idR = idResa;

    return 1;

    EXCEPTION WHEN OTHERS THEN
      -- -2 correspond au numéro d'erreur universelle du site signalant une erreur inconnu
      return -2;
  END;
$$ LANGUAGE plpgsql;

--Ajout d'un service service pour la réservation idResa
CREATE or Replace function ajoutService(idResa integer,service text) returns integer as $$
  BEGIN
      --Association du service à la réservation via la table ReservationServices
      insert into ReservationServices values(idResa,service);
      --Code de réussite
      return 1;

    EXCEPTION WHEN OTHERS THEN
      -- -2 correspond au numéro d'erreur universelle du site signalant une erreur inconnu
      return -2;
  END;
$$ LANGUAGE plpgsql;

--Fonction de paiement d'une réservation pour un client (organisée ou rejointe)
Create or replace function payee(idReserv integer,email TEXT,sum real) returns integer as $$
    BEGIN
        --On augmente la somme versée par le client et diminue le restant à payée
        Update paiement set somme = somme + sum, restant = restant - sum where idr = idReserv and emailC = email;
        return 1;
        EXCEPTION WHEN OTHERS THEN
            return -2;
    END;
$$ LANGUAGE plpgsql;


/* -----------PARTIE GETTERS RESERVATIONS---------------*/

--Renvoie la liste des services demandés pour la réservation idResa
Create or Replace function getServicesResa(idResa integer) returns TABLE(serviceResa text, prixService real) as $$
  BEGIN
    return query select * from Service where intitule in (select service from ReservationServices where idR = idResa);
  END;
$$ LANGUAGE plpgsql;

/* Retourne un tableau de réservations pour l'agenda*/
CREATE or Replace function getReservations() returns TABLE (typeResa text, nomResa text, dateD timestamp, dateF timestamp) as $$
    BEGIN
        return query Select type,nom,dateDeb,dateFin from Reservation;
    END;
$$ LANGUAGE plpgsql;


/* Retourne un tableau de mail ayant fait une réservation d'identifiant idReserv*/
CREATE or Replace function getClientsResa(idReserv integer)returns TABLE (mail TEXT) as $$
    BEGIN
        return query Select emailC from ClientReservation where idReserv = idR ;
    END;
$$ LANGUAGE plpgsql;

--Retourne la liste des emails inscrits à une réservation se déroulant dans 2 jours
Create or replace function getEmailsRappel() returns TABLE (emailRappel text) as $$
  BEGIN
    return query select emailC from ClientReservation where idR in (select idr from reservation where dateDeb = current_date + interval '2' DAY);
  END;
$$ LANGUAGE plpgsql;

--Retourne la liste des emails ayant participés à une réservation 1 jour avant pour une enquête de satisfaction
Create or replace function getEmailsRemerciement() returns TABLE (emailRappel text) as $$
  BEGIN
    return query select emailC from ClientReservation where idR in (select idr from reservation where dateDeb = current_date - interval '1' DAY);
  END;
$$ LANGUAGE plpgsql;

/* -----------PARTIE GESTION ADMININISTRATEUR---------------*/

  --Ajoute, modifie ou supprime un article
  Create or Replace function gestionArticle(titreArt text, descArt real,auteurArt text, action text) returns integer as $$
  BEGIN
    --Action correspond à la valeur du bouton préssé par l'administrateur
    if action = 'insert' THEN
      --Vérification de la présence de l'article dans la table
      perform * from article where titre = titreArt;
      if found THEN
        return -1;
      end if;
      --Création d'un nouvel article
      insert into Article values(titreArt,descArt,auteurArt);
      return 1;
    elsif action = 'update' THEN
      --Modification de la description de l'article
      update Article set description = descArt where titre = titreArt;
      return 1;
    else
      --Suppression de l'article dans la table article
      Delete from Article where titre = titreArt;
      return 1;
    end if;
    EXCEPTION WHEN OTHERS THEN
        -- -2 correspond au numéro d'erreur universelle du site signalant une erreur inconnu
        return -2;
  END;
$$ LANGUAGE plpgsql;


--Ajoute, modifie ou supprime un service
Create or replace function gestionService(intituleService text, prixService real, action text) returns integer as $$
  BEGIN
    --Action correspond à la valeur du bouton préssé par l'administrateur
    if action = 'insert' then
      --Vérification de la présence du service dans la table
      perform * from service where intitule = intituleService;
      if found THEN
        return -1;
      end if;
      --Création d'un nouveau service
      insert into Service values(intituleservice,prixService);
      --Code de réussite
      return 1;

    elsif action = 'update' THEN
      --Modification du prix pour le service intituleService
      update service set prix = prixService where intitule = intituleService;
      return 1;
    --Le bouton delete a été préssé
    else
      --Vérification de la présence du service dans des réservations prévues
      Perform * from ReservationServices where service = intituleService and idR in (select idr from reservation where dateDeb >= current_date);

      if found then
        --Code d'erreur pour la présence du service dans une réservation
        return -3;
      --Le service n'est demandé nulle part
      else
        --Suppression du lien entre le service et les réservation (aucune réservation futur ne demande le service)
        delete from ReservationServices where service = intituleService;
        --Supression du lien entre des prestataires et le service supprimé dans la table ServicePrestataires
        delete from ServicePrestataires where service = intituleService;
        --Supression du service
        delete from service where intitule = intituleService;
        return 1;

      end if;

    end if;

    EXCEPTION WHEN OTHERS THEN
      -- -2 correspond au numéro d'erreur universelle du site signalant une erreur inconnu
      return -2;
  END;
$$ LANGUAGE plpgsql;




--Ajoute, modifie ou supprime un prestataire
Create or Replace function gestionPrestataire(nomPresta text, prixPresta real, action text) returns integer as $$
  BEGIN
    --Action correspond à la valeur du bouton préssé par l'administrateur
    if action = 'insert' THEN
      --Vérification de la présence du prestataire dans la table
      perform * from prestataire where nom = nomPresta;
      if found THEN
        return -1;
      end if;
      --Création d'un nouveau prestataire
      insert into Prestataire values(nomPresta,prixPresta);
      return 1;
    elsif action = 'update' THEN
      --Modification du prix du prestataire
      update Prestataire set prix = prixPresta where nom = nomPresta;
      return 1;
    else
      --Suppression du prestataire dans la table prestataire et les tables de jointure
      Delete from ServicePrestataires where prestataire = nomPresta;
      Delete from prestataire where nom = nomPresta;
      return 1;
    end if;
    EXCEPTION WHEN OTHERS THEN
        -- -2 correspond au numéro d'erreur universelle du site signalant une erreur inconnu
        return -2;
  END;
$$ LANGUAGE plpgsql;


--Ajoute, modifie ou supprime un type formation
Create or Replace function gestionFormation(nomForma text, prixForma real, descriptionForma text, action text) returns integer as $$
  BEGIN
    --Action correspond à la valeur du bouton préssé par l'administrateur
    if action = 'insert' then
      --Vérification de la formation dans la table
      perform * from typeReservation where type = 'formation' and nom = nomForma;
      if found THEN
        return -1;
      end if;
      --Création d'une formation pour les réservations
      Insert into TypeReservation values('formation',nomForma,prixForma,descriptionForma);
      return 1;
    elsif action = 'update' THEN
      --Modification du prix et de la description de la formation
      update TypeReservation set prix = prixForma , description = descriptionForma where type = 'formation' and nom = nomForma;
      return 1;
    else
      --Vérification de la présence de la formation dans les réservations
      Perform * from reservation where type = 'formation' and nom = nomForma;
      if found THEN
        --Code d'erreur pour la présence de la formation dans les réservations
        return -3;
      else
        --Suppression de la formation dans la table typeReservation et dans ses jointures
        Delete from TypeExperts where type = 'formation' and nomType = nomForma;
        Delete from TypeReservation where type = 'formation' and nom = nomForma;
        return 1;
      end if;
    end if;
    EXCEPTION WHEN OTHERS THEN
        -- -2 correspond au numéro d'erreur universelle du site signalant une erreur inconnu
        return -2;
  END;
$$ LANGUAGE plpgsql;


--Ajoute, modifie ou supprime un type evenement
Create or Replace function gestionEvenement(nomEvent text, prixEvent real, descriptionEvent text, action text) returns integer as $$
  BEGIN
    --Action correspond à la valeur du bouton préssé par l'administrateur
    if action = 'insert' then
      --Vérification de l'evenement dans la table
      perform * from typeReservation where type = 'evenement' and nom = nomEvent;
      if found THEN
        return -1;
      end if;
      --Création d'un évenement pour les réservations
      Insert into TypeReservation values('evenement',nomEvent,prixEvent,descriptionEvent);
      return 1;
    elsif action = 'update' THEN
      --Modification du prix et de la description de l'évenement
      update TypeReservation set prix = prixEvent, description = descriptionEvent where type = 'evenement' and nom = nomEvent;
      return 1;
    else
      --Vérification de la présence de l'évenement dans les réservations
      Perform * from reservation where type = 'evenement' and nom = nomEvent;
      if found THEN
        --Code d'erreur pour la présence de l'évenement dans les réservations
        return -3;
      else
        --Suppression de l'évenement dans la table typeReservation et dans ses jointures
        delete from TypeExperts where type = 'evenement' and nomType = nomEvent;
        Delete from TypeReservation where type = 'evenement' and nom = nomEvent;
        return 1;
      end if;
    end if;
    EXCEPTION WHEN OTHERS THEN
        -- -2 correspond au numéro d'erreur universelle du site signalant une erreur inconnu
        return -2;
  END;
$$ LANGUAGE plpgsql;


--Ajoute, modifie ou supprime un expert
Create or Replace function gestionExpert(nomExp text, prenomExp text, joursDispoExp integer, prixExp real, action text) returns integer as $$
  BEGIN

  if action = 'insert' THEN
    --Vérification de l'expert dans la table
    perform * from expert where nom = nomExp and prenom = prenomExp;
    if found THEN
      return -1;
    end if;
    Insert into Expert values (nomExp,prenomExp,joursDispoExp,prixExp);
    return 1;
  elsif action = 'update' THEN
    update expert set nbJoursDispo = joursDispoExp, prix = prixExp where nom = nomExp and prenom = prenomExp;
    return 1;
  else
    delete from TypeExperts where nomExpert = nomExp and prenomExpert = prenomExp;
    delete from Expert where nom = nomExp and prenom = prenomExp;
    return 1;
  end if;
  EXCEPTION WHEN OTHERS THEN
      return -2;
END;
$$ LANGUAGE plpgsql;


--Ajoute, modifie ou supprime un lieu
Create or replace function gestionLieux(nomLieu text, surfaceLieu integer, nbPlaceLieu integer, infosLieu text, prixLieu real, action text) returns integer as $$
  BEGIN
    if action = 'insert' THEN
      --Vérification du lieu dans la table
      perform * from lieux where nomLocal = nomLieu;
      if found THEN
        return -1;
      end if;
      Insert into Lieux values(nomLieu,surfaceLieu,nbPlaceLieu,infosLieu,prixLieu);
      return 1;
    elsif action = 'update' THEN
      update lieux set surface = surfaceLieu, nb_place = nbPlaceLieu, infos = infosLieu, prix = prixLieu where nomLocal = nomLieu;
      return 1;
    else
      Perform * from reservation where nomLocal = nomLieu;
      if found THEN
        return -3;
      else
        delete from LieuxEquipements where nomlocal = nomLieu;
        delete from lieux where nomLocal = nomLieu;
        return 1;
      end if;
    end if;

    EXCEPTION WHEN OTHERS THEN
        return -2;
  END;
$$ LANGUAGE plpgsql;


--Ajoute modifie ou supprime un équipement
Create or replace function gestionEquipement(nomEq text, nbDispo integer, prixU real, action text) returns integer as $$
  BEGIN
    if action = 'insert' THEN
      --Vérification de l'équipement dans la table
      perform * from equipement where nomEquip = nomEq;
      if found THEN
        return -1;
      end if;
      Insert into Equipement values(nomEq,nbDispo,prixU);
      return 1;
    elsif action = 'update' THEN
      update equipement set nb_Disponible = nbDispo, prixUnite = prixU where nomEquip = nomEq;
      return 1;
    else
      delete from LieuxEquipements where nomEquip = nomEq;
      delete from equipement where nomEquip = nomEq;
      return 1;
    end if;

    EXCEPTION WHEN OTHERS THEN
        return -2;
END;
$$ LANGUAGE plpgsql;

--Fonction de statistiques sur le nombre de locations, formations, évenements, co-workings, clients et bénéfices par mois
CREATE or Replace function updateStats() returns integer as $$
  Declare
    --Sauvegarde du mois courant
    curMonth integer;
    --Record pour parcourir le total des sommes versés par les clients sur le mois courant
    sum record;
    --Sauvegarde du total des paiements de clients pour le mois
    total real;

  BEGIN
    curMonth = extract(month from current_timestamp);
    total = 0.0;
    --Parcours des sommes encaissés pour des réservations du mois courant
    for sum in select somme from paiement where idr in (select idr from reservation where extract(month from dateDeb) = curMonth) loop
      total = total + sum.somme;
    end loop;

    --Affectation des valeurs au tableau de retours
    insert into statistiques values(
      current_date,
      (select count(*) from Reservation where type = 'location' and (select extract(month from dateDeb)) = curMonth),
      (select count(*) from Reservation where type = 'formation' and extract(month from dateDeb) = curMonth),
      (select count(*) from Reservation where type = 'evenement' and extract(month from dateDeb) = curMonth),
      (select count(*) from Reservation where type = 'CoWorking' and extract(month from dateDeb) = curMonth),
      (select count(*) from client),
      total);

    return 1;

    EXCEPTION WHEN OTHERS THEN
        return -2;
    END;
$$ LANGUAGE plpgsql;

/* -----------PARTIE CLIENT---------------*/

--Ajout d'un email dans la newsLetter
CREATE or Replace function ajoutNewsLetter(newEmail TEXT) returns integer as $$
    BEGIN
        --Vérification que l'email est lié à un utilisateur
        Perform * from Utilisateur where email = newEmail;

        if not found THEN
          --Création d'un utilisateur
          Perform creerUtilisateur(newEmail,'','','');
        end if;
        --Vérification que l'email ne soit pas déjà inscrit à la newsLetter
        Perform * from NewsLetter where emailN = newEmail;

        if not found then
            --Insertion dans la newsLetter
            Insert into NewsLetter values (newEmail);
            --Code de réussite
            return 1;
        else
            --Présence de l'email dans la newsLetter
            return -1;

        end if;
        EXCEPTION WHEN OTHERS THEN
            return -2;
    END;
$$ LANGUAGE plpgsql;

--identification d'un Utilisateur
Create or Replace function estAdmin(email text) returns boolean as $$
  BEGIN
    --Vérification de la présence de l'email dans la table admin
    Perform * from Admin where emailA = email;

    if found THEN
      --L'email appartient à un admin
      return true;
    --L'email n'appartient pas à un admin
    else return false;

    end if;
  END;
$$ LANGUAGE plpgsql;

Create or Replace function estClient(email text) returns boolean as $$
  BEGIN
    --Vérification de la présence de l'email dans la table client
    Perform * from Client where emailC = email;

    if found THEN
      --L'email appartient à un client
      return true;
    --L'email n'appartient pas à un client
    else return false;

    end if;
  END;
$$ LANGUAGE plpgsql;
