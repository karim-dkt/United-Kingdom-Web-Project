<?php 

try {
    $conn = new PDO("mysql:host=localhost;dbname=news_letter","root","") ;
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION) ;
}
catch(PDOException $e) {
    die( "ERREUR DE CONNEXION".$e->getMessage()) ;
}
$req_pageAcc = "select news_id,date_publication, titre from news ;" ;
$rows = $conn->query($req_pageAcc) ;

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Découvrir Londres</title>
    <link rel="stylesheet" href="../CSS/Acceuil.css">
    <link rel="stylesheet" href="../CSS/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Yatra+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/ville.css">
    
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
<br><br>
    <main>
            <div class="menu-vertical">
                <div>
                    <span class="icones"><svg class="icones" xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960"   width="60px" fill="#fff"><path d="M120-120v-555h165v-165h390v330h165v390H533v-165H427v165H120Zm60-60h105v-105H180v105Zm0-165h105v-105H180v105Zm0-165h105v-105H180v105Zm165 165h105v-105H345v105Zm0-165h105v-105H345v105Zm0-165h105v-105H345v105Zm165 330h105v-105H510v105Zm0-165h105v-105H510v105Zm0-165h105v-105H510v105Zm165 495h105v-105H675v105Zm0-165h105v-105H675v105Z"/></svg><span class="type_icon">Villes</span></span>
                     <ul class="icon-list">
                        <li><a href="london_city.php">Londres</a></li>
                        <li><a href="edimbourg_city.php">Edimbourg</a></li>
                        <li><a href="belfast_city.php">Belfast</a></li>
                    </ul>
                </div>
                <div>
                    <span  class="icones"><svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="60px" fill="#fff"><path d="M80-80v-80h80v-360H80v-80l400-280 400 280v80h-80v360h80v80H80Zm160-80h480-480Zm80-80h80v-160l80 120 80-120v160h80v-280h-80l-80 120-80-120h-80v280Zm400 80v-454L480-782 240-614v454h480Z"/></svg><span class="type_icon">Monuments</span></span>
                    <ul class="icon-list">
                         <li><a href="Bigben.php"> Big ben et palais de Westminster</a></li>
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
                    <span class="icones"><svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="60px" fill="#fff" ><path d="m305.96-305.96 271-76.08 76.08-271-271 76.08-76.08 271Zm173.51-144.76q-10.75 0-19.75-9.52-9-9.53-9-20.29 0-10.75 9.52-19.75 9.53-9 20.29-9 10.75 0 19.75 9.52 9 9.53 9 20.29 0 10.75-9.52 19.75-9.53 9-20.29 9Zm.47 387.92q-86.29 0-162.75-32.42-76.46-32.43-133-88.97-56.54-56.54-88.97-133.04Q62.8-393.74 62.8-480.11q0-86.62 32.51-162.81t89.12-132.72q56.62-56.53 133-89.16 76.38-32.63 162.66-32.63 86.64 0 162.85 32.5 76.22 32.51 132.74 89.3 56.52 56.8 89.14 133.09 32.61 76.3 32.61 162.82 0 86.49-32.63 162.57t-89.16 132.52q-56.53 56.45-132.84 89.14Q566.48-62.8 479.94-62.8Zm.5-77.31q142.36 0 240.91-98.99 98.54-98.98 98.54-241.34t-98.5-240.91q-98.5-98.54-240.86-98.54t-241.39 98.5q-99.03 98.5-99.03 240.86t98.99 241.39q98.98 99.03 241.34 99.03ZM480-480Z"/></svg><span class="type_icon">A visiter</span></span>
                    <ul class="icon-list">
                        <li><a href="https://www.visitbritain.com">Visit Britain</a></li>
                        <li><a href="https://www.britishmuseum.org">Site du musée</a></li>
                        <li><a href="https://www.ucas.com">Université UCAS</a></li>
                    </ul>
                </div>
            </div>
         <div class="page">

    <!-- Bandeau de titre -->
    <section class="hero-london">
        <div class="hero-text">
            <h1>Découvrir Londres</h1>
            <h2>Une capitale entre tradition et modernité</h2>
            <p>
                Londres est une ville-monde où l’histoire et l’innovation coexistent à chaque coin de rue. 
                Entre ses monuments emblématiques, ses quartiers animés et sa diversité culturelle, elle offre 
                une expérience unique aux visiteurs comme aux habitants.
            </p>
        </div>
        <div class="hero-image">
            <img src="../images/Villes/Londres/london1.jpg" alt="Vue panoramique de Londres">
        </div>
    </section>

    <!-- 3 cartes pour les grandes parties -->
    <section class="london-grid">

        <article class="london-card">
            <h3>Héritage historique</h3>
            <p>
                De la Tour de Londres à Buckingham Palace, la capitale britannique porte les traces de siècles 
                de monarchie, de révolutions et de transformations urbaines. Les bâtiments officiels, les ponts 
                emblématiques et les places publiques racontent l’évolution politique du Royaume-Uni.
            </p>
        </article>

        <article class="london-card">
            <h3>Quartiers emblématiques</h3>
            <p>
                Chaque quartier de Londres possède sa propre atmosphère : Westminster et ses institutions, 
                Camden et ses marchés alternatifs, City of London et ses gratte-ciels, Notting Hill et ses maisons 
                colorées. Cette diversité fait de la ville un véritable patchwork urbain.
            </p>
        </article>

        <article class="london-card">
            <h3>Culture et modernité</h3>
            <p>
                Musées gratuits, théâtres de renommée mondiale, salles de concert, street art 
                et innovations architecturales font de Londres une capitale tournée vers l’avenir. 
                La ville accueille chaque jour des événements artistiques, scientifiques et technologiques.
            </p>
        </article>

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

                      echo "<div class='news_div' style='font-size: 70%; width: 320px;border: 2px solid rgb(114, 111, 111);  border-radius: 10px ; box-shadow: none; '>";
                        echo "<div style='width: 290px;'><p >".$news["date_publication"]."</p>" ;
                        echo "<p style='margin-top: 12px;'>".$news["titre"]."</p></div>";
                        echo "<a href=\"article.php?news_id=" . $news["news_id"] . "\" style='font-size:100%; color: blue; padding: 15px 0;'>Cliquez pour voir l'article</a>";
                        echo "</div>" ;
                     $numb_row += 1 ;
                        if ($numb_row >=2){
                            echo "<div style='border: 2px solid rgb(114, 111, 111); width:320px ; border-radius: 8px ;'>" ;
                            echo "<p id='all_news' style='color: rgb(220, 151, 0); text-align: left; line-height: 40px; font-weight: 1000; height: 40px;'>"."<a href='./all_news.php' style='color: rgb(220, 151, 0); text-decoration: none;'> >>Toutes les news </a>"."</p>"."</div>" ;
                            break ;
                        }
                    } 
                ?>
            </div>
                <div id="conn_inscrip">
                    <a id="conn" href="./pages_gestion_newsLetter/connexion.php">Se connecter</a>
                    <a id="insc" href="./pages_gestion_newsLetter/inscription_nl.php">S'abonner</a>
                </div>
        </div>
    </main>
   <!-- Ligne séparatrice -->
   <div class="page_reste">
    <section class="highlights-london">
        <h2>À ne pas manquer lors d’une visite à Londres</h2>

        <!-- 1. Tower Bridge & la Tamise -->
        <div class="highlight-row">
            <div class="highlight-image">
                <figure class="highlight-figure">
                    <img src="../images/Villes/Londres/london3.webp" alt="Tower Bridge">
                    <figcaption>Vue sur Tower Bridge et la Tamise au coucher du soleil.</figcaption>
                </figure>
            </div>
            <div class="highlight-text">
                <h3>Tower Bridge & la Tamise</h3>
                <p>
    Symbole emblématique de Londres, le Tower Bridge s'élève majestueusement au-dessus de la Tamise, reliant les quartiers historiques de la City et de Southwark. 
    Construit à la fin du XIXe siècle dans un style néo-gothique, il allie élégance architecturale et prouesse technique grâce à son système basculant qui permet le passage des navires. 
    Traverser le Tower Bridge, c’est plonger au cœur de l’histoire londonienne : on découvre les tours imposantes, les passerelles suspendues offrant des vues panoramiques sur la Tamise et le Tower of London, ainsi que la vie animée du port de Londres. 
    Véritable passage incontournable, il illustre parfaitement la relation entre la ville et son fleuve, symbole de commerce, d’innovation et de culture urbaine.
