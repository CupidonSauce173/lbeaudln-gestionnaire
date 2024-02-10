<?php
if(!isset($firstName) || !isset($lastName)) {
    echo "Problème lors de l'affichage de la page";
    exit;
}
?>

<div class="col-md-2 d-none d-md-block sidebar">
    <div style="padding-top: 3vh">
        <h4 class="text-center">Bienvenue, <br/> <span
                class="user-name"> <?php echo $firstName . ' ' . $lastName ?></span></h4>
    </div>
    <div class="menu-section" style="padding-top: 5vh">
        <h4 class="ml-2">Clients</h4>
        <ul class="nav flex-column menuBtn text-center">
            <li><a href="/dashboard"><i class="fas fa-list"></i> Liste</a></li>
            <li><a href="/customer/add"><i class="fas fa-plus"></i> Ajouter</a></li>
            <li><a href="/customer/delete"><i class="fas fa-trash"></i> Supprimer</a></li>
            <li><a href="/customer/edit"><i class="fas fa-edit"></i> Modifier</a></li>
        </ul>
    </div>
    <div class="menu-section">
        <h4 class="ml-2">Votre compte</h4>
        <ul class="nav flex-column menuBtn text-center">
            <li><a href="/account/information"><i class="fas fa-info-circle"></i> Informations</a></li>
            <li><a href="/account/comments"><i class="fas fa-comments"></i> Commentaires</a></li>
            <li><a href="/account/security"><i class="fas fa-shield-alt"></i> Sécurité</a></li>
        </ul>
    </div>
    <div class="menu-section">
        <h4 class="ml-2">Utilisateurs</h4>
        <ul class="nav flex-column menuBtn text-center">
            <li><a href="/account/users"><i class="fas fa-users-cog"></i> Gérer utilisateurs</a></li>
        </ul>
    </div>
    <form action="/logout" method="post" id="logoutForm">
        <a class="btn-primary btn-lg text-decoration-none logoutBtn" href="javascript:"
           onclick="document.getElementById('logoutForm').submit()">Déconnexion</a>
    </form>
</div>