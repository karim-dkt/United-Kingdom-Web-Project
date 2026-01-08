<?php 
try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=localhost;dbname=news_letter","root","");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    die("ERREUR DE CONNEXION : " . $e->getMessage());
}

// Import PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../../PHPMailer/src/SMTP.php';
require __DIR__ . '/../../PHPMailer/src/Exception.php';

// --------------------------------------------------------------
//                       SUPPRESSION
// --------------------------------------------------------------
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    $sql_delete = "DELETE FROM news WHERE news_id = :id";
    $stmt_delete = $conn->prepare($sql_delete);
    $delete_var = $stmt_delete->execute([':id' => $delete_id]);

    if ($delete_var) {
        echo "<script>alert('News supprimée avec succès.');</script>";
    }
}

// --------------------------------------------------------------
//                   AJOUT + ENVOI AUTOMATIQUE
// --------------------------------------------------------------
if (isset($_POST['submit'])) {

    $titre = trim($_POST['titre']);
    $resumee = trim($_POST['resumee']);
    $contenu = trim($_POST['contenu']);

    // Ajout dans la base
    $stmt = $conn->prepare("INSERT INTO news (titre, resumee, contenu) VALUES (:titre, :resumee, :contenu)");
    $insert_var = $stmt->execute([
        ':titre' => $titre,
        ':resumee' => $resumee,
        ':contenu' => $contenu
    ]);

    if ($insert_var) {

        // Récupération de l’ID de la news ajoutée
        $news_id = $conn->lastInsertId();

        // Construire le lien vers l’article
        $lien_article = "http://localhost/Projet_dev_web_copie/HTML/article.php?id=$news_id";

        // Récupérer tous les abonnés
        $emails = $conn->query("SELECT email FROM Internaute")
                       ->fetchAll(PDO::FETCH_COLUMN);

        foreach ($emails as $mail_dest) {

            $mail = new PHPMailer(true);

            try {
                // Config SMTP
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;
                $mail->Username = "thekardiak@gmail.com";
                $mail->Password = "wrgz hpor fbxm dvhc"; // mot de passe application
                $mail->SMTPSecure = "tls";
                $mail->Port = 587;

                $mail->setFrom("thekardiak@gmail.com", "Site Royaume-Uni");
                $mail->addAddress($mail_dest);

                $mail->isHTML(true);
                $mail->Subject = "Nouvel article disponible : $titre";

                $mail->Body = "
                    <div style='font-family: Arial; padding: 10px; line-height: 1.6;'>
                        <h1 style='color:#2c3e50; text-align:center;'>📢 NOUVEL ARTICLE PUBLIÉ</h1>

                        <h2 style='color:#34495e;'>$titre</h2>

                        <p><strong>Résumé :</strong><br>$resumee</p>

                        <p>
                            Pour lire l’article complet, cliquez ici :<br>
                            <a href='$lien_article' style='color:#1b4d9b; font-weight:bold;'>👉 Lire l’article complet</a>
                        </p>

                        <p style='margin-top:20px;'>
                            Merci pour votre fidélité à notre newsletter 💙
                        </p>
                    </div>
                ";

                $mail->send();

            } catch (Exception $e) {
                // Optionnel : enregistrer l’erreur dans un fichier log
            }
        }

        echo "<script>alert('News ajoutée et envoyée aux abonnés !');</script>";
    }
}

// --------------------------------------------------------------
//                   RÉCUPÉRATION POUR MODIFICATION
// --------------------------------------------------------------
$news = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT news_id, titre, resumee, contenu FROM news WHERE news_id = :id");
    $stmt->execute([':id' => $id]);
    $news = $stmt->fetch();

    if (!$news) {
        die("News introuvable.");
    }
}

