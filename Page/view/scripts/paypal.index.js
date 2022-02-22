paypal.Buttons({
  style: {
    color:'blue',
    shape:'pill'
  },

// Set up the transaction
  createOrder: function(data, actions) {
      return actions.order.create({
          purchase_units: [{
              amount: {
                  value: prixTotal.toString()
              }
          }]
      });
  },

  // Finalize the transaction
  onApprove: function(data, actions) {
      return actions.order.capture().then(function(orderData) {
          // Successful capture! For demo purposes:
          /*
          console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
          var transaction = orderData.purchase_units[0].payments.captures[0];
          alert('Transaction '+ transaction.status + ': ' + transaction.id + '\n\nSee console for all available details');
          */
          // Affichage du message de remerciement
          console.log(orderData)
          const element = document.getElementById('paypal-payment-container');
          element.innerHTML = '';
          element.innerHTML = '<h3>Merci pour votre confiance !</h3>';
          document.getElementById('paypal-payment-container').parentNode.firstElementChild.remove(); // supression du paragraphe de la section

          function redirect () {
            // (B1) URL SEARCH PARAMS (QUERY STRING)
            var params = new URLSearchParams();
            params.set("idR", idR);
           
            // (B2) REDIRECTION
            window.location.href = "../../controler/paiementPaypal.ctrl.php?" + params.toString();
            return false;
          }
          redirect();
          // document.createAttribute('div').innerHTML = '<h3>Merci pour votre confiance !</h3>';

          // Replace the above to show a success message within this page, e.g.
          // const element = document.getElementById('paypal-button-container');
          // element.innerHTML = '';
          // element.innerHTML = '<h3>Thank you for your payment!</h3>';
          // Or go to another URL:  actions.redirect('thank_you.html');
        })
      }/*,
      onCancel: function (data) {
          window.location.replace("https://elan.ddns.net/controler/paiement.ctrl.php")
      }*/


}).render('#paypal-payment-container');
