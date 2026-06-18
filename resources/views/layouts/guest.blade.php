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
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background:
                linear-gradient(to bottom, rgba(44,26,14,0.88), rgba(44,26,14,0.78), rgba(44,26,14,0.92)),
                url('/images/baobab-sunset.jpg') center/cover fixed;
            display: flex;
        }
        .auth-left {
            flex: 1;
            display: none;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
            padding-top: 6rem;
            background: rgba(44,26,14,0.3);
            overflow: hidden;
        }
        .auth-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            overflow-y: auto;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: rgba(44,26,14,0.92);
            border: 1px solid rgba(244,164,41,0.2);
            border-radius: 1rem;
            padding: 2rem;
        }
        @media (min-width: 1024px) {
            .auth-left { display: flex; }
        }
        @media (max-width: 480px) {
            .auth-card { padding: 1.25rem; }
            .auth-right { padding: 1rem; }
        }
    </style>
</head>
<body>

    {{-- Panneau gauche --}}
    <div class="auth-left">

        <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2.5rem; text-decoration: none;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #F4A429, #E07B2A); display: flex; align-items: center; justify-content: center;">
                <span style="color: white; font-weight: 900; font-size: 1.5rem;">G</span>
            </div>
            <span style="font-family: 'Playfair Display', serif; font-weight: 900; font-size: 1.75rem;">
                <span style="color: #F4A429">Gasy</span><span style="color: white">Market</span>
            </span>
        </a>

        @if(request()->routeIs('login'))
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 900; color: #FDF6EC; line-height: 1.2; margin-bottom: 1.5rem;">
                Bon retour<br><span style="color: #F4A429">parmi nous !</span>
            </h1>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <p style="color: rgba(253,246,236,0.8)">🛍️ Que les bonnes affaires commencent !</p>
                <p style="color: rgba(253,246,236,0.7)">Retrouvez vos produits favoris et suivez vos commandes en temps réel.</p>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.5rem;"><span>🔒</span><p style="color: rgba(253,246,236,0.8)">Paiements 100% sécurisés</p></div>
                <div style="display: flex; align-items: center; gap: 0.75rem;"><span>🚚</span><p style="color: rgba(253,246,236,0.8)">Livraison partout à Madagascar</p></div>
                <div style="display: flex; align-items: center; gap: 0.75rem;"><span>🇲🇬</span><p style="color: rgba(253,246,236,0.8)">Marketplace 100% malgache</p></div>
            </div>

        @elseif(request()->routeIs('register*'))
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 900; color: #FDF6EC; line-height: 1.2; margin-bottom: 1.5rem;">
                Rejoignez la<br><span style="color: #F4A429">communauté !</span>
            </h1>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <p style="color: rgba(253,246,236,0.8)">🎉 Inscription gratuite en quelques minutes !</p>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.5rem;"><span>✅</span><p style="color: rgba(253,246,236,0.8)">Compte gratuit, sans engagement</p></div>
                <div style="display: flex; align-items: center; gap: 0.75rem;"><span>🛍️</span><p style="color: rgba(253,246,236,0.8)">Achetez et vendez en toute confiance</p></div>
                <div style="display: flex; align-items: center; gap: 0.75rem;"><span>💰</span><p style="color: rgba(253,246,236,0.8)">Gagnez de l'argent depuis chez vous</p></div>
                <div style="display: flex; align-items: center; gap: 0.75rem;"><span>🇲🇬</span><p style="color: rgba(253,246,236,0.8)">Soutenez l'économie malgache</p></div>
            </div>

        @else
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 900; color: #FDF6EC; line-height: 1.2; margin-bottom: 1.5rem;">
                La marketplace<br><span style="color: #F4A429">100% malgache</span>
            </h1>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;"><span>🔒</span><p style="color: rgba(253,246,236,0.8)">Paiements sécurisés</p></div>
                <div style="display: flex; align-items: center; gap: 0.75rem;"><span>🚚</span><p style="color: rgba(253,246,236,0.8)">Livraison partout à Madagascar</p></div>
                <div style="display: flex; align-items: center; gap: 0.75rem;"><span>🛍️</span><p style="color: rgba(253,246,236,0.8)">Des milliers de produits locaux</p></div>
            </div>
        @endif

    </div>

    {{-- Panneau droit --}}
    <div class="auth-right">
        <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; text-decoration: none;">
            <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #F4A429, #E07B2A); display: flex; align-items: center; justify-content: center;">
                <span style="color: white; font-weight: 900; font-size: 1rem;">G</span>
            </div>
            <span style="font-family: 'Playfair Display', serif; font-weight: 900; font-size: 1.25rem;">
                <span style="color: #F4A429">Gasy</span><span style="color: white">Market</span>
            </span>
        </a>
        <div class="auth-card">
            {{ $slot }}
        </div>
        <p style="margin-top: 1.5rem; font-size: 0.8rem; color: rgba(244,164,41,0.4);">
            🇲🇬 La marketplace 100% malgache
        </p>
    </div>

</body>
</html>