// --------------------------------------------------------------
//                         MISE À JOUR
// --------------------------------------------------------------
if (isset($_POST['update'])) {

    $id = intval($_GET['id']);
    $titre = $_POST['titre'];
    $resumee = $_POST['resumee'];
    $contenu = $_POST['contenu'];

    $stmt_up = $conn->prepare("
        UPDATE news 
        SET titre = ?, resumee = ?, contenu = ?
        WHERE news_id = ?
    ");

    $update_var = $stmt_up->execute([$titre, $resumee, $contenu, $id]);

    if ($update_var) {
        echo "<script>alert('News mise à jour avec succès');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace d'administration du site'</title>
    <link rel="stylesheet" href="../../CSS/Acceuil.css">
    <link rel="stylesheet" href="../../CSS/footer.css">
    <link rel="stylesheet" href="../../CSS/coup_de_coeur.CSS">
    <link rel="stylesheet" href="../../CSS/newsletter_gestion_css/page_administration_site_karim.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Yatra+One&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <img src="../../images/flag.png" alt="Drapeau Royaume-Uni" style="height: 60px;">

    <nav style="width: 170px;">
            <a href="../Accueil.php" title="Deconnexion">Déconnexion</a>
    </nav>
    </header>
<br><br>
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
                <li><a href="../london_city.php">Londres</a></li>
                <li><a href="../edimbourg_city.php">Edimbourg</a></li>
                <li><a href="../belfast_city.php">Belfast</a></li>
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
                <li><a href="../Bigben.php">Big Ben et Palais de Westminster</a></li>
                <li><a href="../british_museum.php">Musée britannique</a></li>
                <li><a href="../shard.php">Shard</a></li>
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

            <!-- Espace d'insertion de la base de donnée-->
             <div class="article-wrapper"> 
                <ul class="position-list">
                    <li data-target="article-wrapper" style="background-color: #0044ff; color: white;">Publication</li>
                    <li data-target="modification">Modification</li>
                    <li data-target="news-management-section">Suppression</li>
                </ul>

            <div class="article-header">
                <div class="article-info">
                    <h1>Espace Administrateur-Bienvenue Monsieur</h1>
                    <p class="subtitle">Partagez vos actualités, vos idées et vos contenus avec les visiteurs de ce site.</p>

                    <p class="description">
                        Ci-dessous le formulaire qui vous permet de publier une news sur votre site, assurez-vous de correctement saisir
                        les informations que vous souhaitez inserez, vous pouvez juste ajouter ou supprimer une news et pas en modifier.
                    </p>
                </div>

                <div class="article-image">
                    <img src="../../images/insert_news_letter.png" alt="">
                </div>
            </div>
                    <hr class="separator">
                    <h2 class="form-title">Formulaire de publication d'un article</h2> <br><br>
                <?php if (isset($insert_var)) : ?>
                    <?php if ($insert_var === true) : ?>
                        <div id="insert-alert" class="alert success" style="margin:10px 0;padding:10px;border-radius:4px; width:95%; background:#15a43d;color:#ffffff; top: 380px ; left: 20px; /* le div se place juste après le titre du formulaire */">
                            <strong>Succès :</strong> L’article a été publié avec succès.
                        </div>
                    <?php else : ?>
                        <div id="insert-alert" class="alert fail" style="margin:10px 0;padding:10px;border-radius:4px;background:#e42e2e;color:white;">
                            <strong>Erreur :</strong> La publication a échoué.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
<script>
(function() {
    var el = document.getElementById('insert-alert');
    if (!el) return;
    // délai avant début du fondu (en ms)
    var showDuration = 4000;
    // durée du fondu (doit correspondre à la transition CSS ci-dessous)
    var fadeDuration = 600;     
    // lance le fondu après showDuration
    setTimeout(function(){  
        el.style.transition = 'opacity ' + (fadeDuration/1000) + 's';                
        el.style.opacity = 0;
    }, showDuration);
})();
</script>

                    <form action="" method="post" class="styled-form">

                        <div class="form-group">
                            <label for="titre">Titre de l’article</label>
                            <input type="text" id="titre" name="titre" placeholder="Entrez le titre de votre article,choisissez un titre captivant pour attirer l'attention de vos visiteurs" required>
                        </div>

                        <div class="form-group">
                            <label for="resumee">Résumé</label>
                            <textarea id="resumee" name="resumee" rows="4" placeholder="Écrivez un court résumé " required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="contenu">Contenu complet</label>
                            <textarea id="contenu" name="contenu" rows="8" placeholder="Rédigez le contenu complet ici..." required></textarea>
                        </div>

                        <button type="submit" name="submit" class="submit-btn">Publier l’article</button>

                    </form>
         </div>   
        <div class="news-management-section">
                <ul class="position-list">
                    <li data-target="article-wrapper">Publication</li>
                    <li data-target="modification">Modification</li>
                    <li data-target="news-management-section" style="background-color: #0044ff; color: white;">Suppression</li>
                </ul>

            <?php if (isset($delete_var)) : ?>
                <?php if ($delete_var === true) : ?>
                    <div id="update-alert" class="alert success" style="margin:10px 0;padding:10px;border-radius:4px;background:#15a43d;color:#ffffff;">
                        <strong>Succès :</strong> La news a été supprimée avec succès.
                    </div>
                <?php else : ?>
                    <div id="update-alert" class="alert fail" style="margin:10px 0;padding:10px;border-radius:4px;background:#e42e2e;color:white;">
                        <strong>Erreur :</strong> La suppression a échoué.
                    </div>
                <?php endif; ?>
            <?php endif; ?>
<script>
(function() {
    var el = document.getElementById('update-alert');
    if (!el) return;

    // force l'affichage de la fenêtre active "Suppression" après chargement
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof showBlock === 'function' && Array.isArray(blocs)) {
            // index 2 = news-management-section (suppression)
            showBlock(blocs[2]);
        }
    });

    // retire le paramètre delete_id de l'URL pour éviter réexécution au refresh
    if (history.replaceState) {
        var url = new URL(window.location);
        url.searchParams.delete('delete_id');
        window.history.replaceState({}, document.title, url.toString());
    }

    var showDuration = 4000, fadeDuration = 600;
    setTimeout(function(){
        el.style.transition = 'opacity ' + (fadeDuration/1000) + 's';
        el.style.opacity = '0';
    }, showDuration);

    setTimeout(function(){
        if (el && el.parentNode) el.parentNode.removeChild(el);
    }, showDuration + fadeDuration + 50);
})();
</script> 

                <h2>Gestion des News Publiées</h2>
                <p>Ci-dessous la liste des news actuellement publiées sur le site. Vous pouvez les retirer si nécessaire.</p>
            <!-- Table des news existantes -->
            
            <table class="news-table">
                <tr>
                    <th style='background-color:#0044ff; color:white;'>Date</th>
                    <th style='background-color:#0044ff; color:white;'>Titre</th>
                    <th style='background-color:#0044ff; color:white;'>Action</th>
                </tr>+

                <?php
                $rows = $conn->query("SELECT news_id, titre, date_publication FROM news ORDER BY date_publication DESC ;");

                    while($art = $rows->fetch()) {
                        echo "<tr>";
                        echo "<td>".$art['date_publication']."</td>";
                        echo "<td>".$art['titre']."</td>";
                        echo "<td><a href='page_for_admin.php?delete_id=" . $art['news_id'] . "' class='delete-btn' style='color:red ;' onclick='return confirm(\"Voulez-vous vraiment retirer cet article ?\");' >Retirer</a></td>";
                        echo "</tr>";
                    }
                ?>
                </table>
        </div>
        <div class="modification" >
                <ul class="position-list">
                    <li data-target="article-wrapper">Publication</li>
                    <li data-target="modification" style="background-color: #0044ff; color: white;">Modification</li>
                    <li data-target="news-management-section">Suppression</li>
                </ul>

