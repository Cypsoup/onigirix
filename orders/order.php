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
            error_log("Erreur dans la récupération de la commande : " . $e->getMessage());
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

}

?>