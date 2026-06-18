<x-app-layout>
    <div class="py-12">
        {{-- max-w-2xl remplacé par style inline car classe absente du build --}}
        <div style="max-width: 42rem; margin: 0 auto; padding: 0 1.5rem;">

            <h1 class="text-3xl font-black mb-8 text-center"
                style="font-family: 'Playfair Display', serif; color: #F4A429">
                Sélection du mode de règlement
            </h1>

            @if($errors->any())
                <div style="margin-bottom:1.5rem; padding:1rem; border-radius:0.75rem;
                            background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3)">
                    @foreach($errors->all() as $error)
                        <p style="color:#f87171; font-size:0.875rem">⚠️ {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST"
                  style="display:flex; flex-direction:column; gap:1.5rem;">
                @csrf

                {{-- Total --}}
                <div style="padding:1.5rem; border-radius:1rem;
                            background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.3)">
                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
                        <span style="color: rgba(253,246,236,0.6)">Total à payer</span>
                        <span class="text-3xl font-black" style="color: #F4A429">
                            {{ number_format($total, 0, ',', ' ') }} Ar
                        </span>
                    </div>
                    <p style="font-size:0.75rem; margin-top:0.5rem; color: rgba(253,246,236,0.4)">
                        🔒 Votre argent sera sécurisé jusqu'à confirmation de réception
                    </p>
                </div>

                {{-- Livraison --}}
                <div style="padding:1.5rem; border-radius:1rem; display:flex; flex-direction:column; gap:1rem;
                            background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                    <h3 class="text-xs font-bold uppercase tracking-widest"
                        style="color: rgba(244,164,41,0.8)">
                        Coordonnées de livraison
                    </h3>
                    <input type="text" name="address" value="{{ old('address') }}"
                           placeholder="Adresse exacte (Ex: Logement 123, Itaosy)" required
                           style="width:100%; border-radius:0.75rem; padding:0.75rem 1rem; font-size:0.875rem;
                                  background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2);
                                  color: #FDF6EC; outline:none; box-sizing:border-box;">
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="Numéro de téléphone (Ex: 034 XX XXX XX)" required
                           style="width:100%; border-radius:0.75rem; padding:0.75rem 1rem; font-size:0.875rem;
                                  background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2);
                                  color: #FDF6EC; outline:none; box-sizing:border-box;">
                </div>

                {{-- Choix de l'opérateur de paiement --}}
                <div style="padding:1.5rem; border-radius:1rem;
                            background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-4"
                        style="color: rgba(244,164,41,0.8)">
                        📱 Opérateur de paiement
                    </h3>

                    {{-- Sélecteurs d'opérateur --}}
                    <div id="operator-selector" style="display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem; margin-bottom:1.25rem;">

                        {{-- MVola --}}
                        <label id="label-mvola"
                               style="display:flex; flex-direction:column; align-items:center; gap:0.5rem;
                                      padding:1rem 0.5rem; border-radius:0.75rem; cursor:pointer;
                                      border: 2px solid #F4A429; background: rgba(244,164,41,0.1);">
                            <input type="radio" name="operator" value="mvola"
                                   {{ old('operator', 'mvola') == 'mvola' ? 'checked' : '' }}
                                   style="display:none;" onchange="switchOperator('mvola')">
                            <span style="font-size:1.5rem;">📲</span>
                            <span style="font-size:0.75rem; font-weight:700; color:#F4A429;">MVola</span>
                            <span style="font-size:0.65rem; color:rgba(253,246,236,0.4);">034</span>
                        </label>

                        {{-- Orange Money --}}
                        <label id="label-orange"
                               style="display:flex; flex-direction:column; align-items:center; gap:0.5rem;
                                      padding:1rem 0.5rem; border-radius:0.75rem; cursor:pointer;
                                      border: 2px solid rgba(244,164,41,0.2); background:transparent;">
                            <input type="radio" name="operator" value="orange"
                                   {{ old('operator') == 'orange' ? 'checked' : '' }}
                                   style="display:none;" onchange="switchOperator('orange')">
                            <span style="font-size:1.5rem;">🟠</span>
                            <span style="font-size:0.75rem; font-weight:700; color:rgba(253,246,236,0.7);">Orange Money</span>
                            <span style="font-size:0.65rem; color:rgba(253,246,236,0.4);">032</span>
                        </label>

                        {{-- Airtel Money --}}
                        <label id="label-airtel"
                               style="display:flex; flex-direction:column; align-items:center; gap:0.5rem;
                                      padding:1rem 0.5rem; border-radius:0.75rem; cursor:pointer;
                                      border: 2px solid rgba(244,164,41,0.2); background:transparent;">
                            <input type="radio" name="operator" value="airtel"
                                   {{ old('operator') == 'airtel' ? 'checked' : '' }}
                                   style="display:none;" onchange="switchOperator('airtel')">
                            <span style="font-size:1.5rem;">🔴</span>
                            <span style="font-size:0.75rem; font-weight:700; color:rgba(253,246,236,0.7);">Airtel Money</span>
                            <span style="font-size:0.65rem; color:rgba(253,246,236,0.4);">033</span>
                        </label>
                    </div>

                    {{-- Champ numéro (commun aux 3 opérateurs) --}}
                    <input type="text" name="mvola_number" id="operator-number"
                           value="{{ old('mvola_number') }}"
                           placeholder="Votre numéro MVola(Ex: 034 XX XXX XX)" required
                           style="width:100%; border-radius:0.75rem; padding:0.75rem 1rem; font-size:0.875rem;
                                  background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2);
                                  color: #FDF6EC; outline:none; box-sizing:border-box; margin-bottom:1rem;">

                    {{-- Instructions dynamiques --}}
                    <div style="font-size:0.75rem; color:rgba(253,246,236,0.4); display:flex; flex-direction:column; gap:0.25rem;">
                        <p>1. Sélectionnez votre opérateur</p>
                        <p>2. Entrez votre numéro</p>
                        <p>3. Confirmez la commande</p>
                        <p id="operator-step4">4. Validez le paiement MVola sur votre téléphone</p>
                        <p>5. Votre argent est retenu jusqu'à la livraison</p>
                    </div>
                </div>

                <button type="submit"
                        style="width:100%; padding:1rem; border-radius:0.75rem; font-weight:900;
                               font-size:0.875rem; text-transform:uppercase; letter-spacing:0.1em;
                               background: #F4A429; color: #2C1A0E; border:none; cursor:pointer;">
                    🔒 Sécuriser le paiement
                </button>

                <a href="{{ route('cart.index') }}"
                   style="display:block; text-align:center; font-size:0.875rem;
                          color: rgba(253,246,236,0.4); text-decoration:none;">
                    ← Retour au panier
                </a>
            </form>
        </div>
    </div>

    <style>
        /* Responsive : 1 colonne sur très petit écran pour les opérateurs */
        @media (max-width: 400px) {
            #operator-selector {
                grid-template-columns: 1fr !important;
            }
        }
        @media (max-width: 640px) {
            input[type="text"] { font-size: 16px !important; }
        }
    </style>

    <script>
        /* Gestion du switch visuel entre les 3 opérateurs */
        const operators = ['mvola', 'orange', 'airtel'];
        const placeholders = {
            mvola:  'Votre numéro MVola (Ex: 034 XX XXX XX)',
            orange: 'Votre numéro Orange Money (Ex: 032 XX XXX XX)',
            airtel: 'Votre numéro Airtel Money (Ex: 033 XX XXX XX)',
        };
        const step4 = {
            mvola:  '4. Validez le paiement MVola sur votre téléphone',
            orange: '4. Validez le paiement Orange Money sur votre téléphone',
            airtel: '4. Validez le paiement Airtel Money sur votre téléphone',
        };

        function switchOperator(selected) {
            operators.forEach(op => {
                const label = document.getElementById('label-' + op);
                if (op === selected) {
                    label.style.border       = '2px solid #F4A429';
                    label.style.background   = 'rgba(244,164,41,0.1)';
                    label.querySelector('span:nth-child(3)').style.color = '#F4A429';
                } else {
                    label.style.border       = '2px solid rgba(244,164,41,0.2)';
                    label.style.background   = 'transparent';
                    label.querySelector('span:nth-child(3)').style.color = 'rgba(253,246,236,0.7)';
                }
            });
            document.getElementById('operator-number').placeholder = placeholders[selected];
            document.getElementById('operator-step4').textContent   = step4[selected];
        }

        /* Initialisation au chargement (conserve le choix old() de Laravel) */
        const preselected = '{{ old('operator', 'mvola') }}';
        switchOperator(preselected);

        /* Clic sur le label déclenche le switch */
        operators.forEach(op => {
            document.getElementById('label-' + op).addEventListener('click', () => {
                switchOperator(op);
            });
        });
    </script>
</x-app-layout>