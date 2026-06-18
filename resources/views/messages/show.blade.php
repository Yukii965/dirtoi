<x-app-layout>
    <div class="py-12">
        <div style="max-width:48rem; margin:0 auto; padding:0 1.5rem; display:flex; flex-direction:column; gap:1.5rem;">

            {{-- En-tête --}}
            @php $other = $conversation->otherParty(auth()->id()); @endphp
            <div style="display:flex; align-items:center; gap:1rem;">
                <a href="{{ route('messages.index') }}"
                style="color:rgba(244,164,41,0.7); text-decoration:none; font-size:0.875rem; flex-shrink:0;">
                    ← Retour
                </a>

                <div style="width:2.5rem; height:2.5rem; border-radius:50%; flex-shrink:0;
                            display:flex; align-items:center; justify-content:center;
                            font-weight:900; color:#2C1A0E;
                            background:linear-gradient(135deg,#F4A429,#E07B2A);">
                    @if($other->avatar)
                        <img src="{{ asset('storage/'.$other->avatar) }}"
                            style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                    @else
                        {{ strtoupper(substr($other->name, 0, 1)) }}
                    @endif
                </div>

                <div style="flex:1; min-width:0;">
                    <div style="font-weight:700; color:#FDF6EC;">{{ $other->name }}</div>
                    @if($conversation->product)
                        <a href="{{ route('products.show', $conversation->product->slug) }}"
                        style="font-size:0.75rem; color:rgba(244,164,41,0.7); text-decoration:none;">
                            📦 {{ $conversation->product->name }}
                        </a>
                    @endif
                </div>

                {{-- Bouton supprimer la conversation --}}
                <form method="POST" action="{{ route('messages.destroy', $conversation) }}"
                    onsubmit="return confirm('Supprimer cette conversation ? Cette action est irréversible.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            style="padding:0.4rem 0.75rem; border-radius:0.75rem; font-size:0.75rem;
                                font-weight:700; cursor:pointer; flex-shrink:0;
                                background:rgba(239,68,68,0.1); color:#f87171;
                                border:1px solid rgba(239,68,68,0.3);">
                        🗑️ Supprimer
                    </button>
                </form>
            </div>

            {{-- Fil de messages --}}
            <div id="messages-feed"
                 style="display:flex; flex-direction:column; gap:0.75rem;
                        padding:1.25rem; border-radius:1rem; min-height:20rem; max-height:60vh;
                        overflow-y:auto; background:rgba(44,26,14,0.85);
                        border:1px solid rgba(244,164,41,0.2);">

                @if($messages->isEmpty())
                    <p style="text-align:center; color:rgba(253,246,236,0.3);
                               margin:auto; font-size:0.875rem;">
                        Commencez la conversation ci-dessous 👇
                    </p>
                @else
                    @foreach($messages as $msg)
                        @php $isMe = $msg->sender_id === auth()->id(); @endphp
                        <div style="display:flex; flex-direction:column;
                                    align-items:{{ $isMe ? 'flex-end' : 'flex-start' }};">
                            <div style="max-width:75%; padding:0.6rem 1rem; border-radius:1rem;
                                        font-size:0.875rem; line-height:1.5;
                                        background:{{ $isMe ? '#F4A429' : 'rgba(255,255,255,0.08)' }};
                                        color:{{ $isMe ? '#2C1A0E' : '#FDF6EC' }};
                                        border-radius:{{ $isMe ? '1rem 1rem 0.25rem 1rem' : '1rem 1rem 1rem 0.25rem' }};">
                                {{ $msg->body }}
                            </div>
                            <span style="font-size:0.65rem; color:rgba(253,246,236,0.3);
                                         margin-top:0.2rem;">
                                {{ $msg->created_at->format('d/m H:i') }}
                                @if($isMe && $msg->read_at) · Lu @endif
                            </span>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Zone de saisie --}}
            <form method="POST" action="{{ route('messages.send', $conversation) }}"
                  style="display:flex; gap:0.75rem; align-items:flex-end;">
                @csrf
                <textarea name="body" rows="2"
                          placeholder="Votre message…"
                          required
                          style="flex:1; border-radius:0.75rem; padding:0.75rem 1rem;
                                 font-size:0.875rem; resize:none; outline:none;
                                 background:rgba(255,255,255,0.08);
                                 border:1px solid rgba(244,164,41,0.2);
                                 color:#FDF6EC; font-family:inherit;"></textarea>
                <button type="submit"
                        style="padding:0.75rem 1.25rem; border-radius:0.75rem; border:none;
                               font-weight:700; font-size:0.875rem; cursor:pointer;
                               background:#F4A429; color:#2C1A0E; flex-shrink:0;">
                    Envoyer ➤
                </button>
            </form>

        </div>
    </div>

    <script>
        // Scroll automatique vers le bas à l'ouverture
        const feed = document.getElementById('messages-feed');
        feed.scrollTop = feed.scrollHeight;
    </script>
</x-app-layout>