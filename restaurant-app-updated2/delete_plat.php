<?php
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_plat'])) {
    $id = (int) $_POST['id_plat'];

    try {
        // Récupérer les informations du plat avant suppression pour supprimer l'image
        $stmt = $pdo->prepare("SELECT image FROM plat WHERE id_plat = ?");
        $stmt->execute([$id]);
        $plat = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($plat) {
            // Supprimer le plat de la base de données
            $deleteStmt = $pdo->prepare("DELETE FROM plat WHERE id_plat = ?");
            $deleteStmt->execute([$id]);

            // Supprimer l'image du dossier uploads si elle existe
            if (!empty($plat['image']) && file_exists($plat['image'])) {
                unlink($plat['image']);
            }

            // Redirection avec message de succès
            header("Location: index.php?deleted=success");
            exit;
        } else {
            header("Location: index.php?deleted=error");
            exit;
        }
    } catch (PDOException $e) {
        // En cas d'erreur, rediriger avec message d'erreur
        header("Location: index.php?deleted=error");
        exit;
    }
} else {
    echo "Suppression invalide.";
}
?>