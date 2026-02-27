<?php
class User
{
    public $login;
    public $password;
    public $trigramme;
    public $nom;
    public $email;
    public $role;

    public static function getUtilisateur($dbh, $login)
    {
        $query = "SELECT * FROM `users` WHERE `login`=?";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'User');
        $sth->execute(array($login));
        $user = $sth->fetch();
        return $user;
    }

    public static function afficheUtilisateur($dbh, $login)
    {
        $user = User::getUtilisateur($dbh, $login);
        echo $user;
    }

    public static function insererUtilisateur($dbh, $login, $password, $trigramme, $nom, $email, $role)
    {
        if (User::getUtilisateur($dbh, $login) == null) {
            $sth = $dbh->prepare('INSERT INTO `users` (`login`, `password`, `trigramme`, `nom`, `email`, `role`) VALUES(?,?,?,?,?,?,?,?)');
            $sth->execute(array($login, $password /*password_hash($password, PASSWORD_DEFAULT)*/ , $trigramme, $nom, $email, $role));
        }
    }

    public static function testMdp($user, $password)
    {
        //return password_verify(password: $mdp, hash: $user->password);
        return ($password == $user->password);
    }
}
?>