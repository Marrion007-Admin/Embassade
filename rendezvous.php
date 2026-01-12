<?php
// Page de prise de rendez-vous en ligne
$message = '';
$messageClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $date = htmlspecialchars(trim($_POST['date'] ?? ''));
    $objet = htmlspecialchars(trim($_POST['objet'] ?? ''));
    
    if ($nom && $email && $date && $objet) {
        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Veuillez saisir une adresse email valide.";
            $messageClass = 'error';
        } else {
            // Vérification si le dossier data existe
            $dataDir = __DIR__ . '/data';
            if (!is_dir($dataDir)) {
                mkdir($dataDir, 0755, true);
            }
            
            // Formatage de la ligne CSV
            $line = sprintf("%s,%s,%s,%s,%s\n", 
                $nom, 
                $email, 
                $date, 
                $objet, 
                date('Y-m-d H:i:s')
            );
            
            if (file_put_contents($dataDir . '/rendezvous.csv', $line, FILE_APPEND | LOCK_EX)) {
                $message = "✓ Votre rendez-vous a bien été enregistré. Un email de confirmation vous sera envoyé.";
                $messageClass = 'success';
            } else {
                $message = "Une erreur est survenue lors de l'enregistrement. Veuillez réessayer.";
                $messageClass = 'error';
            }
        }
    } else {
        $message = "⚠️ Veuillez remplir tous les champs obligatoires.";
        $messageClass = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Prendre rendez-vous - Service Consulaire</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Prenez rendez-vous en ligne pour vos démarches consulaires">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Variables et reset */
        :root {
            --primary-blue: #1e3a8a;
            --primary-green: #059669;
            --secondary-blue: #3b82f6;
            --light-blue: #eff6ff;
            --light-green: #f0fdf4;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --border-color: #e2e8f0;
            --success-bg: #f0fdf4;
            --error-bg: #fef2f2;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 25px -5px rgba(30, 58, 138, 0.15);
            --radius-lg: 1.5rem;
            --radius-md: 0.75rem;
            --radius-sm: 0.5rem;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f0f9ff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            color: var(--text-dark);
            position: relative;
            overflow-x: hidden;
        }

        /* Arrière-plan décoratif */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(30, 58, 138, 0.03) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(5, 150, 105, 0.03) 0%, transparent 20%);
            z-index: 0;
        }

        /* Carte principale */
        .rdv-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-lg);
            border-radius: var(--radius-lg);
            padding: 2.5rem;
            max-width: 480px;
            width: 100%;
            position: relative;
            z-index: 1;
            animation: cardAppear 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            transition: transform 0.3s ease;
        }

        .rdv-card:hover {
            transform: translateY(-4px);
        }

        /* En-tête */
        .card-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .card-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .card-header h1 i {
            color: var(--primary-green);
            font-size: 1.8rem;
        }

        .subtitle {
            color: var(--text-light);
            font-size: 1.1rem;
            font-weight: 400;
            line-height: 1.5;
        }

        /* Messages */
        .message {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-weight: 500;
            text-align: center;
            animation: slideIn 0.5s ease;
            border-left: 4px solid transparent;
        }

        .message.success {
            background: var(--success-bg);
            color: var(--primary-green);
            border-left-color: var(--primary-green);
        }

        .message.error {
            background: var(--error-bg);
            color: #dc2626;
            border-left-color: #dc2626;
        }

        /* Formulaire */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-group label i {
            color: var(--primary-green);
            width: 20px;
            text-align: center;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--secondary-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-input:hover {
            border-color: #94a3b8;
        }

        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%231e3a8a' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px;
            padding-right: 2.5rem;
            cursor: pointer;
        }

        /* Bouton */
        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-green) 100%);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 58, 138, 0.25);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Lien retour */
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.75rem;
            border-radius: var(--radius-md);
        }

        .back-link:hover {
            color: var(--primary-green);
            background: var(--light-blue);
            transform: translateX(-5px);
        }

        /* Animations */
        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive */
        @media (max-width: 640px) {
            .rdv-card {
                padding: 2rem 1.5rem;
            }
            
            .card-header h1 {
                font-size: 1.8rem;
            }
            
            .subtitle {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 0.5rem;
            }
            
            .rdv-card {
                padding: 1.5rem 1.25rem;
                border-radius: 1rem;
            }
            
            .card-header h1 {
                font-size: 1.5rem;
            }
            
            .form-input {
                padding: 0.75rem;
            }
        }

        /* Date picker personnalisé */
        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.3s;
        }

        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>
    
    <div class="rdv-card">
        <div class="card-header">
            <h1><i class="fa-solid fa-calendar-check"></i> Prendre rendez-vous</h1>
            <p class="subtitle">Réservez votre créneau pour vos démarches consulaires</p>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo $messageClass; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" autocomplete="off" novalidate>
            <div class="form-group">
                <label for="nom"><i class="fa-solid fa-user"></i> Nom complet</label>
                <input type="text" id="nom" name="nom" class="form-input" required 
                       placeholder="Votre nom et prénom" value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="email"><i class="fa-solid fa-envelope"></i> Adresse email</label>
                <input type="email" id="email" name="email" class="form-input" required 
                       placeholder="exemple@email.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="date"><i class="fa-solid fa-calendar-day"></i> Date souhaitée</label>
                <input type="date" id="date" name="date" class="form-input" required 
                       min="<?php echo date('Y-m-d'); ?>" value="<?php echo htmlspecialchars($_POST['date'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="objet"><i class="fa-solid fa-list"></i> Objet du rendez-vous</label>
                <select id="objet" name="objet" class="form-input" required>
                    <option value="" disabled selected>-- Choisir une option --</option>
                    <option value="Visa" <?php echo (($_POST['objet'] ?? '') === 'Visa') ? 'selected' : ''; ?>>Visa</option>
                    <option value="Passeport" <?php echo (($_POST['objet'] ?? '') === 'Passeport') ? 'selected' : ''; ?>>Passeport</option>
                    <option value="Légalisation" <?php echo (($_POST['objet'] ?? '') === 'Légalisation') ? 'selected' : ''; ?>>Légalisation</option>
                    <option value="Carte consulaire" <?php echo (($_POST['objet'] ?? '') === 'Carte consulaire') ? 'selected' : ''; ?>>Carte consulaire</option>
                    <option value="Autre" <?php echo (($_POST['objet'] ?? '') === 'Autre') ? 'selected' : ''; ?>>Autre</option>
                </select>
            </div>
            
            <button type="submit" class="submit-btn">
                <i class="fa-solid fa-paper-plane"></i> Confirmer le rendez-vous
            </button>
        </form>
        
        <a href="index.php" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Retour à l'accueil
        </a>
    </div>
</body>
</html>