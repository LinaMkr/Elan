// --------------------------------------------------------------
// Elements de la pages 

const weekdays_element = document.querySelector('.weekdays');
const table_head_element = document.querySelector('thead');
const table_body_element = document.querySelector('tbody');

// Titre agenda
const title_element = document.querySelector('.title');

////// Boutons thead nav bar //////

const prev_week_element = document.querySelector('.prev-week');
const home_current_week_element = document.querySelector('.current-week');
const next_week_element = document.querySelector('.next-week');

// Liste des heures de la journée sur l'agenda
const hours = [
    '08:00',
    '08:30',
    '09:00',
    '09:30',
    '10:00',
    '10:30',
    '11:00',
    '11:30',
    '12:00',
    '12:30',
    '13:00',
    '13:30',
    '14:00',
    '14:30',
    '15:00',
    '15:30',
    '16:00',
    '16:30',
    '17:00',
    '17:30',
    '18:00',
    '18:30',
    '19:00',
    '19:30',
    '20:00',
    '20:30',
    '21:00',
    '21:30',
    '22:00'
]

// Liste des jours de la semaine
const weekdays = [
    'Lun',
    'Mar',
    'Mer',
    'Jeu',
    'Ven',
    'Sam',
    'Dim'
]

// Liste des mois de l'année
const months = [
    'Janvier',
    'Février',
    'Mars',
    'Avril',
    'Mai',
    'Juin',
    'Juillet',
    'Août',
    'Septembre',
    'Octobre',
    'Novembre',
    'Décembre'
];

// Weekdays déjà afficher dans l'en-tête du calendrier ou non (bool)
var weekdaysGenerated = false;

// Date courante
let currentDate = moment().toDate();
let currentDay = currentDate.getDate();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

// ----------------- EventListener -----------------

// EventListener pour les boutons de navigations
prev_week_element.addEventListener('click', goToPrevWeek);
home_current_week_element.addEventListener('click', goToCurrentWeek);
next_week_element.addEventListener('click', goToNextWeek);

// ----------------- Fonction d'affichage -----------------

// Fonction de mise à jour du calendrier (création également appel la fonction generateCalendar())
function updateCalendar(e) {
    clearCalendar();
    updateTitle();
    generateCalendar();
    for (let i = 0; i < tableauReservations.length; i++) {
        const map = new Map(Object.entries(tableauReservations[i]));  
        ajouterReservation(map);
    }
}

// Fonction création / génaration du calendrier
function generateCalendar(e) {

    // Affichage des jours de la semaine
    if (!weekdaysGenerated) {
        for (let i = 0; i < weekdays.length; i++) {
            const day_element = document.createElement('th');
            day_element.textContent= weekdays[i];
            weekdays_element.appendChild(day_element);
        } 
        weekdaysGenerated = true;
    }

    // Affichage des heures
    for (let i = 0; i < hours.length; i++) {
        const hour_tr_element = document.createElement('tr');
        for (let j = 0; j < 8; j++) {
            if (j == 0) {
                const hour_th_element = document.createElement('th');
                hour_th_element.textContent = hours[i]; 
                hour_th_element.classList.add('hour');
                hour_tr_element.appendChild(hour_th_element);
            } else {
                const hour_td_element = document.createElement('td');
                hour_td_element.classList.add(weekdays[j - 1]);
                hour_tr_element.appendChild(hour_td_element);
            }
        }  
        table_body_element.appendChild(hour_tr_element);
    }
}

// Fonction de nettoyage des élements du calendrier
function clearCalendar(e) {
    while (table_body_element.firstChild) {
        table_body_element.removeChild(table_body_element.firstChild);
    }
}

