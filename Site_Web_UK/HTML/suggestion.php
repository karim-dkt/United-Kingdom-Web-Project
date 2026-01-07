<?php 
// -------------------- 1. CONNEXION ET IMPORT --------------------

// Paramètres de connexion
try {
    $conn = new PDO("mysql:host=localhost;dbname=news_letter","root","") ;
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION) ;
}
catch(PDOException $e) {
    // En cas d'erreur de connexion, on arrête l'exécution
    die("ERREUR DE CONNEXION: ".$e->getMessage());
}

// Import PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Les chemins ci-dessous doivent pointer vers votre dossier PHPMailer
// ATTENTION : Vérifiez le chemin exact par rapport à votre structure de dossier.
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';
require __DIR__ . '/../PHPMailer/src/Exception.php';

// Variable pour stocker le message de statut à afficher à l'utilisateur
$message_status = "";

// -------------------- 2. GESTION DU MESSAGE DE SUCCÈS APRÈS REDIRECTION --------------------

if (isset($_GET['status']) && $_GET['status'] == 'success') {
    // Affichage du message après une redirection réussie
    $message_status = "<p style='color: green; font-weight: bold;'>✅ Merci, votre suggestion a été enregistrée et transmise à l'administrateur.</p>";
}

// -------------------- 3. TRAITEMENT DU FORMULAIRE (Lors de la soumission POST) --------------------

if (isset($_POST['submit_suggestion'])) {
    
    // Récupération et nettoyage des données
    // Utilisation de htmlspecialchars pour prévenir XSS lors de la réinjection des valeurs si nécessaire
    $nom = trim(htmlspecialchars($_POST['nom'] ?? ''));
    $email = trim(htmlspecialchars($_POST['email'] ?? ''));
    $type_suggestion = htmlspecialchars($_POST['type_suggestion']);
    $suggestion = trim(htmlspecialchars($_POST['suggestion']));

    try {
        // --- A. Insertion dans la base de données ---
        $stmt_insert = $conn->prepare("
            INSERT INTO suggestions (nom, email, type_suggestion, contenu, date_soumission) 
            VALUES (:nom, :email, :type, :contenu, NOW())
        ");
        
        $insertion_reussie = $stmt_insert->execute([
            // Utilisation de '?' pour remplacer une chaîne vide par NULL pour la BDD (champs optionnels)
            ':nom' => !empty($nom) ? $nom : null, 
            ':email' => !empty($email) ? $email : null, 
            ':type' => $type_suggestion,
            ':contenu' => $suggestion
        ]);

        if ($insertion_reussie) {
            
            // --- B. Envoi d'e-mail à l'administrateur ---
            
            $mail = new PHPMailer(true);
            $admin_email = "votre_email_admin@exemple.com"; // **<<< À MODIFIER >>>**
            
            try {
                // Config SMTP (Réutilisation de vos paramètres Gmail)
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;
                $mail->Username = "thekardiak@gmail.com";  
                $mail->Password = "wrgz hpor fbxm dvhc";  // MDP application Gmail
                $mail->SMTPSecure = "tls";
                $mail->Port = 587;
                $mail->CharSet = 'UTF-8';

                $mail->setFrom("thekardiak@gmail.com", "Formulaire de Suggestions");
                $mail->addAddress($admin_email, "Administrateur"); 

                // Contenu de l'e-mail pour l'administrateur
                $mail->isHTML(true);
                $mail->Subject = "Nouvelle Suggestion (Type: $type_suggestion)";
                
                $mail_body = "
                    <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ccc;'>
                        <h2 style='color: #2c3e50;'>🔔 Nouvelle Suggestion Reçue</h2>
                        
                        <p><strong>Type:</strong> " . $type_suggestion . "</p>
                        <p><strong>Nom de l'utilisateur:</strong> " . (empty($nom) ? "Anonyme" : $nom) . "</p>
                        <p><strong>Email:</strong> " . (empty($email) ? "Non fourni" : $email) . "</p>
                        
                        <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
                        
                        <h3 style='color: #34495e;'>Contenu de la Suggestion :</h3>
                        <p style='white-space: pre-wrap; background-color: #f4f4f4; padding: 15px; border-radius: 5px;'>" . $suggestion . "</p>
                        
                        <p style='margin-top: 20px; font-size: 0.9em; color: #7f8c8d;'>
                            Reçue le : " . date('Y-m-d H:i:s') . "
                        </p>
                    </div>
                ";
                
                $mail->Body = $mail_body;
                $mail->send();
                
            } catch (Exception $e) {
                 // Si l'e-mail échoue, on loggue l'erreur pour débogage
                 error_log("Erreur envoi mail Admin (Suggestion): " . $e->getMessage());
            }

            // --- C. Redirection POST-SUCCESS (ESSENTIEL) ---
            // Redirige l'utilisateur vers la même page, mais avec un paramètre 'status=success'
            header("Location: suggestion.php?status=success");
            exit();
            
        } else {
             $message_status = "<p style='color: red; font-weight: bold;'>❌ Erreur : La suggestion n'a pas pu être enregistrée.</p>";
        }


    } catch (PDOException $e) {
        $message_status = "<p style='color: red; font-weight: bold;'>❌ Erreur de base de données : " . $e->getMessage() . "</p>";
    }
}

// NOTE : Votre code pour la section Nouveautés utilise la variable $rows qui n'est pas définie ici. 
// Pour éviter une erreur PHP, vous devez ajouter la requête pour $rows si vous voulez que la section fonctionne.
// Exemple :
// $stmt = $conn->query("SELECT news_id, titre, date_publication FROM news ORDER BY date_publication DESC");
// $rows = $stmt;

// Pour éviter l'erreur dans l'attente d'une requête réelle :
if (!isset($rows)) {
    $rows = new ArrayIterator([]); // Créer un itérateur vide pour éviter le crash
}
?>

<?php 

try {
    $conn = new PDO("mysql:host=localhost;dbname=news_letter","root","") ;
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION) ;
}
catch(PDOException $e) {
    die( "ERREUR DE CONNEXION".$e->getMessage()) ;
}
$req_pageAcc = "select news_id, date_publication, titre from news ;" ;
$rows = $conn->query($req_pageAcc) ;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suggestions et Commentaires</title>
    <link rel="stylesheet" href="../CSS/Acceuil.css">
    <link rel="stylesheet" href="../CSS/footer.css">
    <link rel="stylesheet" href="../CSS/suggestion.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Yatra+One&display=swap" rel="stylesheet">
