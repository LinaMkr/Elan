/* Permet de confimer lla volonté du client et
  de remplacer la ligne par un lien cliquable qui mène
  au controleur rejoindreEntree.ctrl.php */
function ligneCliquable(idR) {
  var element = document.getElementsByClassName("ligneC");

  var rejoindre = confirm("Etes-vous sur de vouloir rejoindre la réservation ?\nVous ne pourrez pas annuler.");

  if(rejoindre){
    for(var counter = 0; counter < element.length; counter++){
      element[counter].innerHTML = self.location.href = "./rejoindreEntree.ctrl.php?idR="+idR;
    }
  }
  return rejoindre;
}