// Fonction de mise à jour du titre du calendrier
function updateTitle(e) {
    
    var firstDayCurrentWeek = moment(currentDate).startOf('isoWeek').toDate();
    var lastDayCurrentWeek = moment(currentDate).endOf('isoWeek').toDate();
    
    // Affichage du titre
    if (firstDayCurrentWeek.getMonth() == lastDayCurrentWeek.getMonth()) {
        title_element.innerText = (firstDayCurrentWeek.getDate()) + " - " + (lastDayCurrentWeek.getDate()) + " " + months[currentDate.getMonth()] + " " + currentDate.getFullYear();
    } else if (firstDayCurrentWeek.getMonth() > lastDayCurrentWeek.getMonth() && firstDayCurrentWeek.getFullYear() < lastDayCurrentWeek.getFullYear()) {
        title_element.innerText = (firstDayCurrentWeek.getDate()) + " " + months[firstDayCurrentWeek.getMonth()] + " " + firstDayCurrentWeek.getFullYear() + " - " + (lastDayCurrentWeek.getDate()) + " " + months[currentDate.getMonth()] + " " + currentDate.getFullYear();
    } else if (firstDayCurrentWeek.getMonth() < lastDayCurrentWeek.getMonth() && firstDayCurrentWeek.getFullYear() == lastDayCurrentWeek.getFullYear()) {
        title_element.innerText = (firstDayCurrentWeek.getDate()) + " " + months[firstDayCurrentWeek.getMonth()] + " - " + (lastDayCurrentWeek.getDate()) + " " + months[currentDate.getMonth()] + " " + currentDate.getFullYear();
    }
}


// ----------------- Fonction de navigation entre les semaines -----------------

// Aller à la semaine précédente
function goToPrevWeek(e) {
    currentDate.setDate(currentDate.getDate() - 7);  
    updateCalendar();
}

// Aller à la semaine courante
function goToCurrentWeek(e) {
    currentDate = moment().toDate();
    currentDay = currentDate.getDate();
    currentMonth = currentDate.getMonth();
    currentYear = currentDate.getFullYear();
    updateCalendar();
}
  
// Aller à la semaine suivante
function goToNextWeek(e) {
    currentDate.setDate(currentDate.getDate() + 7);  
    updateCalendar();
}

// ----------------- Fonction utilitaire -----------------

// Retourne le numéro de la semaine courante 
Date.prototype.getWeek = function() {
    var dt = new Date(this.getFullYear(),0,1);
    return Math.ceil((((this - dt) / 86400000) + dt.getDay()+1)/7) - 1;
};

// Arrondi le nombre n à 0.5 près
var roundHalf = function(n) {
    return (Math.round(n*2)/2).toFixed(1);
};

