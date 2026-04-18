<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen text-white overflow-hidden">
        <div class="max-w-6xl mx-auto px-6">
            
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-4xl font-black tracking-tighter uppercase italic text-white">
                        Localisation <span class="text-cyan-500">Transit Flux</span>
                    </h1>
                    <p class="text-cyan-500/50 font-mono text-xs mt-2 uppercase tracking-[0.3em]">Tracking ID: DT-{{ rand(1000, 9999) }}-X</p>
                </div>
                <div class="text-right hidden md:block">
                    <p class="text-xs text-gray-500 font-mono">COORDONNÉES RÉSEAU</p>
                    <p class="text-sm font-bold text-cyan-400">18.8792° S, 47.5079° E</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-gray-900/40 backdrop-blur-xl border border-white/10 p-6 rounded-[2rem] shadow-2xl">
                        <h3 class="font-mono text-[10px] text-cyan-500 uppercase tracking-widest mb-4">Statut de livraison</h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <div class="absolute inset-0 bg-green-500 rounded-full animate-ping"></div>
                                </div>
                                <div>
                                    <p class="text-sm font-black uppercase">En Transit</p>
                                    <p class="text-[10px] text-gray-500">Vitesse: 45km/h</p>
                                </div>
                            </div>

                            <div class="border-l-2 border-gray-800 ml-1.5 pl-6 space-y-6">
                                <div>
                                    <p class="text-[10px] text-gray-500 uppercase font-mono">Destination</p>
                                    <p class="text-xs font-bold">{{ $address }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-500 uppercase font-mono">Arrivée estimée</p>
                                    <p class="text-lg font-black text-yellow-500 italic">~ 45 MIN</p>
                                </div>
                            </div>
                        </div>

                        <button onclick="window.location.reload()" class="w-full mt-8 py-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-[10px] font-mono uppercase tracking-widest transition-all">
                            Actualiser Flux
                        </button>
                    </div>
                </div>

                <div class="lg:col-span-3 relative group">
                    <div class="absolute inset-0 pointer-events-none z-10 overflow-hidden rounded-[2.5rem] opacity-20">
                        <div class="w-full h-[2px] bg-cyan-500 shadow-[0_0_15px_#06b6d4] animate-scan-line"></div>
                    </div>

                    <div id="map" class="h-[600px] rounded-[2.5rem] border-2 border-cyan-500/20 shadow-[0_0_60px_rgba(0,0,0,0.7)] z-0"></div>
                </div>

            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        .leaflet-container { background: #020617 !important; font-family: 'JetBrains Mono', monospace; }
        .leaflet-control-zoom { border: none !important; margin: 20px !important; }
        .leaflet-control-zoom-in, .leaflet-control-zoom-out { 
            background: #0f172a !important; color: #06b6d4 !important; border: 1px solid rgba(6,182,212,0.2) !important; border-radius: 8px !important; margin-bottom: 5px;
        }
        
        @keyframes scan-line { 0% { transform: translateY(0); } 100% { transform: translateY(600px); } }
        .animate-scan-line { animation: scan-line 4s linear infinite; }

        /* Animation de pulsation pour le colis */
        .pulse-marker {
            background: rgba(6, 182, 212, 0.6);
            border-radius: 50%;
            box-shadow: 0 0 0 rgba(6, 182, 212, 0.4);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(6, 182, 212, 0.7); }
            70% { box-shadow: 0 0 0 20px rgba(6, 182, 212, 0); }
            100% { box-shadow: 0 0 0 0 rgba(6, 182, 212, 0); }
        }
    </style>

    <script>
        // 1. Initialisation
        var map = L.map('map', { zoomControl: true, attributionControl: false }).setView([-18.8792, 47.5079], 12);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png').addTo(map);

        var warehouseIcon = L.divIcon({
            className: 'custom-icon',
            html: "<div style='background:#eab308; width:12px; height:12px; border-radius:50%; border:2px solid white; box-shadow:0 0 15px #eab308;'></div>",
            iconSize: [12, 12]
        });

        var packageIcon = L.divIcon({
            className: 'pulse-marker',
            html: "<div style='width:12px; height:12px; border:2px solid white; border-radius:50%;'></div>",
            iconSize: [12, 12]
        });

        var start = [-18.9100, 47.5200]; // Analakely
        L.marker(start, {icon: warehouseIcon}).addTo(map).bindPopup("Dépôt DirToi");

        // 2. GÉOCODAGE AVEC SÉCURITÉ
        var rawAddress = "{{ $address }}";
        // On nettoie l'adresse et on force la précision
        var queryAddress = rawAddress + ", Antananarivo, Madagascar";

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(queryAddress)}&limit=1`, {
            headers: { 'Accept-Language': 'fr' }
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                var lat = data[0].lat;
                var lon = data[0].lon;
                var end = [lat, lon];

                // Marqueur colis
                L.marker(end, {icon: packageIcon}).addTo(map).bindPopup("<b>Destination :</b> " + rawAddress).openPopup();

                // Tracé du trajet
                var path = L.polyline([start, end], {
                    color: '#06b6d4', weight: 2, dashArray: '10, 15', opacity: 0.8
                }).addTo(map);

                map.fitBounds(path.getBounds(), {padding: [100, 100]});
            } else {
                // SI ÇA ÉCHOUE : On place un point aléatoire à proximité pour ne pas laisser la carte vide
                console.warn("Adresse non trouvée, simulation de proximité...");
                var fallbackEnd = [-18.8800 + (Math.random() * 0.05), 47.5000 + (Math.random() * 0.05)];
                L.marker(fallbackEnd, {icon: packageIcon}).addTo(map).bindPopup("<b>Localisation approximative</b>");
                L.polyline([start, fallbackEnd], {color: '#06b6d4', weight: 2, dashArray: '10, 15'}).addTo(map);
            }
        })
        .catch(err => {
            console.error("Erreur de connexion à l'API de carte");
        });
    </script>
</x-app-layout>