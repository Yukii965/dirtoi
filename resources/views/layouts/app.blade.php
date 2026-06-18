<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GasyMarket</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ========================================
        GASYMARKET — CSS COMPLET
        Base + Responsive
        ======================================== */

        /* === BASE === */
        * { box-sizing: border-box; }
        img { max-width: 100%; height: auto; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #2C1A0E;
            color: #FDF6EC;
            margin: 0;
            overflow-x: hidden;
        }

        /* === BACKGROUND BAOBAB === */
        .gasy-bg {
            background:
                linear-gradient(to bottom, rgba(44,26,14,0.85) 0%, rgba(44,26,14,0.75) 50%, rgba(44,26,14,0.90) 100%),
                url('/images/baobab-sunset.jpg') center/cover fixed;
            min-height: 100vh;
        }

        /* === BOUTON PRINCIPAL === */
        .btn-gasy {
            background: #F4A429;
            color: #2C1A0E;
            font-weight: 700;
            padding: 0.6rem 1.2rem;
            border-radius: 0.75rem;
            transition: all 0.2s;
            display: inline-block;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }
        .btn-gasy:hover {
            background: #E07B2A;
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(244,164,41,0.3);
        }

        /* === CARTE PRODUIT === */
        .gasy-card {
            background: rgba(92,51,23,0.4);
            border: 1px solid rgba(244,164,41,0.2);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            transition: all 0.2s;
        }
        .gasy-card:hover {
            border-color: rgba(244,164,41,0.5);
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.3);
        }

        /* === TABLES SCROLLABLES === */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table-responsive::-webkit-scrollbar { height: 4px; }
        .table-responsive::-webkit-scrollbar-track { background: rgba(244,164,41,0.1); }
        .table-responsive::-webkit-scrollbar-thumb { background: rgba(244,164,41,0.4); border-radius: 2px; }

        /* ========================================
        RESPONSIVE — TABLETTE (max 1024px)
        ======================================== */
        @media (max-width: 1024px) {

            /* Auth — cacher panneau gauche */
            .auth-left { display: none !important; }
            .auth-right { margin-left: 0 !important; width: 100% !important; }

            /* Boutique — sidebar en haut */
            .lg\:flex-row { flex-direction: column !important; }
            .lg\:w-64 { width: 100% !important; }

            /* Grille produits boutique */
            .lg\:grid-cols-3 { grid-template-columns: repeat(2, 1fr) !important; }
            .lg\:grid-cols-4 { grid-template-columns: repeat(2, 1fr) !important; }

            /* Détail produit — une colonne */
            .lg\:grid-cols-2 { grid-template-columns: 1fr !important; }

            /* Dashboard stats */
            .grid-cols-3 { grid-template-columns: repeat(2, 1fr) !important; }
            .sm\:grid-cols-4 { grid-template-columns: repeat(2, 1fr) !important; }
        }

        /* ========================================
        RESPONSIVE — MOBILE (max 768px)
        ======================================== */
        @media (max-width: 768px) {

            /* Navigation */
            #search-wrapper { display: none !important; }
            #menu-toggle { display: block !important; }

            /* Espacements réduits */
            .py-12 { padding-top: 2rem !important; padding-bottom: 2rem !important; }
            .px-6  { padding-left: 1rem !important; padding-right: 1rem !important; }
            .max-w-7xl, .max-w-5xl, .max-w-4xl, .max-w-3xl, .max-w-2xl {
                max-width: 100% !important;
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
                margin: 0 auto !important;
            }

            /* Textes */
            .text-6xl { font-size: 2.2rem !important; line-height: 1.2 !important; }
            .text-4xl { font-size: 1.75rem !important; }
            .text-3xl { font-size: 1.4rem !important; }
            .text-2xl { font-size: 1.2rem !important; }
            .text-xl  { font-size: 1rem !important; }

            /* Hero banner */
            .h-80 { height: 220px !important; }
            .px-12 { padding-left: 1.5rem !important; padding-right: 1.5rem !important; }

            /* Boutons hero en colonne */
            .mt-6.flex.gap-3 {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }
            .mt-6.flex.gap-3 a,
            .mt-6.flex.gap-3 button {
                width: 100% !important;
                text-align: center !important;
            }

            /* Grilles */
            .grid-cols-3 { grid-template-columns: repeat(2, 1fr) !important; }
            .grid-cols-4 { grid-template-columns: repeat(2, 1fr) !important; }
            .sm\:grid-cols-3 { grid-template-columns: repeat(2, 1fr) !important; }

            /* Panier — flex wrap */
            .flex.items-center.gap-4 { flex-wrap: wrap !important; }
            .shrink-0 { flex-shrink: 0 !important; }

            /* Détail produit image */
            .h-96 { height: 240px !important; }

            /* Produits similaires */
            [style*="repeat(4, 1fr)"] {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            /* Formulaires */
            input, select, textarea, button {
                font-size: 16px !important;
            }

            /* Footer */
            footer [style*="repeat(3, 1fr)"] {
                grid-template-columns: 1fr !important;
                gap: 1.5rem !important;
            }
            footer [style*="space-between"] {
                flex-direction: column !important;
                text-align: center !important;
                gap: 0.5rem !important;
            }

            /* Dashboard vendeur commandes */
            .flex.gap-2 { flex-wrap: wrap !important; }

            /* Touch targets */
            a, button { min-height: 40px; }
        }

        /* ========================================
        RESPONSIVE — PETIT MOBILE (max 480px)
        ======================================== */
        @media (max-width: 480px) {

            /* Tout en une colonne */
            .grid-cols-3,
            .grid-cols-2,
            .grid-cols-4,
            .sm\:grid-cols-2,
            .sm\:grid-cols-3,
            .sm\:grid-cols-4 {
                grid-template-columns: 1fr !important;
            }

            /* Auth card pleine largeur */
            .auth-card {
                padding: 1rem !important;
                border-radius: 0.75rem !important;
            }

            /* Hero très petit */
            .h-80 { height: 180px !important; }
            .text-6xl { font-size: 1.75rem !important; }

            /* Navbar logo simplifié */
            .font-black.text-xl { font-size: 1rem !important; }

            /* Panier items */
            .w-20 { width: 64px !important; }
            .h-20 { height: 64px !important; }

            /* Boutons pleine largeur sur mobile */
            .active\:scale-95 { width: 100% !important; }

            /* Padding très serré */
            .p-6 { padding: 1rem !important; }
            .p-8 { padding: 1.25rem !important; }
            .gap-6 { gap: 1rem !important; }
            .gap-8 { gap: 1.25rem !important; }

            /* Textes minimum */
            .text-3xl { font-size: 1.25rem !important; }
            .text-4xl { font-size: 1.4rem !important; }
        }

        /* ========================================
        RESPONSIVE — TON ÉCRAN SPÉCIFIQUE
        (entre 768px et 1200px)
        ======================================== */
        @media (min-width: 769px) and (max-width: 1200px) {

            /* Réduire les max-width */
            .max-w-7xl { max-width: 100% !important; padding: 0 1.5rem !important; }
            .max-w-5xl { max-width: 100% !important; padding: 0 1.5rem !important; }

            /* Grilles 3 colonnes au lieu de 4 */
            .lg\:grid-cols-4 { grid-template-columns: repeat(3, 1fr) !important; }

            /* Stats dashboard */
            .grid-cols-3 { gap: 0.75rem !important; }

            /* Textes un peu plus petits */
            .text-4xl { font-size: 1.75rem !important; }
            .text-3xl { font-size: 1.4rem !important; }

            /* Hero un peu moins grand */
            .h-80 { height: 260px !important; }
        }
    </style>
</head>
<body class="gasy-bg">

    @include('layouts.navigation')

    {{-- Messages flash --}}
    @if(session('success'))
        <div id="flash-msg" class="fixed top-4 right-4 z-50 px-5 py-3 rounded-xl shadow-lg text-sm font-bold"
             style="background: rgba(34,197,94,0.9); color: white; max-width: 90vw">
            ✅ {{ session('success') }}
        </div>
        <script>setTimeout(() => { const el = document.getElementById('flash-msg'); if(el) el.remove(); }, 4000);</script>
    @endif

    @if(session('error'))
        <div id="flash-err" class="fixed top-4 right-4 z-50 px-5 py-3 rounded-xl shadow-lg text-sm font-bold"
             style="background: rgba(239,68,68,0.9); color: white; max-width: 90vw">
            ⚠️ {{ session('error') }}
        </div>
        <script>setTimeout(() => { const el = document.getElementById('flash-err'); if(el) el.remove(); }, 4000);</script>
    @endif

    <main>{{ $slot }}</main>

    <footer class="mt-16 py-10" style="border-top:1px solid rgba(244,164,41,0.15);background:rgba(44,26,14,0.6)">
        <div style="max-width:1280px;margin:0 auto;padding:0 1.5rem">
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:2rem;margin-bottom:2rem">
                {{-- À propos --}}
                <div>
                    <h3 style="color:#F4A429;font-weight:900;margin-bottom:1rem;font-family:'Playfair Display',serif">GasyMarket</h3>
                    <p style="color:rgba(253,246,236,0.6);font-size:0.875rem;line-height:1.6">
                        La marketplace 100% malgache. Achetez et vendez des produits locaux en toute sécurité grâce à notre système Escrow.
                    </p>
                    <p style="color:rgba(253,246,236,0.4);font-size:0.75rem;margin-top:0.75rem">
                        🇲🇬 Fait avec fierté à Madagascar
                    </p>
                </div>
                {{-- Liens --}}
                <div>
                    <h3 style="color:#F4A429;font-weight:700;margin-bottom:1rem;font-size:0.875rem;text-transform:uppercase">Liens utiles</h3>
                    <div style="display:flex;flex-direction:column;gap:0.5rem">
                        <a href="{{ route('home') }}" style="color:rgba(253,246,236,0.6);font-size:0.875rem;text-decoration:none" onmouseover="this.style.color='#F4A429'" onmouseout="this.style.color='rgba(253,246,236,0.6)'">Accueil</a>
                        <a href="{{ route('products.index') }}" style="color:rgba(253,246,236,0.6);font-size:0.875rem;text-decoration:none" onmouseover="this.style.color='#F4A429'" onmouseout="this.style.color='rgba(253,246,236,0.6)'">Boutique</a>
                        <a href="{{ route('nav.help') }}" style="color:rgba(253,246,236,0.6);font-size:0.875rem;text-decoration:none" onmouseover="this.style.color='#F4A429'" onmouseout="this.style.color='rgba(253,246,236,0.6)'">Aide & Support</a>
                        <a href="{{ route('register') }}" style="color:rgba(253,246,236,0.6);font-size:0.875rem;text-decoration:none" onmouseover="this.style.color='#F4A429'" onmouseout="this.style.color='rgba(253,246,236,0.6)'">Devenir vendeur</a>
                    </div>
                </div>
                {{-- Contact & Réseaux --}}
                <div>
                    <h3 style="color:#F4A429;font-weight:700;margin-bottom:1rem;font-size:0.875rem;text-transform:uppercase">Contact</h3>
                    <div style="display:flex;flex-direction:column;gap:0.5rem">
                        <a href="mailto:support@gasymarket.mg" style="color:rgba(253,246,236,0.6);font-size:0.875rem;text-decoration:none">📧 support@gasymarket.mg</a>
                        <a href="https://wa.me/261000000000" target="_blank" style="color:rgba(253,246,236,0.6);font-size:0.875rem;text-decoration:none">💬 WhatsApp</a>
                        <a href="https://instagram.com/gasymarket" target="_blank" style="color:rgba(253,246,236,0.6);font-size:0.875rem;text-decoration:none">📸 Instagram</a>
                        <a href="https://facebook.com/gasymarket" target="_blank" style="color:rgba(253,246,236,0.6);font-size:0.875rem;text-decoration:none">👥 Facebook</a>
                    </div>
                </div>
            </div>
            {{-- Politique --}}
            <div style="padding-top:1.5rem;border-top:1px solid rgba(244,164,41,0.1);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
                <p style="color:rgba(244,164,41,0.4);font-size:0.8rem">© 2026 GasyMarket — Tous droits réservés</p>
                <div style="display:flex;gap:1rem">
                    <a href="{{ route('policy') }}" style="color:rgba(244,164,41,0.4);font-size:0.75rem;text-decoration:none" onmouseover="this.style.color='#F4A429'" onmouseout="this.style.color='rgba(244,164,41,0.4)'">Politique de confidentialité</a>
                    <a href="{{ route('terms') }}" style="color:rgba(244,164,41,0.4);font-size:0.75rem;text-decoration:none" onmouseover="this.style.color='#F4A429'" onmouseout="this.style.color='rgba(244,164,41,0.4)'">Conditions d'utilisation</a>
                </div>
            </div>
        </div>
    </footer>

    @include('components.ia-zero')
</body>
</html>