</head>
<body>

<header>
    <img src="../images/flag.png" alt="Drapeau Royaume-Uni" style="height: 60px;">
    <nav>
        <a href="Accueil.php" title="Accueil">Accueil</a>
        <a href="Plan_du_site.php" title="Plan de site">Plan de site</a>
        <a href="who_we_are.php" title="Qui sommes-nous?">Qui sommes-nous ?</a>
        <a href="contact.php" title="Contact">Contact</a>
    </nav>
</header>

<main>
    <div class="menu-vertical">
        <div>
            <span class="icones">
                <svg class="icones" xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="60px" fill="#fff">
                    <path d="M120-120v-555h165v-165h390v330h165v390H533v-165H427v165H120Zm60-60h105v-105H180v105Zm0-165h105v-105H180v105Zm0-165h105v-105H180v105Zm165 165h105v-105H345v105Zm0-165h105v-105H345v105Zm0-165h105v-105H345v105Zm165 330h105v-105H510v105Zm0-165h105v-105H510v105Zm0-165h105v-105H510v105Zm165 495h105v-105H675v105Zm0-165h105v-105H675v105Z"/>
                </svg>
                <span class="type_icon">Villes</span>
            </span>
            <ul class="icon-list">
                <li><a href="london_city.php">Londres</a></li>
                <li><a href="edimbourg_city.php">Edimbourg</a></li>
                <li><a href="belfast_city.php">Belfast</a></li>
            </ul>
        </div>
        <div>
            <span class="icones">
                <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="60px" fill="#fff">
                    <path d="M80-80v-80h80v-360H80v-80l400-280 400 280v80h-80v360h80v80H80Zm160-80h480-480Zm80-80h80v-160l80 120 80-120v160h80v-280h-80l-80 120-80-120h-80v280Zm400 80v-454L480-782 240-614v454h480Z"/>
                </svg>
                <span class="type_icon">Monuments</span>
            </span>
            <ul class="icon-list">
                <li><a href="Bigben.php">Big Ben et Palais de Westminster</a></li>
                <li><a href="british_museum.php">Musée britannique</a></li>
                <li><a href="shard.php">Shard</a></li>
            </ul>
        </div>
         <div>
    <span class="icones">
        <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="60px" fill="#fff">
            <path d="m613.52-102.57-265.04-93.52-183.98 73.33q-21.63 10.96-41.28-1.6-19.65-12.55-19.65-37.42v-576.87q0-15.92 8.83-27.85 8.84-11.93 23.75-17.41l212.33-74.29 265.04 92.77 182.98-73.33q20.63-10.2 40.9 2.36 20.27 12.55 20.27 37.18v582.59q0 14.15-9.57 23.97-9.58 9.81-21.49 14.05l-213.09 76.04Zm-38.85-95.06v-502.78l-188.34-63.96v503.54l188.34 63.2Zm69.18 0 136.28-44.52v-510.78l-136.28 52.52v502.78Zm-462.98-11.2 136.28-52v-503.54l-136.28 45v510.54Zm462.98-491.58v502.78-502.78Zm-326.7-63.96v503.54-503.54Z"/>
        </svg>
        <span class="type_icon"><a href="carte.php" style="color: white; text-decoration-line: none ;">Carte</a></span>
    </span>
    <ul class="icon-list"> 
        <li><a href="carte.php">Carte UK</a></li>
    </ul>
