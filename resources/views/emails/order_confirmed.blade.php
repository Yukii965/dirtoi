<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #020617; color: #ffffff; padding: 20px; }
        .card { background-color: #0f172a; border: 1px solid #1e293b; padding: 30px; border-radius: 20px; max-width: 600px; margin: auto; }
        .header { color: #06b6d4; font-size: 24px; font-weight: bold; text-transform: uppercase; margin-bottom: 20px; }
        .highlight { color: #eab308; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 12px; color: #64748b; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">Dir<span style="color:white">Toi</span> Quantum Logistics</div>
        <p>Salutations,</p>
        <p>Votre transaction a été validée avec succès sur notre terminal. Votre colis est désormais <span class="highlight">en route</span> vers votre destination.</p>
        
        <div style="background-color: #1e293b; padding: 15px; border-radius: 10px;">
            <p><strong>Mode de paiement :</strong> {{ $details['payment_method'] == 'delivery' ? 'Paiement à la livraison' : 'Paiement Digital' }}</p>
            <p><strong>Adresse :</strong> {{ $details['address'] }}</p>
        </div>

        <p>Nos agents de livraison vous contacteront prochainement sur votre numéro : <span class="highlight">{{ $details['phone'] }}</span>.</p>
        
        <p>Merci d'avoir choisi le futur de l'e-commerce à Madagascar.</p>
        
        <div class="footer">
            © 2026 GasyMarket. Données cryptées de bout en bout.
        </div>
    </div>
</body>
</html>