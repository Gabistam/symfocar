//script.js

// Création de la session Stripe

let publicKey = document.body.dataset.publicKey;
let stripe = Stripe(publicKey);
        let checkoutButton = document.getElementById('checkout-button');
        checkoutButton.addEventListener('click', function() {
            // Ajoutez la référence de la commande à l'URL de la requête fetch
            fetch('http://127.0.0.1:8000/commande/create-session', {
                method: "POST",
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(session) {
                return stripe.redirectToCheckout({ sessionId: session.id });
            })
            .then(function(result) {
                if (result.error) {
                    alert(result.error.message);
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
            });
        });