<?php
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_plat = $_POST['nom_plat'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $type = $_POST['type'];

    // Gestion de l'image
    $image_destination = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image'];
        $image_name = $image['name'];
        $image_tmp_name = $image['tmp_name'];
        $image_size = $image['size'];

        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];

        if (in_array($image_ext, $allowed_ext)) {
            if ($image_size <= 2097152) {
                $image_new_name = uniqid("IMG-", true) . '.' . $image_ext;
                $image_destination = 'uploads/' . $image_new_name;
                move_uploaded_file($image_tmp_name, $image_destination);
            } else {
                echo "  ( La taille de l'image est trop grande)";
                exit;
            }
        } else {
            echo " (Type de fichier non autorisé.)";
            exit;
        }
    }

    // Ajouter un nouveau plat
    $sql = "INSERT INTO plat (nom_plat, description, prix, type, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom_plat, $description, $prix, $type, $image_destination]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajouter un Plat</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      color: #333;
      padding: 0;
      margin: 0;
    }
    header {
      background-color: #d4a574;
      color: #2c1810;
      padding: 1rem 0;
      text-align: center;
      border-bottom: 2px solid #2c1810;
    }
    main {
      padding: 20px;
    }
    form {
      max-width: 600px;
      margin: 20px auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
    }
    input[type="text"],
    input[type="number"],
    select,
    textarea {
      width: 100%;
      padding: 8px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    textarea {
      resize: vertical;
    }
    button[type="submit"] {
      background-color: #4caf50;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 4px;
      cursor: pointer;
    }
    button[type="submit"]:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>

  <header>
    <h1>Ajouter un Plat</h1>
  </header>

  <main>
    <form method="POST" action="addProduct.php" enctype="multipart/form-data">
      <label for="nom_plat">Nom du Plat :</label>
      <input type="text" name="nom_plat" id="nom_plat" required>

      <label for="description">Description :</label>
      <textarea name="description" id="description" required></textarea>

      <label for="prix">Prix :</label>
      <input type="number" name="prix" id="prix" required>

      <label for="type">Type :</label>
      <select name="type" id="type" required>
        <option value="pizza">Pizza</option>
        <option value="pate">Pâtes</option> <option value="salade">Salades</option> <option value="burger">Burgers</option> </select>

      <label for="image">Image :</label>
      <input type="file" name="image" id="image" accept="image/*" required>

      <button type="submit">Ajouter le Plat</button>
    </form>
  </main>

</body>
</html>