// Ajouter une réunion
function ajouterReservation(reservation) {

    // Split de la chaine de caracteres de forme "YYYY-MM-DD-HH-mm-ss" vers un tableau ayant a chaque index un attribut permettant de crée la date
    const dateDebutParameter = reservation.get("dateDebut").split('-');
    const dateFinParameter = reservation.get("dateFin").split('-');

    // ----------------- Création des dates -----------------
    // dateDebut = date de debut de la réservation
    // dateFin = date de fin de la réservation
    // startOfWeek = date du premier jour de la semaine courante de l'agenda
    // dateDebut = date du dernier jour de la semaine courante de l'agenda

    var dateDebut = new Date(dateDebutParameter[0],dateDebutParameter[1]-1,dateDebutParameter[2],dateDebutParameter[3],dateDebutParameter[4],0);
    var dateFin = new Date(dateFinParameter[0],dateFinParameter[1]-1,dateFinParameter[2],dateFinParameter[3],dateFinParameter[4],0);
    var startOfWeek = moment(currentDate).startOf('isoWeek');
        startOfWeek = startOfWeek.set({'hour' : 8, 'minute' : 0, 'second' : 0}).toDate();
    var endOfWeek = moment(currentDate).endOf('isoWeek');
        endOfWeek = endOfWeek.set({'hour' : 17, 'minute' : 30, 'second' : 0}).toDate();

    // ------------------------------------------------------

    // --------------------- Vérifications ---------------------
    // L'ensemble des vérifications ci dessous permettent de savoir comment afficher la réservation en fonction de sa date de commencement et de fin

    // Si dateDebut et dateFin de la reservation ne font pas parti de la semaine courante ==> on quite la fonction car nous n'avons pas bessoin de d'afficher la réservation
    // Sinon ==> on continue

    if (!moment(dateDebut).isBetween(startOfWeek,endOfWeek) && !moment(dateFin).isBetween(startOfWeek,endOfWeek)) {
        return;
    } 
    
    // Si dateDebut fait parti de la semaine courante de la reservation mais pas dateFin (réservation qui dure sur plusieurs semaine différentes)
    // ==> dateFin = endOfWeek
    // Sinon ==> on continue

    if (moment(dateDebut).isBetween(startOfWeek,endOfWeek) && !moment(dateFin).isBetween(startOfWeek,endOfWeek)) {
        dateFin = endOfWeek;
    }

    // Si dateFin fait parti de la semaine courante de la reservation mais pas dateDebut (réservation qui dure sur plusieurs semaine différentes)
    // ==> dateDebut = startOfWeek
    // Sinon ==> on continue
    if (!moment(dateDebut).isBetween(startOfWeek,endOfWeek) && moment(dateFin).isBetween(startOfWeek,endOfWeek)) {
        dateDebut = startOfWeek;
    }

    // ----------------- Fin des vérifications -----------------

    // Création d'un tableau contenant toutes les cases ayant comme classe CSS '.hour'
    const hours = Array.from(document.querySelectorAll('.hour'));

    do {

        // Date de fin du jour courant
        var dateFinCurrentDay = new moment(dateFin).toDate();

        // Si la réservation se fini un jour ultérieur au jour courant alors heure fin reservation pour jour courant = 22h
        if (dateDebut.getDate() < dateFin.getDate()) {
            dateFinCurrentDay =  moment(dateFin).set({'date' : dateDebut.getDate(), 'hour' : 17, 'minute' : 30}).toDate();
        }

        // Fait en sorte qu'une semaine commence par Lundi car l'objet Date() est indéxé de [0-6] pour les jours de la semaine, 0 étant le dimanche
        day = dateDebut.getDay() - 1;
        if (day == -1) { day = 6; }

        // ----------------- Variables de temps -----------------
        // meetingDuration = Durée en heures de la réservation pour le jour courant
        // meetingStartHour = Heure de début de la réservation pour le jour courant
        // meetingEndHour = Heure de fin de la réservation pour le jour courant

        var meetingDuration = roundHalf((new moment(dateFinCurrentDay).diff(new moment(dateDebut),'hours', true)));
        var meetingStartHour = hours[hours.findIndex(th => th.innerText == moment(dateDebut).format("HH[:]mm"))];
        var meetingEndHour = hours[hours.findIndex(th => th.innerText == moment(dateDebut).add(meetingDuration, 'hours').format("HH[:]mm"))];

        // ------------------------------------------------------

        // Création d'un tableau contenant toutes les cases suceptible de contenir une réservation (toutes les heures de tous les jours de la semaine)
        var allTd = document.querySelectorAll('.'+weekdays[day]);

        // Création du td qui affiche la reservation et définition de ses attributs
        const tableTd = meetingStartHour.parentNode.querySelector('.'+weekdays[day]);
        // tableTd.classList.add(reservation.get("typeReservation"));
        tableTd.classList.add("reservation");
        tableTd.innerText = "Créneau horaire déjà réservé";
        tableTd.rowSpan = meetingDuration*2;
        
        // Boucle pour supprimer toutes les cases déjà crée sur la période d'une reservation
        for (var i = 0; i < allTd.length; ++i) {
            // caseTd = le i-ème élement de allTd
            var caseTd = allTd[i];

            // Si caseTd n'a pas comme classe '.reservation'
            if (!caseTd.classList.contains("reservation")) {

                // Hour = heure de la ligne qui content la caseTd
                var hour = caseTd.parentNode.querySelector(".hour").innerHTML;
                if (caseTd != null && meetingEndHour != null && meetingStartHour != null) {

                    // Si hour est compris entre meetingStartHour et meetingEndHour
                    if (meetingStartHour.innerText < hour && hour < meetingEndHour.innerText) {
                        caseTd.remove();
                    }
                }
            }
        }

        // Incrémentation de dateDebut
        if (dateDebut.getDate() <= dateFin.getDate() ) {
            dateDebut.setDate(dateDebut.getDate()+1);

            // La réservation dure sur plusieurs jours de la même semaine donc le jours d'après elle commencera à 8h00
            dateDebut.setHours(8,0,0);
        } else {
            break;
        }

    } while (dateDebut <= dateFin)

}

updateCalendar();