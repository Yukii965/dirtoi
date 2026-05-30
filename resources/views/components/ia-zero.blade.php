<div x-data="{ 
        chatOpen: false, 
        tooltipOpen: false, 
        message: 'Initialisation du système...', 
        proactiveMessages: [
            'Analyse du flux de données en cours...',
            'Sécurisation des protocoles terminée.',
            'Optimisation du rendu visuel...',
            'Signal satellite : Stable.',
            'Scan des meilleures offres en cours...',
            'Vérification de l’intégrité du réseau.'
        ],
        chatHistory: [
            { role: 'bot', text: 'Connexion établie. Je suis IA-Zéro. Comment puis-je assister votre navigation ?' }
        ],
        userInput: '',

        init() {
            // Cycle de messages proactifs toutes les 5 secondes
            setInterval(() => {
                if (!this.chatOpen) {
                    this.generateProactiveInsight();
                }
            }, 5000);
        },

        generateProactiveInsight() {
            // Vérification dynamique des données du site
            let cartCount = {{ session('cart') ? count(session('cart')) : 0 }};
            let isAuth = {{ Auth::check() ? 'true' : 'false' }};
            
            this.tooltipOpen = true;

            if (cartCount > 0) {
                this.message = 'Alerte : ' + cartCount + ' unité(s) en attente dans le panier.';
            } else if (!isAuth) {
                this.message = 'Identification requise pour un accès complet au réseau.';
            } else {
                this.message = this.proactiveMessages[Math.floor(Math.random() * this.proactiveMessages.length)];
            }

            // Cache l'info-bulle après 3.5 secondes
            setTimeout(() => { 
                if(!this.chatOpen) this.tooltipOpen = false; 
            }, 3500);
        },

        sendMessage() {
            if (this.userInput.trim() === '') return;
            this.chatHistory.push({ role: 'user', text: this.userInput });
            let input = this.userInput.toLowerCase();
            this.userInput = '';
            
            setTimeout(() => {
                let response = 'Commande non reconnue. Synchronisation en cours.';
                if(input.includes('prix')) response = 'Les tarifs sont indexés en temps réel sur GasyMarket.';
                if(input.includes('merci')) response = 'À votre service, Citoyen.';
                if(input.includes('aide')) response = 'Je peux surveiller vos transactions et vos stocks.';
                
                this.chatHistory.push({ role: 'bot', text: response });
            }, 600);
        }
    }" 
    class="relative z-[100]">

    <div class="fixed bottom-8 right-8 flex flex-col items-end">
        <div x-show="tooltipOpen && !chatOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:leave="transition ease-in duration-300"
             class="mb-4 mr-4 p-4 bg-gray-900/95 backdrop-blur-md border border-cyan-500/50 rounded-2xl rounded-br-none shadow-[0_0_20px_rgba(6,182,212,0.3)] max-w-xs">
            <p class="text-cyan-400 font-mono text-[10px] uppercase tracking-widest mb-1 italic">IA-Zéro Broadcast</p>
            <p class="text-white text-sm italic" x-text="message"></p>
        </div>

        <div @click="chatOpen = !chatOpen"
             class="relative cursor-pointer group">
            <div class="absolute inset-0 bg-cyan-500/20 blur-2xl rounded-full group-hover:bg-cyan-500/40 transition-all"></div>
            <div class="relative w-16 h-16 bg-gray-900 border-2 border-cyan-500 rounded-full flex items-center justify-center shadow-[0_0_15px_rgba(6,182,212,0.5)] animate-float">
                <div class="w-8 h-2 bg-cyan-500 rounded-full shadow-[0_0_10px_#06b6d4] animate-pulse"></div>
            </div>
            <div class="w-8 h-1 bg-cyan-500/30 blur-sm mx-auto mt-4 rounded-full animate-shadow"></div>
        </div>
    </div>

    <div x-show="chatOpen" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed top-0 right-0 h-full w-full md:w-96 bg-gray-950/98 backdrop-blur-3xl border-l border-cyan-500/30 shadow-[-20px_0_60px_rgba(0,0,0,0.8)] flex flex-col">
        
        <div class="p-6 border-b border-gray-800 bg-gray-900/50 flex justify-between items-center">
            <div>
                <h3 class="text-cyan-400 font-black uppercase tracking-tighter">Neural Link</h3>
                <p class="text-[10px] text-gray-500 font-mono">ID: IA-ZERO_UNIT_01</p>
            </div>
            <button @click="chatOpen = false" class="text-gray-500 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-4">
            <template x-for="msg in chatHistory">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="msg.role === 'user' ? 'bg-cyan-600 text-gray-950' : 'bg-gray-800 text-white border border-gray-700'"
                         class="max-w-[85%] p-3 rounded-xl shadow-lg text-sm">
                        <p x-text="msg.text"></p>
                    </div>
                </div>
            </template>
        </div>

        <div class="p-6 border-t border-gray-800 bg-gray-900/50">
            <div class="relative">
                <input type="text" x-model="userInput" @keyup.enter="sendMessage()"
                       placeholder="Entrez une commande..." 
                       class="w-full bg-gray-950 border-gray-800 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 focus:ring-0">
                <button @click="sendMessage()" class="absolute right-2 top-2 text-cyan-500 hover:text-cyan-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-15px); } }
    @keyframes shadow { 0%, 100% { transform: scale(1); opacity: 0.3; } 50% { transform: scale(0.6); opacity: 0.1; } }
    .animate-float { animation: float 3s ease-in-out infinite; }
    .animate-shadow { animation: shadow 3s ease-in-out infinite; }
</style>