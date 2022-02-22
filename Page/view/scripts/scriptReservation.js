function griserDateFin(){
    // récupère l'input file
    let inputAGriser = document.getElementById('dateFin');
    let inputHoraire = document.getElementById('horaire-select');
    let valeurselectionnee = inputHoraire.options[inputHoraire.selectedIndex].value;

    if(valeurselectionnee=="jours"){ // si plusieurs jours sont séléctionnés
        inputAGriser.disabled = false; // on dégrise la case
    }
    else{
        inputAGriser.disabled = true; // on grise la case
    }

    if (valeurselectionnee=="matin") {

    } else if (valeurselectionnee=="aprem") {

    } else if (valeurselectionnee=="journee") {

    } else if (valeurselectionnee=="jours") {

    }

}

function updatePrix() {
    const element_prix_total = document.querySelector('.prixTotal');

    var total = 0;
    var checkboxs = document.querySelectorAll('.checkbox');
    checkboxs.forEach(checkbox => {
        if (checkbox.checked) {
            total += parseFloat(checkbox.value);
        }
    })

    element_prix_total.innerText = total + '€';
}

