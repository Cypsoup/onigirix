<?php

class Order
{
    public $id;
    public $userId;
    public $eventId;
    public $status;
    public $totalAmount;
    public $createdAt;

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
                "SELECT r.nom, SUM(oi.quantity) as totalQty
                FROM `order_items` as oi
                JOIN `recipes` r ON oi.recipeId = r.id
                JOIN `orders` o ON oi.orderId = o.id
                WHERE o.status = ?
                GROUP BY r.id, r.nom
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