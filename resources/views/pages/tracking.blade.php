<x-app-layout>
    <div class="py-12">
        <div class="max-w-6xl mx-auto px-6">

            {{-- En-tête --}}
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-4xl font-black" style="font-family: 'Playfair Display', serif; color: #F4A429">
                        Suivi de livraison
                    </h1>
                    <p class="text-sm mt-1 font-mono" style="color: rgba(253,246,236,0.4)">
                        ID : GM-{{ rand(1000, 9999) }}-X — Antananarivo, Madagascar
                    </p>
                </div>
                <div class="text-right hidden md:block">
                    <p class="text-xs font-mono" style="color: rgba(253,246,236,0.4)">COORDONNÉES</p>
                    <p class="text-sm font-bold" style="color: #F4A429">18.8792° S, 47.5079° E</p>
                </div>
            </div>

            <div id="tracking-grid" class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                {{-- Panneau statut --}}
                <div class="lg:col-span-1 space-y-4">
                    <div class="p-6 rounded-2xl"
                         style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">

                        <h3 class="text-xs font-bold uppercase tracking-widest mb-4"
                            style="color: rgba(244,164,41,0.7)">
                            Statut de livraison
                        </h3>

                        <div class="flex items-center gap-3 mb-6">
                            <div class="relative">
                                <div class="w-3 h-3 rounded-full" style="background: #4ade80"></div>
                                <div class="absolute inset-0 w-3 h-3 rounded-full animate-ping"
                                     style="background: #4ade80; opacity: 0.5"></div>
                            </div>
                            <div>
                                <p class="text-sm font-black" style="color: #FDF6EC">En Transit 🚚</p>
                                <p class="text-xs" style="color: rgba(253,246,236,0.4)">En route vers vous</p>
                            </div>
                        </div>

                        <div class="space-y-4" style="border-left: 2px solid rgba(244,164,41,0.2); padding-left: 1rem">
                            <div>
                                <p class="text-xs uppercase font-mono mb-1" style="color: rgba(253,246,236,0.4)">Destination</p>
                                <p class="text-sm font-bold" style="color: #FDF6EC">{{ $address }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase font-mono mb-1" style="color: rgba(253,246,236,0.4)">Arrivée estimée</p>
                                <p class="text-2xl font-black" style="color: #F4A429">~ 45 MIN</p>
                            </div>
                        </div>

                        <button onclick="window.location.reload()"
                                class="w-full mt-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition"
                                style="background: rgba(244,164,41,0.1); border: 1px solid rgba(244,164,41,0.2); color: #F4A429">
                            🔄 Actualiser
                        </button>
                    </div>

                    {{-- Étapes --}}
                    <div class="p-6 rounded-2xl"
                         style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                        <h3 class="text-xs font-bold uppercase tracking-widest mb-4"
                            style="color: rgba(244,164,41,0.7)">
                            Étapes
                        </h3>
                        <div class="space-y-3">
                            @foreach([
                                ['Commande confirmée', '✅', true],
                                ['Préparation', '📦', true],
                                ['En livraison', '🚚', true],
                                ['Livré', '🏠', false],
                            ] as [$step, $icon, $done])
                            <div class="flex items-center gap-3">
                                <span class="text-lg">{{ $icon }}</span>
                                <span class="text-sm {{ $done ? 'font-bold' : '' }}"
                                      style="color: {{ $done ? '#FDF6EC' : 'rgba(253,246,236,0.3)' }}">
                                    {{ $step }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Carte --}}
                <div class="lg:col-span-3">
                    <div id="map" class="rounded-2xl overflow-hidden"
                         style="height: clamp(280px, 60vw, 500px); border: 1px solid rgba(244,164,41,0.2)"></div>
                </div>

            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Initialisation carte avec thème chaud
        var map = L.map('map', {
            zoomControl: true,
            attributionControl: false
        }).setView([-18.8792, 47.5079], 12);

        // Tuiles style chaud/sépia
        L.tileLayer('https://{s}.basemaps.cartocdn.com/voyager/{z}/{x}/{y}{r}.png').addTo(map);

        // Icône dépôt GasyMarket
        var warehouseIcon = L.divIcon({
            className: '',
            html: "<div style='background:#F4A429; width:14px; height:14px; border-radius:50%; border:2px solid white; box-shadow:0 0 15px #F4A429;'></div>",
            iconSize: [14, 14]
        });

        // Icône colis en mouvement
        var packageIcon = L.divIcon({
            className: '',
            html: "<div style='background:#E07B2A; width:14px; height:14px; border-radius:50%; border:2px solid white; box-shadow:0 0 15px #E07B2A;'></div>",
            iconSize: [14, 14]
        });

        var start = [-18.9100, 47.5200];
        // Marqueur dépôt GasyMarket
        L.marker(start, {icon: warehouseIcon}).addTo(map).bindPopup("📦 Dépôt GasyMarket");

        // Géocodage de l'adresse
        var rawAddress = "{{ $address }}";
        var queryAddress = rawAddress + ", Antananarivo, Madagascar";

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(queryAddress)}&limit=1`, {
            headers: { 'Accept-Language': 'fr' }
        })
        .then(r => r.json())
        .then(data => {
            var end;
            if (data && data.length > 0) {
                end = [data[0].lat, data[0].lon];
            } else {
                // Position approximative si adresse non trouvée
                end = [-18.8800 + (Math.random() * 0.05), 47.5000 + (Math.random() * 0.05)];
            }

            // Marqueur destination
            L.marker(end, {icon: packageIcon}).addTo(map)
             .bindPopup("🏠 <b>Destination :</b> " + rawAddress)
             .openPopup();

            // Tracé du trajet couleur sunset
            var path = L.polyline([start, end], {
                color: '#F4A429',
                weight: 3,
                dashArray: '10, 12',
                opacity: 0.8
            }).addTo(map);

            map.fitBounds(path.getBounds(), {padding: [60, 60]});
        });
    </script>
    <style>
        /* On force 1 seule colonne quelle que soit la taille,
        l'ID a une spécificité plus forte que .lg\:grid-cols-4
        donc il prend le dessus sur la règle globale buggée */
        #tracking-grid {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 1.5rem !important;
        }

        /* À partir de 1024px (vrai breakpoint desktop) :
        panneau statut + carte côte à côte */
        @media (min-width: 1024px) {
            #tracking-grid {
                grid-template-columns: repeat(4, 1fr) !important;
                gap: 2rem !important;
            }
        }
    </style>
</x-app-layout>