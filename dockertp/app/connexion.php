<?php
    session_start();
    include('bd/connexionDB.php');


    if (isset($_SESSION['id'])){
        header('Location: index.php');
        exit;
    }

    if(!empty($_POST)) {
        extract($_POST);
        $valid = true;

        if (isset($_POST['connexion'])) {
            $mail = htmlentities(strtolower(trim($mail)));
            $mdp = trim($mdp);
        }

        if (empty($mail)) {
            $valid = false;
            $er_mail = "Il faut mettre un mail";
        }

        if (empty($mdp)) {
            $valid = false;
            $er_mdp = "Il faut mettre un mot de passe";
        }

        $req = $DB->query("SELECT *
            FROM utilisateur
            WHERE mail = ? AND mdp = ?",
            array($mail, crypt($mdp, "$6$rounds=5000$macleapersonnaliseretagardersecret$")));
        $req = $req->fetch();


        if ($req['id'] == "") {
            $valid = false;
            $er_mail = "Le mail ou le mot de passe est incorrecte";
        }


        if ($req['token'] <> NULL) {
            $valid = false;
            $er_mail = "Le compte n'a pas été validé";
        }

        if ($valid) {
            $_SESSION['id'] = $req['id'];
            $_SESSION['nom'] = $req['nom'];
            $_SESSION['prenom'] = $req['prenom'];
            $_SESSION['mail'] = $req['mail'];

            header('Location: index.php');
            exit;
        }
    }
?>





<!DOCTYPE html>
<html lang="fr">
     <head>
         <meta charset="utf-8">
         <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1">
         <title>Connexion</title>
     </head>
     <body>
        <div>Se connecter</div>
        <form method="post">
            <?php
                if (isset($er_mail)){
            ?>
                <div><?= $er_mail ?></div>
            <?php
                }
            ?>
            <input type="email" placeholder="Adresse mail" name="mail" value="<?php if(isset($mail)){ echo $mail; }?>" required>
            <?php
                if (isset($er_mdp)){
            ?>
                <div><?= $er_mdp ?></div>
            <?php
                 }
            ?>
            <input type="password" placeholder="Mot de passe" name="mdp" value="<?php if(isset($mdp)){ echo $mdp; }?>" required>
            <button type="submit" name="connexion">Se connecter</button>
        </form>
    </body>
</html>









