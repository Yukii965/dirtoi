<x-app-layout>
    <div class="py-12">
        <div style="max-width:48rem; margin:0 auto; padding:0 1.5rem;">

            <h1 class="text-3xl font-black mb-8 text-center"
                style="font-family:'Playfair Display',serif; color:#F4A429">
                💬 Mes messages
            </h1>

            @if($conversations->isEmpty())
                <div style="text-align:center; padding:4rem 1rem; border-radius:1rem;
                            background:rgba(44,26,14,0.85); border:2px dashed rgba(244,164,41,0.2)">
                    <div style="font-size:3rem; margin-bottom:1rem;">📭</div>
                    <p style="color:rgba(253,246,236,0.5)">Aucune conversation pour le moment.</p>
                    <a href="{{ route('products.index') }}"
                       style="display:inline-block; margin-top:1rem; padding:0.5rem 1.5rem;
                              border-radius:0.75rem; font-size:0.875rem; font-weight:700;
                              background:rgba(244,164,41,0.15); color:#F4A429; text-decoration:none;">
                        Découvrir les produits
                    </a>
                </div>
            @else
                <div style="display:flex; flex-direction:column; gap:0.75rem;">
                    @foreach($conversations as $conv)
                        @php
                            $other   = $conv->otherParty(auth()->id());
                            $unread  = $conv->unreadCount(auth()->id());
                            $last    = $conv->lastMessage;
                        @endphp
                        @foreach($conversations as $conv)
                        @php
                            $other  = $conv->otherParty(auth()->id());
                            $unread = $conv->unreadCount(auth()->id());
                            $last   = $conv->lastMessage;
                        @endphp

                        {{-- Conteneur carte + bouton supprimer --}}
                        <div style="display:flex; align-items:stretch; gap:0.5rem;">

                            {{-- Lien vers la conversation --}}
                            <a href="{{ route('messages.show', $conv) }}"
                            style="flex:1; display:flex; align-items:center; gap:1rem;
                                    padding:1rem 1.25rem; border-radius:1rem; text-decoration:none;
                                    background:rgba(44,26,14,0.85);
                                    border:1px solid {{ $unread > 0 ? 'rgba(244,164,41,0.4)' : 'rgba(244,164,41,0.15)' }};">

                                {{-- Avatar --}}
                                <div style="width:2.75rem; height:2.75rem; border-radius:50%; flex-shrink:0;
                                            display:flex; align-items:center; justify-content:center;
                                            font-weight:900; font-size:1rem; color:#2C1A0E;
                                            background:linear-gradient(135deg,#F4A429,#E07B2A);">
                                    @if($other->avatar)
                                        <img src="{{ asset('storage/'.$other->avatar) }}"
                                            style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                                    @else
                                        {{ strtoupper(substr($other->name, 0, 1)) }}
                                    @endif
                                </div>

                                {{-- Infos --}}
                                <div style="flex:1; min-width:0;">
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <span style="font-weight:700; color:#FDF6EC; font-size:0.9rem;">
                                            {{ $other->name }}
                                        </span>
                                        @if($unread > 0)
                                            <span style="background:#F4A429; color:#2C1A0E; font-size:0.7rem;
                                                        font-weight:900; padding:0.15rem 0.5rem; border-radius:9999px;">
                                                {{ $unread }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($conv->product)
                                        <span style="font-size:0.7rem; color:rgba(244,164,41,0.7);">
                                            À propos de : {{ $conv->product->name }}
                                        </span>
                                    @endif
                                    @if($last)
                                        <p style="font-size:0.8rem; color:rgba(253,246,236,0.4);
                                                white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:0.2rem;">
                                            {{ $last->sender_id === auth()->id() ? 'Vous : ' : '' }}{{ Str::limit($last->body, 60) }}
                                        </p>
                                    @endif
                                </div>
                            </a>

                            {{-- Bouton supprimer --}}
                            <form method="POST" action="{{ route('messages.destroy', $conv) }}"
                                onsubmit="return confirm('Supprimer cette conversation ?')"
                                style="display:flex; align-items:center;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="height:100%; padding:0 0.875rem; border-radius:1rem; cursor:pointer;
                                            background:rgba(239,68,68,0.08); color:#f87171;
                                            border:1px solid rgba(239,68,68,0.25); font-size:1rem;">
                                    🗑️
                                </button>
                            </form>

                        </div>
                    @endforeach
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>