<?php if(session_status() != PHP_SESSION_ACTIVE) session_start(); ?>

<p class="font-weight-bold text-success">
    <?php
    if (!empty($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    } ?>
</p>
<p class="font-weight-bold text-success">
    <?php
    if (!empty($_SESSION['error'])) {
        echo $_SESSION['error'];
        unset($_SESSION['error']);
    } ?>
</p>