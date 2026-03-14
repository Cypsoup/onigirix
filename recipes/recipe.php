<?php

class Recipe
{
    public $id;
    public $name;
    public $fileName;
    public $description;
    public $price;
    public $stock;
    public $available;

    public static function getRecipeById($dbh, $id)
    {
        try {
            $query = "SELECT * FROM `recipes` WHERE `id`=?";
            $sth = $dbh->prepare($query);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'Recipe');
            $sth->execute(array($id));
            $recipe = $sth->fetch();
            return $recipe;
        } catch (PDOException $e) {
            error_log("Erreur dans la réception du name de la recette : " . $e->getMessage());
            return null;
        }

    }

    public static function getRecipesByIds($dbh, array $ids)
    {
        if (empty($ids)) return [];

        try {
            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            
            $query = "SELECT * FROM `recipes` WHERE `id` IN ($placeholders)";
            $sth = $dbh->prepare($query);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'Recipe');
            $sth->execute($ids);
            
            // On range le résultat dans un tableau avec l'ID comme clé pour le retrouver facilement
            $recipes = [];
            while ($recipe = $sth->fetch()) {
                $recipes[$recipe->id] = $recipe;
            }
            return $recipes;
            
        } catch (PDOException $e) {
            error_log("Erreur getRecipesByIds : " . $e->getMessage());
            return [];
        }
    }

    public static function getRecipeByName($dbh, $name)
    {
        try {
            $query = "SELECT * FROM `recipes` WHERE `name=?` LIMIT 1";
            $sth = $dbh->prepare($query);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'Recipe');
            $sth->execute(array($name));
            $recipe = $sth->fetch();
            return $recipe;
        } catch (PDOException $e) {
            error_log("Erreur dans la réception du name de la recette : " . $e->getMessage());
            return null;
        }
    }

    public static function insertRecipe($dbh, $name, $fileName, $description, $price, $stock = 0, $available = 1)
    {
        try {
            $query = 'INSERT INTO `recipes` (`name`, `fileName`, `description`, `price`, `stock`, `available`) VALUES(?,?,?,?,?,?)';
            $sth = $dbh->prepare($query);
            return $sth->execute(array($name, $fileName, $description, $price, $stock, $available));
        } catch (PDOException $e) {
            error_log("Erreur lors de l'insertion : " . $e->getMessage());
            return false;
        }
    }

    public static function deleteRecipe($dbh, $id)
    {
        try {
            $query = "DELETE FROM `recipes` WHERE `id`=?";
            $sth = $dbh->prepare($query);
            return $sth->execute(array($id));
        } catch (PDOException $e) {
            error_log("Erreur dans la supression d'une recette : " . $e->getMessage());
            return false;
        }
    }

    public static function updateRecipe($dbh, $id, $name, $fileName, $description, $price)
    {
        try {
            if ($fileName) {
                $query = "UPDATE `recipes` SET `name`=?, `fileName`=?, `description`=?, `price`=? WHERE `id`=?";
                $params = [$name, $fileName, $description, $price, $id];
            } else {
                $query = "UPDATE `recipes` SET `name`=?, `description`=?, `price`=? WHERE `id`=?";
                $params = [$name, $description, $price, $id];
            }

            $sth = $dbh->prepare($query);
            return $sth->execute($params);
        } catch (PDOException $e) {
            error_log("Erreur dans la modification de la recette :" . $e->getMessage());
            return false;
        }
    }

    public static function getAllRecipes($dbh, $status)
    {
        try {
            $query = "SELECT * FROM `recipes` WHERE `available` = ?";
            $sth = $dbh->prepare($query);
            $sth->execute(array($status));
            return $sth->fetchAll(PDO::FETCH_CLASS, 'Recipe');
        } catch (PDOException $e) {
            error_log("Erreur dans la réception des recettes : " . $e->getMessage());
            echo "Recettes indisponibles pour le moment";
            return null;
        }
    }

    public static function updateAvailability($dbh, $id, $status)
    {
        try {
            $query = "UPDATE `recipes` SET `available` = ? WHERE `id` = ?";
            $sth = $dbh->prepare($query);
            return $sth->execute([$status, $id]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de la disponibilité : " . $e->getMessage());
            return false;
        }
    }
}

?>