</p>

                
                <p class="highlight-tip">
                    <strong>Astuce :</strong> venir tôt le matin ou en fin d’après-midi pour profiter de la lumière sur la Tamise.
                </p>
            </div>
        </div>

        <!-- 2. Les parcs royaux -->
        <div class="highlight-row reverse">
            <div class="highlight-text">
                <h3>Les parcs royaux</h3>
                <p>
    Les parcs royaux de Londres offrent de véritables havres de paix au cœur de l'effervescence urbaine. 
    Hyde Park, St James's Park, Kensington Gardens ou Regent's Park ne sont pas seulement des espaces verts, 
    mais aussi des lieux où la ville prend son souffle. On y croise des joggeurs matinaux, des familles en promenade, 
    des écureuils curieux qui se faufilent entre les passants, et parfois même des cérémonies officielles ou des concerts en plein air. 
    Ces jardins historiques, souvent liés à la monarchie, reflètent l'équilibre unique entre patrimoine, nature et vie citadine à Londres.
</p>

                <ul>
                    <li><strong>Hyde Park :</strong> idéal pour faire du vélo ou du pédalo sur le Serpentine.</li>
                    <li><strong>Regent’s Park :</strong> roseraie célèbre et accès au zoo de Londres.</li>
                    <li><strong>St James’s Park :</strong> vue directe sur Buckingham Palace et ses gardes.</li>
                </ul>
                <p class="highlight-tip">
                    <strong>Astuce :</strong> emporter un pique-nique et s’asseoir sur l’herbe comme les Londoniens.
                </p>
            </div>
            <div class="highlight-image">
                <figure class="highlight-figure">
                    <img src="../images/Villes/Londres/parks.jpg" alt="Hyde Park à Londres">
                    <figcaption>Un moment de calme dans les parcs royaux de Londres.</figcaption>
                </figure>
            </div>
        </div>

        <!-- 3. Marchés et vie urbaine -->
        <div class="highlight-row">
            <div class="highlight-image">
                <figure class="highlight-figure">
                    <img src="../images/Villes/Londres/camden2.jpg" alt="Camden Market">
                    <figcaption>Camden Market, mélange de street-food, friperies et street-art.</figcaption>
                </figure>
            </div>
            <div class="highlight-text">
                <h3>Marchés et vie urbaine</h3>
                <p>
    Les marchés londoniens sont bien plus que de simples lieux de commerce : ils reflètent l'âme cosmopolite de la capitale. 
    Borough Market séduit par ses produits frais et sa gastronomie du monde, Camden Market attire avec sa mode alternative et son ambiance jeune et décontractée, 
    tandis que Portobello Road Market charme par ses antiquités et ses trésors vintage. Chaque marché possède son style unique, 
    son public fidèle et offre une expérience sensorielle complète : couleurs, odeurs, saveurs et sons se mêlent pour capturer l'esprit vibrant de Londres.
</p>

                <ul>
                    <li><strong>Camden Market :</strong> stands de nourriture, vêtements, accessoires et musique.</li>
                    <li><strong>Portobello Road :</strong> marché aux antiquités et maisons colorées de Notting Hill.</li>
                    <li><strong>Borough Market :</strong> paradis des gourmands, entre produits frais et spécialités artisanales.</li>
                </ul>
                <p class="highlight-tip">
                    <strong>Astuce :</strong> venir en semaine ou tôt le week-end pour éviter la foule, surtout à Camden.
                </p>
            </div>
        </div>
    </section>
</div>

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