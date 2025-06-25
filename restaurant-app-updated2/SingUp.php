<?php
// Start the session to store messages across redirects
session_start();

// Include the database connection file
require_once 'includes/db.php';

// Initialize message variables in the session
$message = '';
$message_type = '';

try {
    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve and sanitize input
        $nom = htmlspecialchars(trim($_POST['nom']));
        $prenom = htmlspecialchars(trim($_POST['prenom']));
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $mot_de_passe = $_POST['mot_de_passe'];

        // Validate inputs
        if (empty($nom) || empty($prenom) || !$email || empty($mot_de_passe)) {
            $_SESSION['message'] = "Tous les champs sont requis.";
            $_SESSION['message_type'] = "error";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($mot_de_passe, PASSWORD_BCRYPT);

            // Insert the data into the serveur table
            $stmt = $pdo->prepare("INSERT INTO serveur (nom, prénom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :mot_de_passe)");
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mot_de_passe', $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Inscription réussie !";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Erreur lors de l'inscription.";
                $_SESSION['message_type'] = "error";
            }
        }

        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['message'] = "Erreur de base de données : " . htmlspecialchars($e->getMessage());
    $_SESSION['message_type'] = "error";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #342113;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .form-container {
        background: white;
        padding: 30px 25px;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        width: 350px;
        animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-container h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #342113;
        font-weight: 700;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        color: #342113;
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        outline: none;
        transition: border-color 0.3s ease;
        font-size: 14px;
    }

    .form-group input:focus {
        border-color: #c19966;
    }

    .form-group button {
        width: 100%;
        padding: 12px;
        background-color: #c19966;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 15px;
        transition: background-color 0.3s ease;
    }

    .form-group button:hover {
        background-color: #a67c52;
    }

    .message {
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 8px;
        font-weight: bold;
        text-align: center;
        font-size: 14px;
    }

    .message.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .link-button {
        display: block;
        width: 100%;
        padding: 12px;
        background-color: #342113;
        color: white;
        text-align: center;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.3s ease;
        margin-top: 10px;
    }

    .link-button:hover {
        background-color: #5a3a22;
    }
</style>

</head>
<body>
    <div class="form-container">
        <?php if (!empty($_SESSION['message'])): ?>
            <div class="message <?= $_SESSION['message_type'] ?>">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>
        
        <h2>Inscription</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="mot_de_passe">Mot de Passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            <div class="form-group">
                <button type="submit">S'inscrire</button>
            </div>
        </form>
        <!-- Login Link -->
        <a href="login.php" class="link-button">Vous avez un compte ? Connectez-vous</a>

    </div>
</body>
</html>