<?php if (isset($update_var)) : ?>
    <?php if ($update_var === true) : ?>
        <div id="update-alert" class="alert success" style="margin:10px 0;padding:10px;border-radius:4px;background:#15a43d;color:#ffffff;">
            <strong>Succès :</strong> La news a été mise à jour avec succès. Vous serez redirigé vers la liste des articles; section-modifications.
        </div>
    <?php else : ?>
        <div id="update-alert" class="alert fail" style="margin:10px 0;padding:10px;border-radius:4px;background:#e42e2e;color:white;">
            <strong>Erreur :</strong> La mise à jour a échoué.
        </div>
    <?php endif; ?>
<?php endif; ?>
<script>
(function() {
    var el = document.getElementById('update-alert');
    if (!el) return;

            // délai avant début du fondu (en ms)
            var showDuration = 4000;
            // durée du fondu (doit correspondre à la transition CSS ci-dessous)
            var fadeDuration = 600;

            // lance le fondu après showDuration
            setTimeout(function(){
                el.style.transition = 'opacity ' + (fadeDuration/1000) + 's';
                el.style.opacity = '0';
            }, showDuration);

            // après la disparition, on supprime l'alerte puis on redirige vers la page "modification" sans paramètre id
            setTimeout(function(){
                if (el && el.parentNode) el.parentNode.removeChild(el);

                // redirige vers la même page sans query string et avec ancre vers le tableau de modification
                var newUrl = window.location.pathname + '#modification';
                // use replace to avoid keeping the edit URL in history
                window.location.replace(newUrl);
            }, showDuration + fadeDuration + 50);
        })();
    </script>
            <div id="modification_block">
                <h2>Modifier une actualité</h2>
                <p>Sélectionnez une actualité à modifier dans la liste ci-dessous. Apportez les modifications nécessaires et enregistrez-les.</p>
                <!-- Table des news existantes pour modification -->
            <table class="news-table" id="modification" style="margin:auto; border-collapse:collapse;">
                <tr>
                    <th  style='background-color:#0044ff; color:white;'>Date</th>
                    <th style='background-color:#0044ff; color:white;'>Titre</th>
                    <th style='background-color:#0044ff; color:white;'>Action</th>
                </tr>

                <?php 
                $reqq = $conn->query("SELECT news_id, titre, date_publication FROM news ORDER BY date_publication DESC ;");
                    while($row = $reqq->fetch()): ?>
                    <tr>
                        <td style='white-space: nowrap;'><?= htmlspecialchars($row['date_publication']) ?></td>
                        <td><?= htmlspecialchars($row['titre']) ?></td>
                        <td>
                            <a href='page_for_admin.php?id=<?= $row['news_id'] ?>' class='edit-btn' style='color:blue;'>Modifier</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
            </div>
            <?php if ($news):
             ?>
            <script>document.getElementById('modification_block').style.display = 'none';</script>
            <h1>Modifier l'article</h1>
            <h4 style="color: rgb(52, 51, 51);">Vous avez selectionnez un article en vue d'une modification dans la liste des articles que vous avez publiez.
                Veuillez apporter les modifications nécessaires et cliquer sur "Mettre à jour" pour enregistrer les changements.
            </h4>

            <form class="styled-form" action="page_for_admin.php?id=<?= $news['news_id'] ?>" method="post">

                <div class="form-group">
                    <label for="titre">Titre de l’article</label>
                    <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($news['titre']) ?>" required>
                </div>  
                <div class="form-group">
                    <label for="resumee">Résumé</label>
                    <textarea id="resumee" name="resumee" rows="4" required><?= htmlspecialchars($news['resumee']) ?></textarea>
                </div>               
                <div class="form-group">
                    <label for="contenu">Contenu complet</label>
                    <textarea id="contenu" name="contenu" rows="8" required><?= htmlspecialchars($news['contenu']) ?></textarea>
                </div>
                <button type="submit" name="update" class="submit-btn">Mettre à jour</button>
            </form>
            <?php $news=null; endif; ?>

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
<script src ="../../JavaScript/menu_Vertical_Acceuil.js"></script>
<script src="../../JavaScript/page_administration.js"></script>
<script>

</script>
</body>
</html>