<?php

class Recipe
{
    public $id;
    public $name;
    public $fileName;
    public $description;
    public $prix;
    public $stock;
    public $available;

    public static function getRecipeById($dbh, $id)
    {
        $query = "SELECT * FROM `recipes` WHERE `id`=?";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Recipe');
        $sth->execute(array($id));
        $recipe = $sth->fetch();
        return $recipe;
    }

    public static function getRecipeByName($dbh, $name)
    {
        $query = "SELECT * FROM `recipes` WHERE ìd=?`";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Recipe');
        $sth->execute(array($name));
        $recipe = $sth->fetch();
        return $recipe;
    }

    public static function displayRecipe($dbh, $name)
    {
        $recipe = Recipe::getRecipeByName($dbh, $name);
        echo $recipe;
    }

    public static function insertRecipe($dbh, $id, $nom, $fileName, $description, $prix, $stock, $available)
    {
        if (Recipe::getRecipeById($dbh, $id) == null) {
            $sth = $dbh->prepare('INSERT INTO `recipes` (`id`, `nom`, `fileName`, `description`, `prix`, `stock`, `available`) VALUES(?,?,?,?,?,?,?,?,?)');
            $sth->execute(array($id, $nom, $fileName, $description, $prix, $stock, $available));
        }
    }

    public static function deleteRecipe($dbh, $id)
    {
        $recipe = Recipe::getRecipeById($dbh, $id);
        if ($recipe != null && $recipe->available == 0) {
            $dbh->delete($recipe); // A vérifier la fonction !!
        }
    }

    public static function archiveRecipe($dbh, $id)
    {
        $recipe = Recipe::getRecipeById($dbh, $id);
        if ($recipe != null && $recipe->available == 1) {
            $recipe->available = 0;
        }
    }

    public static function unpackRecipe($dbh, $id)
    {
        $recipe = Recipe::getRecipeById($dbh, $id);
        if ($recipe != null && $recipe->available == 0) {
            $recipe->available = 1;
        }
    }
}

?>