</div>
        <div>
            <span class="icones">
                <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="60px" fill="#fff">
                    <path d="m305.96-305.96 271-76.08 76.08-271-271 76.08-76.08 271Zm173.51-144.76q-10.75 0-19.75-9.52-9-9.53-9-20.29 0-10.75 9.52-19.75 9.53-9 20.29-9 10.75 0 19.75 9.52 9 9.53 9 20.29 0 10.75-9.52 19.75-9.53 9-20.29 9Zm.47 387.92q-86.29 0-162.75-32.42-76.46-32.43-133-88.97-56.54-56.54-88.97-133.04Q62.8-393.74 62.8-480.11q0-86.62 32.51-162.81t89.12-132.72q56.62-56.53 133-89.16 76.38-32.63 162.66-32.63 86.64 0 162.85 32.5 76.22 32.51 132.74 89.3 56.52 56.8 89.14 133.09 32.61 76.3 32.61 162.82 0 86.49-32.63 162.57t-89.16 132.52q-56.53 56.45-132.84 89.14Q566.48-62.8 479.94-62.8Zm.5-77.31q142.36 0 240.91-98.99 98.54-98.98 98.54-241.34t-98.5-240.91q-98.5-98.54-240.86-98.54t-241.39 98.5q-99.03 98.5-99.03 240.86t98.99 241.39q98.98 99.03 241.34 99.03ZM480-480Z"/>
                </svg>
                <span class="type_icon">À visiter</span>
            </span>
            <ul class="icon-list">
                <li><a href="https://www.visitbritain.com">Visit Britain</a></li>
                <li><a href="https://www.britishmuseum.org">Site du musée</a></li>
                <li><a href="https://www.ucas.com">Université UCAS</a></li>
            </ul>
        </div>
    </div>
    
    <div class="page">
        <section class="suggestions-content">
            <div class="container">
                <h1>Envoyez-nous vos Suggestions</h1>
                <p class="intro-text">
                    Votre avis est essentiel pour améliorer notre site. Partagez vos idées, vos propositions de nouvelles fonctionnalités, ou signalez tout problème.
                </p>
                
                <?php echo $message_status; ?>

                <form action="suggestion.php" method="POST" class="suggestion-form">
                    
                    <div class="form-group">
                        <label for="nom">Votre Nom (Optionnel) :</label>
                        <input type="text" id="nom" name="nom" placeholder="Ex: Elhadj Barry" value="<?php echo isset($nom) ? $nom : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Votre Email (Optionnel, si vous souhaitez une réponse) :</label>
                         <input type="email" id="email" name="email" placeholder="votre.email@exemple.com" value="<?php echo isset($email) ? $email : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="type_suggestion">Type de Suggestion * :</label>
                        <select id="type_suggestion" name="type_suggestion" required>
                            <option value="" <?php echo (!isset($type_suggestion) || empty($type_suggestion)) ? 'selected' : ''; ?>>-- Sélectionnez un type --</option>
                            <option value="Amelioration" <?php echo (isset($type_suggestion) && $type_suggestion == 'Amelioration') ? 'selected' : ''; ?>>Amélioration/Fonctionnalité</option>
                            <option value="Contenu" <?php echo (isset($type_suggestion) && $type_suggestion == 'Contenu') ? 'selected' : ''; ?>>Idée de Contenu</option>
                            <option value="Bug" <?php echo (isset($type_suggestion) && $type_suggestion == 'Bug') ? 'selected' : ''; ?>>Rapport de Bug/Problème</option>
                            <option value="Autre" <?php echo (isset($type_suggestion) && $type_suggestion == 'Autre') ? 'selected' : ''; ?>>Autre</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="suggestion">Votre Suggestion * :</label>
                        <textarea id="suggestion" name="suggestion" rows="10" required placeholder="Décrivez votre idée ou problème ici..."><?php echo isset($suggestion) ? $suggestion : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" name="submit_suggestion" class="submit-btn">Envoyer la Suggestion</button>
                </form>
            </div>
        </section>
    </div>

  <div class="aside"> 
          <!--Insertion de la video et place des news-letter-->
          <video controls width="300px" height="300px" autoplay loop muted poster="../video/miniature.png">
            <source src="../video/video_uk.mp4" type="video/mp4">
            <source src="../video/video_uk.webm" type="video/webm">
            <source src="../video/video_uk.mp4" type="video/mp4">
          </video>
            <div class="bloc_nouveautes" style=" text-align: center; background-color: rgb(249, 246, 246);">
                <p style="font-size: 120%;"> Nouvautés  </p>
                <?php
                    $numb_row = 0 ;
                    while($news=$rows->fetch()) {

                        echo "<div class='news_div'>";
                        echo "<div style='width:290px;'><p >".$news["date_publication"]."</p>" ;
                        echo "<p >".$news["titre"]."</p></div>";
                        echo "<a href=\"article.php?news_id=" . $news["news_id"] . "\" style='font-size:100%; color: blue; padding: 15px 0;'>Cliquez pour voir l'article</a>";
                        echo "</div>" ;
                     $numb_row += 1 ;
                        if ($numb_row >=2){
                            echo "<div style='border: 2px solid rgb(114, 111, 111); width:320px ; border-radius: 10px ;'>" ;
                            echo "<p id='all_news' style='color: rgb(220, 151, 0); text-align: left; font-weight: 1000;'>"."<a href='./all_news.php' style='color: rgb(220, 151, 0); text-decoration: none;'> >>Toutes les news </a>"."</p>"."</div>" ;
                            break ;
                        }
                    } 
                ?>
                <div id="conn_inscrip">
                    <a id="conn" href="./pages_gestion_newsLetter/connexion.php">Se connecter</a>
                    <a id="insc" href="./pages_gestion_newsLetter/inscription_nl.php">S'abonner</a>
                </div>
        </div>
