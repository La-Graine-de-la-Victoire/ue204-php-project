function addToCart(productId) {
    fetch('/add_to_cart.php', {
        method: 'POST',
        body: JSON.stringify({ product_id: "id_produit_uno" }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            // Gérer la réponse ici, par exemple, mettre à jour l'icône du panier avec le nouveau nombre d'articles
            const cartItemsElement = document.querySelector('.panier span');
            if (cartItemsElement) {
                let currentNumberOfItems = parseInt(cartItemsElement.textContent);
                currentNumberOfItems++;
                cartItemsElement.textContent = currentNumberOfItems;
            }
        } else if (data.error) {
            console.error('Erreur lors de l\'ajout au panier :', data.error);
        }
    })
    .catch(error => {
        console.error('Erreur lors de l\'ajout au panier :', error);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const headerContainer = document.querySelector('.header-container');

    menuToggle.addEventListener('click', function() {
        headerContainer.classList.toggle('change');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const menuOverlay = document.querySelector('.menu-overlay');
    const menuClose = document.createElement('div');
    menuClose.className = 'menu-close';
    menuClose.innerHTML = '&times;'; // Utilisez la croix comme symbole de fermeture

    menuToggle.addEventListener('click', function() {
        menuOverlay.classList.toggle('show');
        if (menuOverlay.classList.contains('show')) {
            document.body.appendChild(menuClose); // Ajoutez le bouton de fermeture
        } else {
            document.body.removeChild(menuClose); // Supprimez le bouton de fermeture
        }
    });

    menuOverlay.addEventListener('click', function(event) {
        if (event.target === menuOverlay) {
            menuOverlay.classList.remove('show');
            document.body.removeChild(menuClose);
        }
    });

    menuClose.addEventListener('click', function() {
        menuOverlay.classList.remove('show');
        document.body.removeChild(menuClose);
    });
});
