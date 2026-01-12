<?php
require_once "config/constante.php";
// Splash si première visite ou inactivité > 1h
@session_start();
$cookieName = 'last_active';
$now = time();
$oneHour = 3600;
if (empty($_COOKIE[$cookieName]) || ($now - intval($_COOKIE[$cookieName]) > $oneHour)) {
    setcookie($cookieName, $now, $now + 365 * 24 * 3600, '/');
    header('Location: splash.php');
    exit;
}
setcookie($cookieName, $now, $now + 365 * 24 * 3600, '/');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo NOM_SITE; ?> - Portail de services consulaires</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo URL_DRAPEAU; ?>">

    <!-- Styles personnalisés -->
    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #dc2626;
            --accent-color: #059669;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        /* Navbar spécifique */
        .navbar-scroll {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Hero gradient */
        .hero-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        }

        /* Security badge */
        .security-badge {
            background: linear-gradient(45deg, #059669, #10b981);
        }

        /* Custom select */
        .custom-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Section spacing */
        .section-padding {
            padding-top: 5rem;
            padding-bottom: 5rem;
        }

        /* Logo animation */
        .logo-part {
            position: relative;
            overflow: hidden;
        }

        .logo-part::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: currentColor;
            transition: left 0.3s ease;
        }

        .logo:hover .logo-part::after {
            left: 0;
        }
    </style>

    <!-- Configuration Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e3a8a',
                        secondary: '#dc2626',
                        accent: '#059669'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in',
                        'slide-up': 'slideUp 0.3s ease-out'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            }
                        },
                        slideUp: {
                            '0%': {
                                transform: 'translateY(10px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            }
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans bg-gray-50">
    <!-- Barre supérieure avec langues, thème et drapeau -->
    <div class="bg-gray-100 border-b border-gray-200 py-1 px-4">
        <div class="container mx-auto flex items-center justify-between">
            <!-- Partie gauche : Drapeau et texte -->
            <div class="flex items-center space-x-3">
                <img src="<?php echo URL_DRAPEAU; ?>"
                    alt="Drapeau du <?php echo NOM_PAYS; ?>"
                    class="w-6 h-4 rounded shadow-sm">
                <span class="text-sm font-medium text-gray-700">
                    <?php echo NOM_SUJET; ?>
                </span>
            </div>

            <!-- Partie droite : Langues, thème et autres options -->
            <div class="flex items-center space-x-4">
                <!-- Sélecteur de langue amélioré -->
                <div class="relative z-100" id="langSelector">
                    <button id="langBtn" aria-haspopup="listbox" aria-expanded="false" class="flex items-center space-x-2 text-sm text-gray-700 hover:text-primary transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary rounded px-2 py-1" tabindex="0">
                        <span id="currentLangFlag" class="mr-1">
                            <i class="fi fi-fr fis"></i>
                        </span>
                        <span id="currentLang" data-i18n="langShort">FR</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <ul id="langMenu" class="absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden" role="listbox" aria-label="Choisir la langue">
                        <li>
                            <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition-colors" role="option" data-lang="FR">
                                <i class="fi fi-fr fis mr-2"><img src="public/images/france.png" alt="" style="width:20px;"></i><span data-i18n="langFR">FR</span>
                                <span class="ml-auto hidden" aria-hidden="true">✓</span>
                            </button>
                        </li>
                        <li>
                            <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition-colors" role="option" data-lang="EN">
                                <i class="fi fi-gb fis mr-2"><img src="public/images/usa.jpg" alt="" style="width: 20px;"></i><span data-i18n="langEN">EN</span>
                                <span class="ml-auto hidden" aria-hidden="true">✓</span>
                            </button>
                        </li>
                        <li>
                            <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition-colors" role="option" data-lang="LIN">
                                <i class="fi fi-cd fis mr-2"><img src="public/images/congo.png" alt="" style="width: 20px;"></i><span title="Lingala" data-i18n="langLIN">LIN</span>
                                <span class="ml-auto hidden" aria-hidden="true">✓</span>
                            </button>
                        </li>
                        <li>
                            <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition-colors" role="option" data-lang="ES">
                                <i class="fi fi-es fis mr-2"><img src="public/images/espagne.jpg" alt="" style="width: 20px;"></i><span title="Español" data-i18n="langES">ES</span>
                                <span class="ml-auto hidden" aria-hidden="true">✓</span>
                            </button>
                        </li>
                        <li>
                            <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition-colors" role="option" data-lang="PT">
                                <i class="fi fi-pt fis mr-2"><img src="public/images/portugual.png" alt="" style="width: 20px;"></i><span title="Português" data-i18n="langPT">PT</span>
                                <span class="ml-auto hidden" aria-hidden="true">✓</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Séparateur -->
                <div class="h-4 w-px bg-gray-300"></div>

                <!-- Sélecteur de thème -->
                <div class="flex items-center space-x-2">
                    <button id="themeToggle" class="p-1.5 transition-colors duration-200">
                        <i class="fas fa-moon text-gray-700 text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script pour le thème -->
    <script>
        // Gestion du thème
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = themeToggle.querySelector('i');

        themeToggle.addEventListener('click', () => {
            const html = document.documentElement;

            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                localStorage.setItem('theme', 'dark');
            }
        });

        // Charger le thème sauvegardé
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        });
    </script>

    <!-- Styles additionnels pour le thème sombre -->
    <style>
        .dark .bg-gray-100 {
            background-color: mediumpurple;
        }

        /* complete les autre logiques de couleurs pour le mode sombre pour toute la page 
         */


        /* .dark .border-gray-200 {
            border-color: #374151;
        }

        .dark .text-gray-700 {
            color: #d1d5db;
        }

        .dark .text-gray-600 {
            color: #9ca3af;
        }

        .dark .bg-gray-200 {
            background-color: #374151;
        }

        .dark .bg-white {
            background-color: #111827;
        }

        .dark .border-gray-200 {
            border-color: #4b5563;
        }

        .dark .text-gray-700 {
            color: #d1d5db;
        }

        .dark .hover\:bg-gray-100:hover {
            background-color: #374151;
        } */
    </style>
    <nav id="navbar" class="sticky top-0 left-0 right-0 bg-white py-2 shadow-md z-10 transition-all duration-300">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <a href="index.php" class="logo flex items-center space-x-3">
                    <img src="<?php echo URL_EMBLEM; ?>" alt="Drapeau du <?php echo NOM_PAYS; ?>" class="w-12 h-auto rounded">
                    <div class="flex items-center">
                        <span class="logo-part text-green-600 font-bold text-xl"><?php echo DETUT; ?></span>
                        <span class="logo-part text-yellow-500 font-bold text-xl"><?php echo SECOND; ?></span>
                        <span class="logo-part text-red-600 font-bold text-xl ml-1"><?php echo LAST; ?></span>
                    </div>
                </a>

                <!-- Menu Desktop -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="#" class="tracking-wide nav-link px-4 py-2 rounded-lg text-gray-700 hover:text-primary hover:bg-gray-100 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-home w-5"></i>
                        <span data-i18n="navAccueil">ACCUEIL</span>
                    </a>
                    <a href="#services" class="tracking-wide nav-link px-4 py-2 rounded-lg text-gray-700 hover:text-primary hover:bg-gray-100 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-concierge-bell w-5"></i>
                        <span data-i18n="navServices">SERVICES</span>
                    </a>
                    <a href="#contact" class="tracking-wide nav-link px-4 py-2 rounded-lg text-gray-700 hover:text-primary hover:bg-gray-100 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-address-card w-5"></i>
                        <span data-i18n="navContact">CONTACT</span>
                    </a>
                    <a href="login.php" class="ml-4 px-6 py-2 bg-primary text-white rounded-full hover:bg-blue-800 transition-colors duration-200 shadow-md hover:shadow-lg flex items-center space-x-2">
                        <i class="fas fa-sign-in-alt"></i>
                        <span data-i18n="navEspace">Espace personnel</span>
                    </a>
                </div>
                <!-- Bouton Mobile -->
                <button id="mobileMenuButton" class="lg:hidden p-2 rounded-lg text-gray-600 hover:text-primary hover:bg-gray-100 transition-colors duration-200">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Menu Mobile -->
            <div id="mobileMenu" class="lg:hidden hidden animate-slide-up bg-white shadow-lg rounded-lg mt-2 p-4">
                <div class="flex flex-col space-y-3">
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:text-primary hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-home w-5"></i>
                        <span data-i18n="mobileAccueil">Accueil</span>
                    </a>
                    <a href="#services" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:text-primary hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-concierge-bell w-5"></i>
                        <span data-i18n="mobileServices">Services</span>
                    </a>
                    <a href="#contact" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:text-primary hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-address-card w-5"></i>
                        <span data-i18n="mobileContact">Contact</span>
                    </a>
                    <a href="login.php" class="mt-2 px-6 py-3 bg-primary text-white rounded-full hover:bg-blue-800 transition-colors duration-200 shadow-md text-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        <span data-i18n="mobileEspace">Espace personnel</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <style>
        .fond-image {
            background-image: url('public/images/Explorer-la-Republique-du-Congo.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .fond-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(30, 58, 138, 0.7);
            /* Couche de couleur pour améliorer la lisibilité */
            z-index: 0;
        }

        .fond-image>div {
            position: relative;
            z-index: 1;
        }
    </style>

    <!-- Hero Section -->
    <section class="hero-gradient fond-image text-white pt-10 pb-16 md:pt-17 md:pb-24">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-12">
                <!-- Texte -->
                <div class="lg:w-1/2 animate-fade-in-up">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6" data-i18n="heroTitre">
                        Services consulaires en ligne de <?php echo NOM_SUJET; ?>
                    </h1>
                    <p class="text-xl md:text-2xl text-blue-100 mb-8 leading-relaxed" data-i18n="heroDesc">
                        Simplifiez vos démarches consulaires grâce à notre plateforme sécurisée et intuitive.
                        Prenez rendez-vous, déposez vos dossiers et effectuez vos paiements en toute confiance.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="rendezvous.php" class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary rounded-full hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-calendar-check mr-3 text-lg"></i>
                            <span class="font-semibold text-lg" data-i18n="btnRdv">Prendre rendez-vous</span>
                        </a>
                        <a href="depot.php" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white rounded-full hover:bg-white hover:text-primary transition-all duration-300 transform hover:-translate-y-1">
                            <i class="fas fa-cloud-upload-alt mr-3 text-lg"></i>
                            <span class="font-semibold text-lg" data-i18n="btnDepot">Déposer un dossier</span>
                        </a>
                    </div>
                </div>

                <!-- Carte de sécurité -->
                <div class="lg:w-1/2 animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-2xl">
                        <div class="flex items-center mb-8">
                            <div class="security-badge rounded-full p-4 mr-4">
                                <i class="fas fa-shield-alt text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800" data-i18n="carteSecuTitre">Plateforme 100% sécurisée</h3>
                                <p class="text-gray-600" data-i18n="carteSecuDesc">Chiffrement automatique de tous vos documents</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="p-4">
                                <div class="text-blue-600 mb-3">
                                    <i class="fas fa-clock text-3xl"></i>
                                </div>
                                <h4 class="font-bold text-gray-800 text-lg" data-i18n="carteSecu24">24h/24</h4>
                                <p class="text-sm text-gray-600" data-i18n="carteSecuDispo">Disponibilité</p>
                            </div>
                            <div class="p-4">
                                <div class="text-green-600 mb-3">
                                    <i class="fas fa-file-contract text-3xl"></i>
                                </div>
                                <h4 class="font-bold text-gray-800 text-lg" data-i18n="carteSecuDepot">Dépôt numérique</h4>
                                <p class="text-sm text-gray-600" data-i18n="carteSecuDocs">Documents sécurisés</p>
                            </div>
                            <div class="p-4">
                                <div class="text-yellow-600 mb-3">
                                    <i class="fas fa-lock text-3xl"></i>
                                </div>
                                <h4 class="font-bold text-gray-800 text-lg" data-i18n="carteSecuPaiement">Paiement sécurisé</h4>
                                <p class="text-sm text-gray-600" data-i18n="carteSecuTransac">Transactions cryptées</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section-padding bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-primary mb-4" data-i18n="servicesTitre">Services essentiels</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto" data-i18n="servicesDesc">
                    Une plateforme simplifiée concentrée sur l'essentiel pour des démarches consulaires rapides et sécurisées
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <!-- Rendez-vous -->
                <div class="card-hover bg-white rounded-2xl shadow-xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 p-4 rounded-full mr-4">
                            <i class="fas fa-calendar-alt text-primary text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800" data-i18n="serviceRdvTitre">Gestion des rendez-vous</h3>
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        <span data-i18n="serviceRdvDesc1">Prenez rendez-vous en ligne pour vos démarches consulaires en quelques clics.</span>
                        <span data-i18n="serviceRdvDesc2">Choisissez la date et l'horaire qui vous conviennent.</span>
                    </p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span data-i18n="serviceRdvList1">Calendrier disponible 24h/24</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span data-i18n="serviceRdvList2">Confirmation immédiate</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span data-i18n="serviceRdvList3">Rappels par email/SMS</span>
                        </li>
                    </ul>
                    <a href="rendezvous.php" class="inline-flex items-center justify-center w-full py-3 bg-primary text-white rounded-full hover:bg-blue-800 transition-colors duration-300 font-semibold">
                        <span data-i18n="serviceRdvBtn">Prendre rendez-vous</span>
                        <i class="fas fa-arrow-right ml-3"></i>
                    </a>
                </div>

                <!-- Dépôt numérique -->
                <div class="card-hover bg-white rounded-2xl shadow-xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-100 p-4 rounded-full mr-4">
                            <i class="fas fa-cloud-upload-alt text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800" data-i18n="serviceDepotTitre">Dépôt numérique sécurisé</h3>
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        <span data-i18n="serviceDepotDesc1">Déposez vos dossiers en ligne avec un chiffrement automatique de tous vos documents.</span>
                        <span data-i18n="serviceDepotDesc2">Vos données sont protégées dès leur téléversement.</span>
                    </p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span data-i18n="serviceDepotList1">Chiffrement AES-256</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span data-i18n="serviceDepotList2">Formats multiples acceptés</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span data-i18n="serviceDepotList3">Suivi en temps réel</span>
                        </li>
                    </ul>
                    <a href="depot.php" class="inline-flex items-center justify-center w-full py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors duration-300 font-semibold">
                        <span data-i18n="serviceDepotBtn">Déposer un dossier</span>
                        <i class="fas fa-arrow-right ml-3"></i>
                    </a>
                </div>

                <!-- Paiement -->
                <div class="card-hover bg-white rounded-2xl shadow-xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-yellow-100 p-4 rounded-full mr-4">
                            <i class="fas fa-credit-card text-yellow-600 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800" data-i18n="servicePaiementTitre">Paiement sécurisé</h3>
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        <span data-i18n="servicePaiementDesc1">Effectuez vos paiements en toute sécurité grâce à notre système de transaction crypté.</span>
                        <span data-i18n="servicePaiementDesc2">Plusieurs moyens de paiement acceptés.</span>
                    </p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span data-i18n="servicePaiementList1">Transactions cryptées</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span data-i18n="servicePaiementList2">Reçus numériques</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span data-i18n="servicePaiementList3">Support 7j/7</span>
                        </li>
                    </ul>
                    <a href="paiement.php" class="inline-flex items-center justify-center w-full py-3 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition-colors duration-300 font-semibold">
                        <span data-i18n="servicePaiementBtn">Payer en ligne</span>
                        <i class="fas fa-arrow-right ml-3"></i>
                    </a>
                </div>
            </div>

            <!-- Sécurité mention -->
            <div class="bg-blue-50 border-l-4 border-primary rounded-r-xl p-6 shadow-lg">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="mb-4 md:mb-0 md:mr-6">
                        <i class="fas fa-info-circle text-primary text-4xl"></i>
                    </div>
                    <div class="text-center md:text-left">
                        <h4 class="text-2xl font-bold text-gray-800 mb-2" data-i18n="simpliciteTitre">Simplicité et sécurité</h4>
                        <p class="text-gray-700 text-lg" data-i18n="simpliciteDesc">
                            L'absence volontaire de tableaux de bord complexes permet de renforcer la sécurité,
                            réduire les délais de développement et garantir une plateforme rapide et fiable.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section-padding bg-white">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Informations de contact -->
                <div>
                    <h2 class="text-4xl font-bold text-primary mb-8">Contactez <?php echo NOM_SUJET; ?></h2>

                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-start mb-4">
                                <div class="bg-primary p-3 rounded-full mr-4">
                                    <i class="fas fa-envelope text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-lg mb-1">Email</h4>
                                    <p class="text-gray-600 text-sm mb-2">Support technique</p>
                                    <a href="mailto:<?php echo EMAIL_SUPPORT; ?>" class="text-primary font-semibold hover:underline">
                                        <?php echo EMAIL_SUPPORT; ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-start mb-4">
                                <div class="bg-primary p-3 rounded-full mr-4">
                                    <i class="fas fa-phone text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-lg mb-1">Téléphone</h4>
                                    <p class="text-gray-600 text-sm mb-2">Assistance technique</p>
                                    <a href="tel:<?php echo TELEPHONE_SUPPORT; ?>" class="text-primary font-semibold hover:underline">
                                        <?php echo TELEPHONE_SUPPORT; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6 mb-8">
                        <div class="flex items-start">
                            <div class="bg-primary p-3 rounded-full mr-4">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-lg mb-2">Adresse postale</h4>
                                <p class="text-gray-700"><?php echo ADRESSE_POSTALE; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                        <h5 class="font-bold text-gray-800 text-lg mb-4 flex items-center">
                            <i class="fas fa-headset text-primary mr-3"></i>
                            Horaires d'assistance
                        </h5>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="font-semibold text-gray-700 mb-1">Lundi - Vendredi:</p>
                                <p class="text-gray-600">8h30 - 17h30</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-700 mb-1">Samedi:</p>
                                <p class="text-gray-600">9h00 - 13h00 (assistance téléphonique uniquement)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de contact -->
                <div class="bg-gray-50 rounded-2xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Message rapide</h3>
                    <form id="contactForm" class="space-y-6">
                        <div>
                            <label for="name" class="block text-gray-700 font-medium mb-2">Nom complet</label>
                            <input type="text" id="name" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-20 transition-colors duration-200" required>
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">Adresse email</label>
                            <input type="email" id="email" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-20 transition-colors duration-200" required>
                        </div>
                        <div>
                            <label for="subject" class="block text-gray-700 font-medium mb-2">Sujet</label>
                            <select id="subject" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-20 transition-colors duration-200 custom-select" required>
                                <option value="">Sélectionnez un sujet</option>
                                <option value="rendez-vous">Rendez-vous</option>
                                <option value="depot">Dépôt de dossier</option>
                                <option value="paiement">Paiement</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div>
                            <label for="message" class="block text-gray-700 font-medium mb-2">Message</label>
                            <textarea id="message" rows="4" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-20 transition-colors duration-200" required></textarea>
                        </div>
                        <button type="submit" class="w-full py-4 bg-primary text-white rounded-full hover:bg-blue-800 transition-colors duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-paper-plane mr-3"></i>
                            Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->

    <footer class="footer-bg-image text-white py-10 " style="position:relative;">
        <style>
            .footer-bg-image {
                background-image: url('public/images/drapeau2.avif');
                background-size: cover;
                background-position: center;
            }

            .footer-bg-image::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(30, 30, 30, 0.85);
                z-index: 1;
            }

            .footer-content {
                position: relative;
                z-index: 2;
            }
        </style>
        <div class="footer-content container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <!-- Bloc institutionnel -->
                <div>
                    <div class="flex items-center mb-4">
                        <img src='<?php echo URL_EMBLEM; ?>' alt="Emblème" class="w-16 h-auto rounded mr-3">
                        <h3 class="text-xl font-bold"><?php echo NOM_SITE; ?></h3>
                    </div>
                    <h1 class="text-lg font-bold mb-4 me-10">
                        Bienvenue sur le site officiel de <?php echo NOM_SUJET; ?>
                    </h1>
                    <p class="text-gray-200 mb-6">
                        Ce portail est une interface officielle d'information, de communication et d'interaction sur la politique étrangère du <?php echo NOM_PAYS; ?>.
                    </p>
                </div>
                <!-- Bloc Services -->
                <div>
                    <h4 class="text-xl font-bold mb-4">Services</h4>
                    <ul class="space-y-2">
                        <li><a href="rendezvous.php" class="text-gray-200 hover:text-yellow-400 transition-colors duration-200"> <i class="fas fa-caret-right mr-2"></i> Prendre rendez-vous</a></li>
                        <li><a href="#services" class="text-gray-200 hover:text-yellow-400 transition-colors duration-200"> <i class="fas fa-caret-right mr-2"></i> Documents officiels</a></li>
                        <li><a href="#services" class="text-gray-200 hover:text-yellow-400 transition-colors duration-200"> <i class="fas fa-caret-right mr-2"></i> Inscription consulaire</a></li>
                        <li><a href="#services" class="text-gray-200 hover:text-yellow-400 transition-colors duration-200"> <i class="fas fa-caret-right mr-2"></i> Légalisation et notariat</a></li>
                    </ul>
                </div>
                <!-- Bloc Contact -->
                <div>
                    <h4 class="text-xl font-bold mb-4">Contact</h4>
                    <ul class="space-y-3 text-gray-200">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3"></i>
                            <span><?php echo ADRESSE_POSTALE; ?></span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3"></i>
                            <span><?php echo TELEPHONE_SUPPORT; ?></span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3"></i>
                            <span><?php echo EMAIL_SUPPORT; ?></span>
                        </li>
                    </ul>
                    <div class="mt-4 flex space-x-4">
                        <a href="#" class="text-gray-200 hover:text-white"><i class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="text-gray-200 hover:text-white"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-200 hover:text-white"><i class="fab fa-instagram text-xl"></i></a>
                    </div>
                </div>
            </div>
            <hr class="border-white my-6">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <p class="text-gray-300 text-md mb-2 md:mb-0">&copy; <?php echo date('Y'); ?> <?php echo NOM_ENTREPRISE; ?>. Tous droits réservés.</p>
                <div class="flex space-x-4 text-gray-400 text-md">
                    <a href="#" class="hover:text-white">Mentions légales</a>
                    <a href="#" class="hover:text-white">Termes et Conditions</a>
                    <a href="#" class="hover:text-white">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript personnalisé -->
    <script>
        // Menu mobile
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const navbar = document.getElementById('navbar');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                const icon = mobileMenuButton.querySelector('i');
                if (mobileMenu.classList.contains('hidden')) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                } else {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                }
            });

            // Fermer le menu en cliquant sur un lien
            document.querySelectorAll('#mobileMenu a').forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                    mobileMenuButton.querySelector('i').classList.remove('fa-times');
                    mobileMenuButton.querySelector('i').classList.add('fa-bars');
                });
            });

            // Fermer le menu en cliquant à l'extérieur
            document.addEventListener('click', (event) => {
                if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                    mobileMenuButton.querySelector('i').classList.remove('fa-times');
                    mobileMenuButton.querySelector('i').classList.add('fa-bars');
                }
            });
        }

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scroll', 'shadow-lg');
                navbar.classList.remove('shadow-md');
            } else {
                navbar.classList.remove('navbar-scroll', 'shadow-lg');
                navbar.classList.add('shadow-md');
            }
        });

        // Gestion du formulaire de contact
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;

                // Simuler l'envoi
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Envoi en cours...';
                submitBtn.disabled = true;

                setTimeout(() => {
                    // Succès
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'mt-4 p-4 bg-green-100 text-green-700 rounded-lg animate-fade-in-up';
                    alertDiv.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-green-500"></i>
                            <div>
                                <p class="font-semibold">Message envoyé avec succès !</p>
                                <p class="text-sm">Nous vous répondrons dans les plus brefs délais.</p>
                            </div>
                        </div>
                    `;

                    this.insertBefore(alertDiv, this.firstChild);
                    this.reset();

                    // Restaurer le bouton
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    // Supprimer l'alerte après 5 secondes
                    setTimeout(() => {
                        alertDiv.remove();
                    }, 5000);
                }, 1500);
            });
        }

        // Animation des cartes au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                    observer.unobserve(entry.target); // pour que l'animation ne disparaisse jamais
                }
            });
        });

        // Observer les cartes de services
        document.querySelectorAll('.card-hover').forEach(card => {
            observer.observe(card);
        });


        // Active le lien de navigation actif
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');

        window.addEventListener('scroll', () => {
            let current = '';

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;

                if (scrollY >= (sectionTop - 100)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('text-primary', 'bg-gray-100');
                link.classList.add('text-gray-700');

                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.remove('text-gray-700');
                    link.classList.add('text-primary', 'bg-gray-100');
                }
            });

            // Activer le lien Accueil si on est en haut
            if (scrollY < 100) {
                navLinks.forEach(link => {
                    if (link.getAttribute('href') === '#') {
                        link.classList.remove('text-gray-700');
                        link.classList.add('text-primary', 'bg-gray-100');
                    }
                });
            }
        });

        // Initialiser l'animation des cartes visibles au chargement
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.card-hover').forEach(card => {
                if (card.getBoundingClientRect().top < window.innerHeight) {
                    card.classList.add('animate-fade-in-up');
                    card.style.opacity = '1';
                }
            });
        });

        // script pour langue
        // Squelette complet des traductions pour toutes les clés data-i18n
        const translations = {
            FR: {
                langShort: "FR",
                langFR: "FR",
                langEN: "EN",
                langLIN: "LIN",
                langES: "ES",
                langPT: "PT",
                logoDetut: "", // à remplir
                logoSecond: "", // à remplir
                logoLast: "", // à remplir
                navAccueil: "ACCUEIL",
                navServices: "SERVICES",
                navContact: "CONTACT",
                navEspace: "Espace personnel",
                mobileAccueil: "Accueil",
                mobileServices: "Services",
                mobileContact: "Contact",
                mobileEspace: "Espace personnel",
                heroTitre: "Services consulaires en ligne de ",
                heroDesc: "Simplifiez vos démarches consulaires grâce à notre plateforme sécurisée et intuitive.\nPrenez rendez-vous, déposez vos dossiers et effectuez vos paiements en toute confiance.",
                btnRdv: "Prendre rendez-vous",
                btnDepot: "Déposer un dossier",
                carteSecuTitre: "Plateforme 100% sécurisée",
                carteSecuDesc: "Chiffrement automatique de tous vos documents",
                carteSecu24: "24h/24",
                carteSecuDispo: "Disponibilité",
                carteSecuDepot: "Dépôt numérique",
                carteSecuDocs: "Documents sécurisés",
                carteSecuPaiement: "Paiement sécurisé",
                carteSecuTransac: "Transactions cryptées",
                servicesTitre: "Services essentiels",
                servicesDesc: "Une plateforme simplifiée concentrée sur l'essentiel pour des démarches consulaires rapides et sécurisées",
                serviceRdvTitre: "Gestion des rendez-vous",
                serviceRdvDesc1: "Prenez rendez-vous en ligne pour vos démarches consulaires en quelques clics.",
                serviceRdvDesc2: "Choisissez la date et l'horaire qui vous conviennent.",
                serviceRdvList1: "Calendrier disponible 24h/24",
                serviceRdvList2: "Confirmation immédiate",
                serviceRdvList3: "Rappels par email/SMS",
                serviceRdvBtn: "Prendre rendez-vous",
                serviceDepotTitre: "Dépôt numérique sécurisé",
                serviceDepotDesc1: "Déposez vos dossiers en ligne avec un chiffrement automatique de tous vos documents.",
                serviceDepotDesc2: "Vos données sont protégées dès leur téléversement.",
                serviceDepotList1: "Chiffrement AES-256",
                serviceDepotList2: "Formats multiples acceptés",
                serviceDepotList3: "Suivi en temps réel",
                serviceDepotBtn: "Déposer un dossier",
                servicePaiementTitre: "Paiement sécurisé",
                servicePaiementDesc1: "Effectuez vos paiements en toute sécurité grâce à notre système de transaction crypté.",
                servicePaiementDesc2: "Plusieurs moyens de paiement acceptés.",
                servicePaiementList1: "Transactions cryptées",
                servicePaiementList2: "Reçus numériques",
                servicePaiementList3: "Support 7j/7",
                servicePaiementBtn: "Payer en ligne",
                simpliciteTitre: "Simplicité et sécurité",
                simpliciteDesc: "L'absence volontaire de tableaux de bord complexes permet de renforcer la sécurité, réduire les délais de développement et garantir une plateforme rapide et fiable.",
                siteName: "<?php echo NOM_SITE; ?>"
            },
            EN: {
                langShort: "EN",
                langFR: "FR",
                langEN: "EN",
                langLIN: "LIN",
                langES: "ES",
                langPT: "PT",
                logoDetut: "", // to fill
                logoSecond: "", // to fill
                logoLast: "", // to fill
                navAccueil: "HOME",
                navServices: "SERVICES",
                navContact: "CONTACT",
                navEspace: "Personal space",
                mobileAccueil: "Home",
                mobileServices: "Services",
                mobileContact: "Contact",
                mobileEspace: "Personal space",
                heroTitre: "Online consular services of ",
                heroDesc: "Simplify your consular procedures with our secure and intuitive platform.\nBook appointments, submit your files, and make payments with confidence.",
                btnRdv: "Book an appointment",
                btnDepot: "Submit a file",
                carteSecuTitre: "100% secure platform",
                carteSecuDesc: "Automatic encryption of all your documents",
                carteSecu24: "24/7",
                carteSecuDispo: "Availability",
                carteSecuDepot: "Digital submission",
                carteSecuDocs: "Secure documents",
                carteSecuPaiement: "Secure payment",
                carteSecuTransac: "Encrypted transactions",
                servicesTitre: "Essential services",
                servicesDesc: "A simplified platform focused on essentials for fast and secure consular procedures",
                serviceRdvTitre: "Appointment management",
                serviceRdvDesc1: "Book appointments online for your consular procedures in just a few clicks.",
                serviceRdvDesc2: "Choose the date and time that suit you.",
                serviceRdvList1: "Calendar available 24/7",
                serviceRdvList2: "Immediate confirmation",
                serviceRdvList3: "Email/SMS reminders",
                serviceRdvBtn: "Book an appointment",
                serviceDepotTitre: "Secure digital submission",
                serviceDepotDesc1: "Submit your files online with automatic encryption of all your documents.",
                serviceDepotDesc2: "Your data is protected from upload.",
                serviceDepotList1: "AES-256 encryption",
                serviceDepotList2: "Multiple formats accepted",
                serviceDepotList3: "Real-time tracking",
                serviceDepotBtn: "Submit a file",
                servicePaiementTitre: "Secure payment",
                servicePaiementDesc1: "Make your payments securely thanks to our encrypted transaction system.",
                servicePaiementDesc2: "Several payment methods accepted.",
                servicePaiementList1: "Encrypted transactions",
                servicePaiementList2: "Digital receipts",
                servicePaiementList3: "Support 7 days a week",
                servicePaiementBtn: "Pay online",
                simpliciteTitre: "Simplicity and security",
                simpliciteDesc: "The deliberate absence of complex dashboards helps strengthen security, reduce development time, and ensure a fast and reliable platform.",
                siteName: "<?php echo NOM_SITE; ?>"
            },
            LIN: {
                langShort: "LIN",
                langFR: "FR",
                langEN: "EN",
                langLIN: "LIN",
                langES: "ES",
                langPT: "PT",
                logoDetut: "", // kobakisa
                logoSecond: "", // kobakisa
                logoLast: "", // kobakisa
                navAccueil: "Esika ya ebandeli",
                navServices: "Misala",
                navContact: "Kontakte",
                navEspace: "Esika ya moto",
                mobileAccueil: "Ebandeli",
                mobileServices: "Misala",
                mobileContact: "Kontakte",
                mobileEspace: "Esika ya moto",
                heroTitre: "Basolo ya konsilɛ na internet ya ",
                heroDesc: "Fungola misala ya konsilɛ na platforme na biso ya libateli mpe ya pɛtɛ.\nSenga rendez-vous, tinda bafichier na yo, mpe sala paiement na kimia.",
                btnRdv: "Senga rendez-vous",
                btnDepot: "Tinda dossier",
                carteSecuTitre: "Platforme 100% libateli",
                carteSecuDesc: "Chiffrement automatique ya bafichier nyonso",
                carteSecu24: "24h/24",
                carteSecuDispo: "Disponibilité",
                carteSecuDepot: "Tindeli ya bafichier",
                carteSecuDocs: "Bafichier libateli",
                carteSecuPaiement: "Paiement libateli",
                carteSecuTransac: "Transactions cryptées",
                servicesTitre: "Misala minene",
                servicesDesc: "Platforme pɛtɛ mpo na misala ya konsilɛ ya noki mpe ya libateli",
                serviceRdvTitre: "Bosaleli ya rendez-vous",
                serviceRdvDesc1: "Senga rendez-vous na internet mpo na misala ya konsilɛ na pɛtɛ.",
                serviceRdvDesc2: "Pona tango oyo ekosimba yo.",
                serviceRdvList1: "Kalendrier 24h/24",
                serviceRdvList2: "Kokakola mbala moko",
                serviceRdvList3: "Sango na email/SMS",
                serviceRdvBtn: "Senga rendez-vous",
                serviceDepotTitre: "Tindeli ya bafichier na libateli",
                serviceDepotDesc1: "Tinda bafichier na internet na chiffrement automatique.",
                serviceDepotDesc2: "Donnee na yo ebateli kobanda upload.",
                serviceDepotList1: "Chiffrement AES-256",
                serviceDepotList2: "Format mingi ekoki",
                serviceDepotList3: "Suivi na tango ya solo",
                serviceDepotBtn: "Tinda dossier",
                servicePaiementTitre: "Paiement libateli",
                servicePaiementDesc1: "Sala paiement na kimia na systeme ya transaction crypté.",
                servicePaiementDesc2: "Moyen mingi ya paiement ekoki.",
                servicePaiementList1: "Transactions cryptées",
                servicePaiementList2: "Reçus numériques",
                servicePaiementList3: "Support 7j/7",
                servicePaiementBtn: "Sala paiement na internet",
                simpliciteTitre: "Pɛtɛ mpe libateli",
                simpliciteDesc: "Koboya dashboard compliqué ekosalisa libateli, kokitisa tango ya développement, mpe kopesa plateforme ya noki.",
                siteName: "<?php echo NOM_SITE; ?>"
            },
            ES: {
                langShort: "ES",
                langFR: "FR",
                langEN: "EN",
                langLIN: "LIN",
                langES: "ES",
                langPT: "PT",
                logoDetut: "", // por completar
                logoSecond: "", // por completar
                logoLast: "", // por completar
                navAccueil: "INICIO",
                navServices: "SERVICIOS",
                navContact: "CONTACTO",
                navEspace: "Espacio personal",
                mobileAccueil: "Inicio",
                mobileServices: "Servicios",
                mobileContact: "Contacto",
                mobileEspace: "Espacio personal",
                heroTitre: "Servicios consulares en línea de ",
                heroDesc: "Simplifique sus trámites consulares con nuestra plataforma segura e intuitiva.\nReserve citas, envíe sus archivos y realice pagos con confianza.",
                btnRdv: "Reservar cita",
                btnDepot: "Enviar expediente",
                carteSecuTitre: "Plataforma 100% segura",
                carteSecuDesc: "Cifrado automático de todos sus documentos",
                carteSecu24: "24h/24",
                carteSecuDispo: "Disponibilidad",
                carteSecuDepot: "Envío digital",
                carteSecuDocs: "Documentos seguros",
                carteSecuPaiement: "Pago seguro",
                carteSecuTransac: "Transacciones cifradas",
                servicesTitre: "Servicios esenciales",
                servicesDesc: "Una plataforma simplificada centrada en lo esencial para trámites consulares rápidos y seguros",
                serviceRdvTitre: "Gestión de citas",
                serviceRdvDesc1: "Reserve citas en línea para sus trámites consulares en unos pocos clics.",
                serviceRdvDesc2: "Elija la fecha y hora que le convenga.",
                serviceRdvList1: "Calendario disponible 24h/24",
                serviceRdvList2: "Confirmación inmediata",
                serviceRdvList3: "Recordatorios por email/SMS",
                serviceRdvBtn: "Reservar cita",
                serviceDepotTitre: "Envío digital seguro",
                serviceDepotDesc1: "Envíe sus expedientes en línea con cifrado automático de todos sus documentos.",
                serviceDepotDesc2: "Sus datos están protegidos desde la carga.",
                serviceDepotList1: "Cifrado AES-256",
                serviceDepotList2: "Múltiples formatos aceptados",
                serviceDepotList3: "Seguimiento en tiempo real",
                serviceDepotBtn: "Enviar expediente",
                servicePaiementTitre: "Pago seguro",
                servicePaiementDesc1: "Realice sus pagos de forma segura gracias a nuestro sistema de transacciones cifradas.",
                servicePaiementDesc2: "Varios métodos de pago aceptados.",
                servicePaiementList1: "Transacciones cifradas",
                servicePaiementList2: "Recibos digitales",
                servicePaiementList3: "Soporte 7 días a la semana",
                servicePaiementBtn: "Pagar en línea",
                simpliciteTitre: "Simplicidad y seguridad",
                simpliciteDesc: "La ausencia deliberada de paneles complejos ayuda a reforzar la seguridad, reducir el tiempo de desarrollo y garantizar una plataforma rápida y fiable.",
                siteName: "<?php echo NOM_SITE; ?>"
            },
            PT: {
                langShort: "PT",
                langFR: "FR",
                langEN: "EN",
                langLIN: "LIN",
                langES: "ES",
                langPT: "PT",
                logoDetut: "", // para preencher
                logoSecond: "", // para preencher
                logoLast: "", // para preencher
                navAccueil: "INÍCIO",
                navServices: "SERVIÇOS",
                navContact: "CONTATO",
                navEspace: "Espaço pessoal",
                mobileAccueil: "Início",
                mobileServices: "Serviços",
                mobileContact: "Contato",
                mobileEspace: "Espaço pessoal",
                heroTitre: "Serviços consulares online de ",
                heroDesc: "Simplifique seus procedimentos consulares com nossa plataforma segura e intuitiva.\nAgende consultas, envie seus arquivos e faça pagamentos com confiança.",
                btnRdv: "Agendar consulta",
                btnDepot: "Enviar arquivo",
                carteSecuTitre: "Plataforma 100% segura",
                carteSecuDesc: "Criptografia automática de todos os seus documentos",
                carteSecu24: "24h/24",
                carteSecuDispo: "Disponibilidade",
                carteSecuDepot: "Envio digital",
                carteSecuDocs: "Documentos seguros",
                carteSecuPaiement: "Pagamento seguro",
                carteSecuTransac: "Transações criptografadas",
                servicesTitre: "Serviços essenciais",
                servicesDesc: "Uma plataforma simplificada focada no essencial para procedimentos consulares rápidos e seguros",
                serviceRdvTitre: "Gestão de consultas",
                serviceRdvDesc1: "Agende consultas online para seus procedimentos consulares em poucos cliques.",
                serviceRdvDesc2: "Escolha a data e hora que preferir.",
                serviceRdvList1: "Calendário disponível 24h/24",
                serviceRdvList2: "Confirmação imediata",
                serviceRdvList3: "Lembretes por email/SMS",
                serviceRdvBtn: "Agendar consulta",
                serviceDepotTitre: "Envio digital seguro",
                serviceDepotDesc1: "Envie seus arquivos online com criptografia automática de todos os seus documentos.",
                serviceDepotDesc2: "Seus dados estão protegidos desde o upload.",
                serviceDepotList1: "Criptografia AES-256",
                serviceDepotList2: "Múltiplos formatos aceitos",
                serviceDepotList3: "Acompanhamento em tempo real",
                serviceDepotBtn: "Enviar arquivo",
                servicePaiementTitre: "Pagamento seguro",
                servicePaiementDesc1: "Faça seus pagamentos com segurança graças ao nosso sistema de transações criptografadas.",
                servicePaiementDesc2: "Vários métodos de pagamento aceitos.",
                servicePaiementList1: "Transações criptografadas",
                servicePaiementList2: "Recibos digitais",
                servicePaiementList3: "Suporte 7 dias por semana",
                servicePaiementBtn: "Pagar online",
                simpliciteTitre: "Simplicidade e segurança",
                simpliciteDesc: "A ausência deliberada de painéis complexos ajuda a reforçar a segurança, reduzir o tempo de desenvolvimento e garantir uma plataforma rápida e confiável.",
                siteName: "<?php echo NOM_SITE; ?>"
            }
        };

        (function() {
            const langBtn = document.getElementById('langBtn');
            const langMenu = document.getElementById('langMenu');
            const currentLangSpan = document.getElementById('currentLang');
            const langSelector = document.getElementById('langSelector');
            const langOptions = langMenu.querySelectorAll('button[data-lang]');
            const LANG_KEY = 'site_lang';
            const defaultLang = 'FR';

            // Récupère la langue sauvegardée ou la langue par défaut
            function getSavedLang() {
                return localStorage.getItem(LANG_KEY) || defaultLang;
            }

            // Met à jour l'affichage de la langue active
            function updateLangUI(lang) {
                // Drapeaux par langue (Fontisto/Flag Icons)
                const flagClasses = {
                    FR: 'fi fi-fr fis',
                    EN: 'fi fi-gb fis',
                    LIN: 'fi fi-cd fis',
                    ES: 'fi fi-es fis',
                    PT: 'fi fi-pt fis'
                };
                currentLangSpan.textContent = lang;
                const flagSpan = document.getElementById('currentLangFlag');
                if (flagSpan) {
                    flagSpan.innerHTML = `<i class="${flagClasses[lang] || 'fi fi-xx fis'}"></i>`;
                }
                langOptions.forEach(btn => {
                    if (btn.dataset.lang === lang) {
                        btn.classList.add('bg-gray-100', 'font-bold');
                        btn.querySelector('span.ml-auto').classList.remove('hidden');
                        btn.setAttribute('aria-selected', 'true');
                    } else {
                        btn.classList.remove('bg-gray-100', 'font-bold');
                        btn.querySelector('span.ml-auto').classList.add('hidden');
                        btn.setAttribute('aria-selected', 'false');
                    }
                });
            }

            // Applique les traductions sur la page
            function applyTranslations(lang) {
                const t = translations[lang] || translations[defaultLang];
                // Met à jour chaque balise avec data-i18n
                document.querySelectorAll('[data-i18n]').forEach(el => {
                    const key = el.getAttribute('data-i18n');
                    let value = t[key];
                    // Cas particulier pour le titre principal (concaténer NOM_SUJET)
                    if (key === 'heroTitre' && window.NOM_SUJET) {
                        value = value + window.NOM_SUJET;
                    }
                    if (typeof value !== 'undefined') {
                        // Si c'est un input ou textarea, changer placeholder ou value
                        if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                            if (el.hasAttribute('placeholder')) {
                                el.setAttribute('placeholder', value);
                            } else {
                                el.value = value;
                            }
                        } else {
                            el.textContent = value;
                        }
                    }
                });
            }

            // Ouvre/ferme le menu
            function toggleMenu(force) {
                const isOpen = !langMenu.classList.contains('hidden');
                if (force === true || (!isOpen && force === undefined)) {
                    langMenu.classList.remove('hidden');
                    langBtn.setAttribute('aria-expanded', 'true');
                } else {
                    langMenu.classList.add('hidden');
                    langBtn.setAttribute('aria-expanded', 'false');
                }
            }

            // Sélection de langue
            langOptions.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const lang = this.dataset.lang;
                    localStorage.setItem(LANG_KEY, lang);
                    updateLangUI(lang);
                    applyTranslations(lang);
                    toggleMenu(false);
                });
            });

            // Ouvre/ferme au clic
            langBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleMenu();
            });

            // Ferme le menu si clic à l'extérieur
            document.addEventListener('click', function(e) {
                if (!langSelector.contains(e.target)) {
                    toggleMenu(false);
                }
            });

            // Accessibilité clavier : ouverture avec Entrée/Espace
            langBtn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleMenu();
                }
            });

            // Initialisation
            const savedLang = getSavedLang();
            updateLangUI(savedLang);
            // Pour PHP -> JS : expose NOM_SUJET
            window.NOM_SUJET = window.NOM_SUJET || (typeof NOM_SUJET !== 'undefined' ? NOM_SUJET : document.querySelector('span.text-sm.font-medium')?.textContent || '');
            applyTranslations(savedLang);
        })();
    </script>
</body>

</html>