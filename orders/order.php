<?php

class Order
{
    public $id;
    public $userId;
    public $eventId;
    public $status;
    public $totalAmount;
    public $createdAt;

    public static function createOrder($dbh, $userId, $eventId, $totalAmount, $items)
    {
        try {
            $dbh->beginTransaction();

            // Insertion de la commande
            $stmtOrder = $dbh->prepare("
                INSERT INTO `orders` (`userId`, `eventId`, `status`, `totalAmount`, `createdAt`) 
                VALUES (?, ?, 'attente', ?, NOW())
            ");
            $stmtOrder->execute([$userId, $eventId, $totalAmount]);
            $orderId = $dbh->lastInsertId();

            // Insertion des articles
            $stmtItem = $dbh->prepare("INSERT INTO `order_items` (`orderId`, `recipeId`, `quantity`) VALUES (?, ?, ?)");
            foreach ($items as $recipeId => $qty) {
                if ($qty > 0) {
                    $stmtItem->execute([$orderId, $recipeId, $qty]);
                }
            }

            $dbh->commit();
            return $orderId;

        } catch (Exception $e) {
            $dbh->rollBack();
            error_log("Erreur createOrder : " . $e->getMessage());
            throw $e;
        }
    }

    public static function getAllOrders($dbh)
    {
        try {
            // On cherche tout ce qui est en cours (attente, prepa, pret)
            $query = "SELECT * FROM `orders` ORDER BY `createdAt` ASC";
            $sth = $dbh->prepare($query);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'Order');
            $sth->execute();
            return $sth->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur getAllOrders : " . $e->getMessage());
            return [];
        }
    }

    public static function getOrderById($dbh, $id)
    {
        try {
            $query = "SELECT * FROM `orders` WHERE `id`=?";
            $sth = $dbh->prepare($query);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'Order');
            $sth->execute(array($id));
            return $sth->fetch();
        } catch (PDOException $e) {
            error_log("Erreur getOrderById : " . $e->getMessage());
            return null;
        }
    }

    public static function getOrdersByStatus($dbh, $status)
    {
        try {
            $query = "SELECT * FROM `orders` WHERE `status`=? ORDER BY `createdAt` ASC";
            $sth = $dbh->prepare($query);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'Order');
            $sth->execute(array($status));
            return $sth->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur getOrderByStatus : " . $e->getMessage());
            return null;
        }
    }

    public static function getOrderItems($dbh, $orderId)
    {
        try {
            $query =
                "SELECT * 
                FROM `order_items` oi
                JOIN `recipes` r ON oi.recipeId = r.id
                WHERE oi.orderId = ?";
            $sth = $dbh->prepare($query);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'Order');
            $sth->execute(array($orderId));
            return $sth->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur getOrderItems : " . $e->getMessage());
            return null;
        }
    }

    public static function getAllRecipes($dbh)
    {
        try {
            $sth = $dbh->query("SELECT * FROM recipes");
            return $sth->fetchAll(PDO::FETCH_UNIQUE | PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getAllRecipes: " . $e->getMessage());
            return null;
        }
    }

    public static function getStatsByStatus($dbh, $status)
    {
        try {
            $query =
                "SELECT r.name, SUM(oi.quantity) as totalQty
                FROM `order_items` as oi
                JOIN `recipes` r ON oi.recipeId = r.id
                JOIN `orders` o ON oi.orderId = o.id
                WHERE o.status = ?
                GROUP BY r.id, r.name
                ORDER BY totalQty DESC";
            $sth = $dbh->prepare($query);
            $sth->execute([$status]);
            return $sth->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur getStatsByStatus: " . $e->getMessage());
            return null;
        }
    }

    public static function updateStatus($dbh, $orderId, $newStatus)
    {
        try {
            $stmt = $dbh->prepare("UPDATE `orders` SET `status` = ? WHERE `id` = ?");
            return $stmt->execute([$newStatus, $orderId]);
        } catch (PDOException $e) {
            error_log("Erreur updateStatus : " . $e->getMessage());
            return false;
        }
    }

    public static function getUserActiveOrder($pdo, $userId)
    {
        try {
            $stmt = $pdo->prepare("SELECT * FROM `orders` WHERE `userId` = ? AND `status` != 'archive' ORDER BY `createdAt` DESC LIMIT 1");
            $stmt->execute([$userId]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Order');
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur getUserActiveOrder : " . $e->getMessage());
            return null;
        }

    }

    // Récupère les dernières commandes terminées
    public static function getUserRecentOrders($pdo, $userId, $limit = 3)
    {
        try {
            $stmt = $pdo->prepare("SELECT * FROM `orders` WHERE `userId` = ? AND `status` = 'archive' ORDER BY `createdAt` DESC LIMIT ?");
            // On lie le paramètre limit en tant qu'entier
            $stmt->bindValue(1, $userId, PDO::PARAM_INT);
            $stmt->bindValue(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Order');
        } catch (PDOException $e) {
            error_log("Erreur getUserRecentOrders : " . $e->getMessage());
            return null;
        }
    }

    // (Pour getUserStats, la requête SQL dépendra de comment sont structurées vos tables "orders" et "order_items")
    public static function getUserStats($pdo, $userId)
    {
        // Exemple basique
        return [
            'totalOrders' => 4,
            'totalItems' => 12,
            'favorite' => 'Saumon Spicy'
        ];
    }

    /**
     * Récupère le détail des recettes (quantité + nom) pour une commande donnée
     */
    public static function getOrderItemsDetails($pdo, $orderId)
    {
        try {
            // On fait une jointure entre order_items et recipes pour récupérer le nom de la recette
            $stmt = $pdo->prepare("
            SELECT oi.quantity, r.name 
            FROM `order_items` oi
            JOIN `recipes` r ON oi.recipeId = r.id
            WHERE oi.orderId = ?
        ");
            $stmt->execute([$orderId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getOrderItemsDetails : " . $e->getMessage());
            return null;
        }
    }
}




?>