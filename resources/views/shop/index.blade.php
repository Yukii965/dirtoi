@extends('layouts.app')

@section('content')
<div class="grid grid-cols-3 gap-4">
    @foreach($products as $product)
    <div class="border p-4 rounded shadow">
        <img src="{{ asset('storage/' . $product->image) }}" alt="">
        <h2 class="text-xl font-bold">{{ $product->name }}</h2>
        <p>{{ $product->price }} €</p>
        <form action="{{ route('cart.add', $product->id) }}" method="POST">
            @csrf
            <button class="bg-blue-500 text-white px-4 py-2 mt-2">Ajouter au panier</button>
        </form>
    </div>
    @endforeach
</div>
@endsection