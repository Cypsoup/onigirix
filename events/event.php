<?php

class Event
{
    public $id;
    public $name;
    public $isOpen;
    public $canOrder;
    public $dateEvent;

    public static function getEventById($dbh, $id)
    {
        try {
            $query = "SELECT * FROM `events` WHERE `id`=?";
            $sth = $dbh->prepare($query);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'Event');
            $sth->execute(array($id));
            $event = $sth->fetch();
            return $event;
        } catch (PDOException $e) {
            error_log("Erreur getEventById : " . $e->getMessage());
            return null;
        }
    }

    public static function getEventByName($dbh, $name)
    {
        try {
            $query = "SELECT * FROM `events` WHERE `name`=? LIMIT 1";
            $sth = $dbh->prepare($query);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'Event');
            $sth->execute(array($name));
            $event = $sth->fetch();
            return $event;
        } catch (PDOException $e) {
            error_log("Erreur getEventByName : " . $e->getMessage());
            return null;
        }
    }

    public static function getAllEvents($dbh)
    {
        try {
            $query = "SELECT * FROM `events` ORDER BY id DESC";
            $sth = $dbh->prepare($query);
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_CLASS, 'Event');
        } catch (PDOException $e) {

            error_log("Erreur getAllEvents : " . $e->getMessage());
            return [];
        }
    }

    public static function getOpenEvent($dbh)
    {
        try {
            $query = "SELECT * FROM `events` WHERE `isOpen`=1 LIMIT 1";
            $sth = $dbh->prepare($query);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'Event');
            $sth->execute();
            $event = $sth->fetch();
            return $event;
        } catch (PDOException $e) {
            error_log("Erreur getOpenEvent : " . $e->getMessage());
            return null;
        }
    }

    public static function openEvent($dbh, $id)
    {
        if (self::getOpenEvent($dbh)) { // On ne peut jamais avoir deux events d'ouvert simultanément
            error_log("Erreur openEvent : il existe déjà un event ouvert !");
            return false;
        }
        try {
            $query = "UPDATE `events` SET `isOpen` = 1 WHERE `id` = ?";
            $sth = $dbh->prepare($query);
            return $sth->execute(array($id));
        } catch (PDOException $e) {
            error_log("Erreur openEvent : " . $e->getMessage());
            return false;
        }
    }

    public static function closeEvent($dbh)
    {
        $openEvent = self::getOpenEvent($dbh);
        if (!$openEvent) { // Vérification qu'il existe bien un event d'ouvert
            error_log("Erreur closeEvent : il n'existe aucun event ouvert !");
            return false;
        }
        try {
            $query = "UPDATE `events` SET `isOpen` = 0 WHERE `id` = ?";
            $sth = $dbh->prepare($query);
            return $sth->execute(array($openEvent->id));
        } catch (PDOException $e) {
            error_log("Erreur closeEvent : " . $e->getMessage());
            return false;
        }
    }

    public static function openOrder($dbh)
    {
        $openEvent = self::getOpenEvent($dbh);
        if (!$openEvent) { // Vérification qu'il existe bien un event d'ouvert
            error_log("Erreur openOrder : il n'existe aucun event ouvert !");
            return false;
        }
        try {
            $query = "UPDATE `events` SET `canOrder` = 1 WHERE `id` = ?";
            $sth = $dbh->prepare($query);
            return $sth->execute(array($openEvent->id));
        } catch (PDOException $e) {
            error_log("Erreur openOrder : " . $e->getMessage());
            return false;
        }
    }

    public static function closeOrder($dbh)
    {
        $openEvent = self::getOpenEvent($dbh);
        if (!$openEvent) { // Vérification qu'il existe bien un event d'ouvert
            error_log("Erreur closeOrder : il n'existe aucun event ouvert !");
            return false;
        }
        try {
            $query = "UPDATE `events` SET `canOrder` = 0 WHERE `id` = ?";
            $sth = $dbh->prepare($query);
            return $sth->execute(array($openEvent->id));
        } catch (PDOException $e) {
            error_log("Erreur closeOrder : " . $e->getMessage());
            return false;
        }
    }

    public static function editEventName($dbh, $id, $newName)
    {
        try {
            $event = self::getEventById($dbh, $id);
            $query = "UPDATE `events` SET `name` = ? WHERE `id` = ?";
            $sth = $dbh->prepare($query);
            return $sth->execute(array($newName, $event->id));
        } catch (PDOException $e) {
            error_log("Erreur editEventName : " . $e->getMessage());
            return false;
        }
    }

    public static function createNewEvent($dbh, $name)
    {
        try {
            if (!self::getEventByName($dbh, $name)) {
                $sth = $dbh->prepare('INSERT INTO `events` (`name`) VALUES(?)');
                return $sth->execute(array($name));
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erreur createNewEvent : " . $e->getMessage());
            return null;
        }
    }
}

?>