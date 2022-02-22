# Projet M3301 (dépôt de rendu)

Ce dépôt est le dépôt de référence de votre équipe pour le module M3301.
Vos rendus se feront en déposant tous les fichiers pertinents pour chaque itération ici.

#### Fichiers particuliers

Les deux fichiers `.gitattributes` et `.gitignore` sont liés à la configuration de git.<br>
Vous pouvez modifier le fichier `.gitignore` en fonction des technologies utilisées et de l'organisation du dépôt choisie.<br>
Il est vivement déconseillé de modifier le fichier `.gitattributes`.

Le dossier `.gitlab` contient la configuration spécifique à GitLab.

### Organisation du dépôt

Le dépôt est organisé comme suit :

```console
rendus
|
├── Itération1/
├── Itération2/
├── Itération3/
├── Page/
│   └── controler/
│        └── config/
│   └── cron/
│   └── data/
│   └── framework/
│   └── data/
│   └── model/
│   └── test/
│   └── view/
│        └── documents/
│        └── fonts/
│        └── images/
│        └── scripts/
│        └── style/
├── .gitattributes
├── .gitignore
├── .gitlab
│   └── …
└── README.md
```

**Vous trouverez les rendus textuels au format `pdf` dans les dossiers correspondant à leurs itérations `itérationX/`.<br>**

**L'ensemble du code se trouve dans le répertoire `Page/`.**

## Sommaire

<ol>
    <li> [Accès au site web](#accès-au-site-web) </li>
    <li> [Accès au serveur](#accès-au-serveur) </li>
    <li> [Accès aux ressources du projet](#accès-aux-ressources-du-projet)</li>
    <li> [Accès à la base de données](#accès-à-la-base-de-donnée) </li>
    <li> [Connexion SSH](#connexion-ssh)
        <ol>
            <li>[Clés SSH](#clés-ssh) </li>
            <li>[Génération d'une paire de clés SSH](#génération-dune-paire-de-clés-ssh) </li>
        </ol>
    </li>
</ol>

### Accès au site web

Vous pouvez vous rendre sur le site web du projet à l'adresse suivante : https://elan.ddns.net

Les identifiants du compte administrateur sur le site sont :<br/>
**Login** : admin@gmail.com<br/>
**Mot de passe** : adminElanm3301<br/>

Si besoin, voici un compte utilisateur classique :<br/>
**Login** : utilisateur@gmail.com<br/>
**Mot de passe** : MDPutilisateur1<br/>

### Accès au serveur

Vous pouvez également vous connecter en SSH au serveur pour accéder aux fichiers ainsi qu'à la base de données.
La section [connexion SSH](#connexion-ssh) détaille les étapes à suivre.

### Accès aux ressources du projet

Une fois connecté en SSH, on trouve sur le homedir de l'utilisateur elan un lien symbolique nommé "rendus" qui méne vers les ressources du projet.
Tapez la commande suivante pour acceder aux ressources :

```console
cd rendus
```

### Accès à la base de données

Une fois connecté en SSH, tapez la commande suivante pour accéder à la base de données postgresql en tant qu'administrateur :

```console
psql
```

### Connexion SSH

**Login** : elan<br/>
**Mot de passe** : elanm3301<br/>
Il faut également une **clé SSH autorisée à se connecter au serveur** pour cela merci de vous référer à la section [clé SSH](#clé-ssh).

Vous pourrez utiliser des clients de connexion SSH (FileZila, Putty, Bitvise, ...) ou utiliser la console (sur Linux le terminal ou sur Windows PowerShell).

Si vous utilisez un logiciel de connexion SSH, il faudra ajouter la clé SSH au paramètre de votre logiciel et utiliser les informations ci-dessus.
Si vous utilisez le terminal :

**Cas 1** : votre clé SSH se trouve dans votre **répertoire ~/.ssh/**, vous pourrez vous connecter grâce à la commande suivante :

```console
ssh elan@elan.ddns.net
```

**Cas 2** : votre clé SSH se trouve dans un **autre répertoire**, vous pourrez vous connecter grâce à la commande suivante :

```console
ssh elan@elan.ddns.net -i [CHEMIN_DE_LA_CLE_PRIVEE]
```

### Clés SSH

Si vous avez déjà une paire de clés SSH, il vous suffira d'envoyer votre clé publique au propriétaire du serveur à l'adresse mail suivante :
**lilian.steimer@etu.univ-grenoble-alpes.fr**

Si ce n'est pas le cas, merci de vous référer à la section [Génération d'une paire de clés SSH](#génération-dune-paire-de-clés-ssh).

Dès que le propriétaire du serveur aura autorisé votre clé sur le serveur, il vous le notifira par email et vous pourrez vous connecter.

### Génération d'une paire de clés SSH

Sur Windows :
Vérifiez que les composants OpenSSH sont installés :

    Ouvrez "Paramètres", sélectionnez "Applications" > "Applications et fonctionnalités", puis "Fonctionnalités facultatives".
    Parcourez la liste pour voir si OpenSSH est déjà installé. Si ce n’est pas le cas, sélectionnez "Ajouter une fonctionnalité" en haut de la page, puis :
    Recherchez OpenSSH Client et cliquez sur "Installer".
    Recherchez OpenSSH Server et cliquez sur "Installer".

Sur Linux :
Avec votre gestionnaire de paquet préféré, installez **openssh-client** et **openssh-server**

Ensuite :
Sur Windows 10 --> Ouvrez Powershell
Sur Linux --> Ouvrez le terminal

    - Entrez la commande suivante :
           ssh-keygen -t ed25519
    - Entrez le répertoire où sauvegarder votre clé (ou laissez l’emplacement par défaut (~/.ssh/) en appuyant sur "Entrée")
    - Entrez un mot de passe pour protéger votre clé privée (non obligatoire, appuyez sur "Entrée" pour continuer)

    Voilà, la paire de clés SSH a bien été générée dans le répertoire sélectionné sous les noms :
      - id_ed25519 (private)
      - id_ed25519.pub (publique)

Maintenant que vos clés sont générées, il vous suffira d'envoyer votre clé publique au propriétaire du serveur à l'adresse mail suivante :
**lilian.steimer@etu.univ-grenoble-alpes.fr**
