<?php
require_once "config/constante.php";
// Redirige vers index.php après DUREE_SPLASH secondes
date_default_timezone_set('Africa/Dakar');
$redirectUrl = URL_BASE . 'index.php';
$duration = defined('DUREE_SPLASH') ? DUREE_SPLASH : 5;
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo NOM_SITE; ?> - Ouverture</title>
    <link rel="icon" type="image/x-icon" href="<?php echo URL_DRAPEAU; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #fff;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .splash-logo {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            box-shadow: 0 2px 16px rgba(30,58,138,0.10), 0 1.5px 8px rgba(5,150,105,0.08);
            margin-bottom: 2rem;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: box-shadow 0.3s;
        }
        .splash-logo img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .splash-title {
            color: #1e293b;
            font-size: 2.1rem;
            font-weight: 700;
            margin-bottom: 0.7rem;
            text-align: center;
            letter-spacing: 0.5px;
        }
        .splash-desc {
            color: #64748b;
            font-size: 1.08rem;
            margin-bottom: 2.2rem;
            text-align: center;
        }
        .splash-loader {
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .splash-loader i {
            font-size: 2.1rem;
            color: #1e3a8a;
            animation: spin 1.1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = <?php echo json_encode($redirectUrl); ?>;
        }, <?php echo (int)$duration * 1000; ?>);
    </script>
</head>
<body>
    <div class="splash-logo">
        <img src="<?php echo URL_EMBLEM; ?>" alt="Logo" style="width:80px;height:80px;object-fit:contain;">
    </div>
    <div class="splash-title"><?php echo NOM_SITE; ?></div>
    <div class="splash-desc">Bienvenue sur la plateforme officielle de <?php echo NOM_SUJET; ?></div>
    <div class="splash-loader">
        <i class="fas fa-spinner fa-spin"></i>
    </div>
</body>
</html>
