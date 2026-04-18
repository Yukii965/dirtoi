namespace App\Services;

use Illuminate\Support\Facades\Session;

class CartService {
    public function add($product) {
        $cart = Session::get('cart', []);
        if(isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }
        Session::put('cart', $cart);
    }
}