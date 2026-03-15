<?php
class User
{
    public $id;
    public $trigramme;
    public $name;
    public $email;
    public $password;
    public $role;

    public static function getUserById($dbh, $id)
    {
        try {
            $query = "SELECT * FROM `users` WHERE `id`=?";
            $sth = $dbh->prepare($query);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'User');
            $sth->execute(array($id));
            $user = $sth->fetch();
            return $user;
        } catch (PDOException $e) {
            error_log("Erreur getUserById : " . $e->getMessage());
            return null;
        }
    }

    public static function getUserByTrigramme($dbh, $tri)
    {
        try {
            $query = "SELECT * FROM `users` WHERE `trigramme`=?";
            $sth = $dbh->prepare($query);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'User');
            $sth->execute(array($tri));
            $user = $sth->fetch();
            return $user;
        } catch (PDOException $e) {
            error_log("Erreur getUserByTrigramme : " . $e->getMessage());
            return null;
        }
    }

    public static function createUser($dbh, $trigramme, $name, $email, $password, $role = 'user')
    {
        try {
            if (self::getUserByTrigramme($dbh, $trigramme) == null) {
                $trigramme = strtoupper($trigramme);
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sth = $dbh->prepare('INSERT INTO `users` (`trigramme`, `name`, `email`, `password`, `role`) VALUES(?,?,?,?,?)');
                return $sth->execute(array($trigramme, $name, $email, $hashedPassword, $role));
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erreur createUser : " . $e->getMessage());
            return null;
        }
    }

    public static function updateUserInfo($dbh, $id, $trigramme, $name, $email)
    {
        try {
            $query = "UPDATE `users` SET `trigramme`=?, `name`=?, `email`=? WHERE `id`=?";
            $params = [$trigramme, $name, $email, $id];
            $sth = $dbh->prepare($query);
            return $sth->execute($params);
        } catch (PDOException $e) {
            error_log("Erreur updateUserInfo :" . $e->getMessage());
            return false;
        }
    }

    public static function updateUserPassword($dbh, $id, $password)
    {
        try {
            $query = "UPDATE `users` SET `password`=? WHERE `id`=?";
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $params = [$hashedPassword, $id];
            $sth = $dbh->prepare($query);
            return $sth->execute($params);
        } catch (PDOException $e) {
            error_log("Erreur updateUserPassword :" . $e->getMessage());
            return false;
        }
    }

    public static function testPassword($user, $password)
    {
        return password_verify($password, $user->password);
        //return ($password == $user->password);
    }
}
?>