</main>


<section id="footer">
    <div class="footer-container">
        <div class="footer-person">
            <p><strong>Elhadj Aliou Barry</strong> </p>
            <p style="font-size:150%;color: rgb(199, 202, 202);;">Etudiant en Informatique</p>                
            <div class="contact_icone"> 
                <a class="logo_contact" href=""><i class="fa-brands fa-github fa-2xl"></i></a>
                <a class="logo_contact" href="mailto:elhadjaliou.barry@usmba.ac.ma"><i class="fa-regular fa-envelope fa-2xl"></i></a>
                <a class="logo_contact" href="https://wa.me/224624227101"><i class="fa-brands fa-whatsapp fa-2xl"></i></a>
                <a class="logo_contact" href="https://www.instagram.com/elhadj_ouli.a"><i class="fa-brands fa-instagram fa-2xl"></i></a>
            </div>
        </div>

        <div class="footer-person">
            <p><strong>Karim Diakité</strong></p>
            <p style="font-size:150%;color: rgb(199, 202, 202);">Etudiant en Informatique</p>
            <div class="contact_icone"> 
                <a class="logo_contact" href=""><i class="fa-brands fa-github fa-2xl"></i></a>
                <a class="logo_contact" href="mailto:karim.diakite@usmba.ac.ma"><i class="fa-regular fa-envelope fa-2xl"></i></a>
                <a class="logo_contact" href="https://wa.me/2120657145808"><i class="fa-brands fa-whatsapp fa-2xl"></i></a>
                <a class="logo_contact" href="https://www.instagram.com/thekardiak.00"><i class="fa-brands fa-instagram fa-2xl"></i></a>
            </div>
        </div>
    </div>

    <!-- Nouvelle section Suggestions et Conditions -->
    <div class="footer-links">
        <a href="suggestion.php">Suggestions</a>
        <a href="conditions_utilisation.php">Conditions d’utilisation</a>
    </div>

    <!-- Copyright en bas -->
    <div class="footer-bottom">
        <p>© 2014 - 2015 Projet-Programmation WEB — Tous droits réservés</p>
    </div>
</section>
<script src ="../JavaScript/menu_Vertical_Acceuil.js"></script>
</body>
</html>