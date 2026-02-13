<?php
function printLoginForm($askedPage)
{
    echo "<form action=index.php?page=" . $askedPage . " method='post'>";
    echo <<<end
        <p>Nom d'utilisateur : <input type="text" name="login" placeholder="Nom d'utilisateur" required /></p>
        <p>Mot de passe : <input type="password" name="mdp" placeholder="Mot de passe" required /></p>
        <p><input type="submit" value="Valider" /></p>
        </form>
    end;

}

function printLogoutForm($askedPage)
{
    // $askedPage = "home";
    echo "<form action='index.php?page=" . $askedPage . "&todo=logOut' method='post'>";
    echo <<<end
        <p><input type="submit" value="Se déconnecter" /></p>
        </form>
    end;
}
?>