<?php
class User
{
    public $id;
    public $trigramme;
    public $nom;
    public $email;
    public $password;
    public $role;

    public static function getUtilisateurById($dbh, $id)
    {
        try {
            $query = "SELECT * FROM `users` WHERE `id`=?";
            $sth = $dbh->prepare($query);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'User');
            $sth->execute(array($id));
            $user = $sth->fetch();
            return $user;
        } catch (PDOException $e) {
            error_log("Erreur dans la récupération de l'utilisateur : " . $e->getMessage());
            return null;
        }
    }

    public static function getUtilisateurByTrigramme($dbh, $tri)
    {
        try {
            $query = "SELECT * FROM `users` WHERE `trigramme`=?";
            $sth = $dbh->prepare($query);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'User');
            $sth->execute(array($tri));
            $user = $sth->fetch();
            return $user;
        } catch (PDOException $e) {
            error_log("Erreur dans la récupération de l'utilisateur : " . $e->getMessage());
            return null;
        }
    }

    public static function afficheUtilisateur($dbh, $id)
    {
        $user = User::getUtilisateurByID($dbh, $id);
        echo $user;
    }

    public static function insererUtilisateur($dbh, $tri, $nom, $email, $password, $role)
    {
        try {
            if (User::getUtilisateurByTrigramme($dbh, $tri) == null) {
                $sth = $dbh->prepare('INSERT INTO `users` (`id`, `trigramme`, `nom`, `email`, `password`, `role`) VALUES(?,?,?,?,?)');
                $sth->execute(array($tri, $nom, $email, $password /*password_hash($password, PASSWORD_DEFAULT)*/ , $role));
            }
        } catch (PDOException $e) {
            error_log("Erreur dans la création d'un utilisateur : " . $e->getMessage());
            return null;
        }
    }

    public static function testMdp($user, $password)
    {
        //return password_verify(password: $mdp, hash: $user->password);
        return ($password == $user->password);
    }
}
?>