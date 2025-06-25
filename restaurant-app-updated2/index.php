<?php
// Database connection
require_once 'includes/db.php';

// Fetch dishes from the database
$sql = "SELECT * FROM plat";
$result = $pdo->query($sql);

$plats = [];
if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $plats[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css">
    <style>
        #modifier-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-left: 10px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 1rem;
        }

        #modifier-btn:hover {
            background-color: #45a049;
        }

        /* Style pour la poubelle */
        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #ff4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #cc0000;
        }

        .plat {
            position: relative;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: none;
            border-radius: 10px;
            width: 300px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .modal-buttons {
            margin-top: 20px;
        }

        .modal-btn {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .confirm-btn {
            background-color: #ff4444;
            color: white;
        }

        .cancel-btn {
            background-color: #666;
            color: white;
        }

        .confirm-btn:hover {
            background-color: #cc0000;
        }

        .cancel-btn:hover {
            background-color: #444;
        }

        .clr {
            color: black;
        }

        h1 {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Distribute space to sides */
            padding: 0 20px; /* Add some padding */
        }

        .logo-container {
            display: inline-flex;
            align-items: center;
        }

        .logo-container img {
            height: 150px;
            margin-right: 20px;
            vertical-align: middle;
        }

        h1 > div:last-child {
            flex-grow: 1; /* Allow text to take up remaining space */
            text-align: center; /* Center the text */
        }
    </style>
    <title>Menu de Plats</title>
</head>

<body>
    <header>
        <h1>
            <div class="logo-container">
                <img src="img/logo.png" alt="Restaurant Logo">
            </div>
            <div>Bienvenue au Menu de Plats</div>
        </h1>
        
        <div class="header-content">
            <nav>
                <ul>
                    <li><a href="#menu-pizzas">Pizzas</a></li>
                    <li><a href="#menu-pates">Pâtes</a></li>
                    <li><a href="#menu-salades">Salades</a></li>
                    <li><a href="#menu-burgers">Burgers</a></li>
                </ul>
            </nav>
            <input type="text" id="search-input" placeholder="Rechercher un plat..." />
            <button id="modifier-btn" onclick="window.location.href='addProduct.php'">Ajouter des plats</button>
        </div>
    </header>

    <main>
        <div class="menu-content">
            </div>

        <section id="facture" class="facture">
            <h2>FACTURE</h2>
            <div id="facture-details"></div>
            <p>
                <strong>Total : <span id="total">0DH</span></strong>
            </p>
            <button id="imprimer-btn">Imprimer la facture</button>
        </section>
        
    </main>

    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3 class="clr" >Confirmer la suppression</h3>
            <p class="clr">Êtes-vous sûr  de vouloir supprimer ce plat ?</p>
            <div class="modal-buttons">
                <button id="confirmDelete" class="modal-btn confirm-btn">Supprimer</button>
                <button id="cancelDelete" class="modal-btn cancel-btn">Annuler</button>
            </div>
        </div>
    </div>

    <form id="deleteForm" method="POST" action="delete_plat.php" style="display: none;">
        <input type="hidden" id="deleteId" name="id_plat" value="">
    </form>
    

    <script>
        let plats = <?php echo json_encode($plats); ?>;

        const menuContent = document.querySelector('.menu-content');
        const factureDetails = document.getElementById("facture-details");
        const totalElement = document.getElementById("total");
        const searchInput = document.getElementById("search-input");
        const deleteModal = document.getElementById("deleteModal");
        const confirmDeleteBtn = document.getElementById("confirmDelete");
        const cancelDeleteBtn = document.getElementById("cancelDelete");
        const deleteForm = document.getElementById("deleteForm");
        const deleteIdInput = document.getElementById("deleteId");
        
        let facture = [];
        let platToDelete = null;

        
        const categories = ['pizza', 'pate', 'salade', 'burger']; 

        function renderMenu() {
            menuContent.innerHTML = ''; // Clear existing menu

            categories.forEach((category) => {
                const section = document.createElement('section');
                section.id = `menu-${category}s`;
                section.className = 'menu-category';

                const title = document.createElement('h2');
                title.className = 'category-title';
                title.textContent = 'Nos ' + category.charAt(0).toUpperCase() + category.slice(1) + 's';

                const platsContainer = document.createElement('div');
                platsContainer.className = 'plats-container';

                plats
                    .filter((plat) => plat.type === category)
                    .forEach((plat) => {
                        const platDiv = document.createElement('div');
                        platDiv.classList.add('plat');
                        platDiv.innerHTML = `
                            <button class="delete-btn" data-id="${plat.id_plat}" title="Supprimer ce plat">🗑️</button>
                            <img src="${plat.image}" alt="${plat.nom_plat}" onerror="this.src='https://via.placeholder.com/300x300/D4A574/2C1810?text=${encodeURIComponent(plat.nom_plat)}'">
                            <h2>${plat.nom_plat}</h2>
                            <p>${plat.description}</p>
                            <p><strong>${plat.prix} DH</strong></p>
                            <button data-id="${plat.id_plat}">+</button>
                        `;

                        platsContainer.appendChild(platDiv);

                        // Bouton ajouter à facture
                        platDiv.querySelector('button[data-id="' + plat.id_plat + '"]:not(.delete-btn)').addEventListener('click', () => ajouterAuFacture(plat.id_plat));
                        
                        // Bouton supprimer
                        platDiv.querySelector('.delete-btn').addEventListener('click', (e) => {
                            e.stopPropagation();
                            platToDelete = plat.id_plat;
                            deleteModal.style.display = 'block';
                        });
                    });

                section.appendChild(title);
                section.appendChild(platsContainer);
                menuContent.appendChild(section);
            });
        }

        function ajouterAuFacture(idPlat) {
            const plat = plats.find((p) => p.id_plat == idPlat);
            if (!plat) return;

            const existingItem = facture.find((item) => item.id_plat == idPlat);
            if (existingItem) {
                existingItem.quantite++;
            } else {
                facture.push({ ...plat, quantite: 1 });
            }

            mettreAJourFacture();
        }

        function mettreAJourFacture() {
            factureDetails.innerHTML = "";
            let total = 0;

            facture.forEach((item) => {
                total += item.quantite * item.prix;

                const itemDiv = document.createElement("div");
                itemDiv.classList.add("facture-item");
                itemDiv.innerHTML = `
                    <span>${item.nom_plat} (x${item.quantite})</span>
                    <span>${item.quantite * item.prix} DH</span>
                    <button class="annuler-btn" data-id="${item.id_plat}">Annuler</button>
                `;
                factureDetails.appendChild(itemDiv);
            });

            totalElement.textContent = `${total} DH`;

            document.querySelectorAll(".annuler-btn").forEach((button) => {
                button.addEventListener("click", (e) => {
                    const idAnnuler = e.target.getAttribute("data-id");
                    annulerCommande(idAnnuler);
                });
            });
        }

        function annulerCommande(idPlat) {
            facture = facture.filter((item) => item.id_plat != idPlat);
            mettreAJourFacture();
        }

        // Gestion du modal de suppression
        confirmDeleteBtn.addEventListener('click', () => {
            if (platToDelete) {
                deleteIdInput.value = platToDelete;
                deleteForm.submit();
            }
        });

        cancelDeleteBtn.addEventListener('click', () => {
            deleteModal.style.display = 'none';
            platToDelete = null;
        });

        // Fermer le modal en cliquant à l'extérieur
        window.addEventListener('click', (e) => {
            if (e.target === deleteModal) {
                deleteModal.style.display = 'none';
                platToDelete = null;
            }
        });

        document.getElementById("imprimer-btn").addEventListener("click", () => {
            const factureSection = document.getElementById("facture");

            const printWindow = window.open("", "_blank", "width=600,height=800");
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Facture</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 0; padding: 1rem; }
                            h2 { text-align: center; }
                            #facture-details { margin-top: 1rem; }
                            .facture-item { display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; padding: 0.5rem 0; }
                            strong { font-size: 1.2rem; }
                        </style>
                    </head>
                    <body>
                        <h2>Facture</h2>
                        ${factureSection.innerHTML}
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.onafterprint = () => printWindow.close();
        });

        // Recherche
        searchInput.addEventListener("input", (event) => {
            const searchTerm = event.target.value.toLowerCase();
            const platElements = document.querySelectorAll(".plat");

            platElements.forEach((platEl) => {
                const nomPlat = platEl.querySelector("h2").textContent.toLowerCase();
                const descriptionPlat = platEl.querySelector("p").textContent.toLowerCase();

                if (nomPlat.includes(searchTerm) || descriptionPlat.includes(searchTerm)) {
                    platEl.style.display = "block";
                } else {
                    platEl.style.display = "none";
                }
            });
        });

        // Initial render
        renderMenu();

        // Function to refresh the menu after adding/editing a dish
        function refreshMenu() {
            fetch('index.php')
                .then(response => response.text())
                .then(data => {
                    const parser = new DOMParser();
                    const htmlDoc = parser.parseFromString(data, 'text/html');
                    const scriptContent = htmlDoc.querySelector('script').textContent;

                    // Extract the plats array from the script content
                    const platsMatch = scriptContent.match(/let plats = (\[.*?\]);/);
                    if (platsMatch && platsMatch[1]) {
                        plats = JSON.parse(platsMatch[1]);
                        renderMenu();
                    }
                });
        }
    </script>
</body>
</html>