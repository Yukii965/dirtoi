<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="relative rounded-3xl overflow-hidden mb-16 h-80 flex items-center shadow-2xl border border-gray-800">
                <img src="https://source.unsplash.com/featured/?cyberpunk,city,neon" class="absolute inset-0 w-full h-full object-cover opacity-60">
                <div class="absolute inset-0 bg-gradient-to-r from-gray-950 via-gray-950/80 to-transparent"></div>
                
                <div class="relative z-10 px-12">
                    <span class="inline-block bg-yellow-500 text-gray-950 text-xs font-bold px-3 py-1 rounded-full mb-4">TECHNOLOGIE 2026</span>
                    <h1 class="text-6xl font-extrabold text-white leading-tight tracking-tighter">
                        Dir<span class="text-yellow-500">Toi</span> Quantum
                    </h1>
                    <p class="text-gray-300 mt-3 text-xl max-w-xl">
                        L'avenir est ici. Découvrez des produits exclusifs que vous ne trouverez nulle part ailleurs sur l'île.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($products as $product)
                <div class="group bg-gray-900 rounded-3xl overflow-hidden shadow-lg hover:shadow-cyan-900/50 transition-all duration-500 transform hover:-translate-y-2 border border-gray-800 hover:border-cyan-500/50">
                    
                    <div class="overflow-hidden h-60 relative">
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                             alt="{{ $product->name }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>

                    <div class="p-6">
                        <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest">{{ $product->category->name }}</span>
                        <h3 class="text-xl font-bold text-white mt-1 group-hover:text-cyan-300 transition-colors">{{ $product->name }}</h3>
                        
                        <div class="flex items-center justify-between mt-6">
                            <span class="text-3xl font-black text-white">{{ number_format($product->price, 0, ',', ' ') }} Ar</span>
                            
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button class="bg-gray-800 text-white p-4 rounded-2xl hover:bg-cyan-500 hover:text-gray-950 transition-all duration-300 shadow-lg active:scale-95 group-hover:rotate-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>