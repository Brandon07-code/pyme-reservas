<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_cancelar_pedido_devuelve_stock_al_inventario()
    {
        // 1. Preparar el entorno (Admin, Categoría, Cliente)
        $admin = User::factory()->create(['role_id' => 1]);
        $cliente = Client::factory()->create();
        $categoria = ProductCategory::create(['nombre' => 'Perfumería', 'estado' => 1]);

        // 2. Crear un producto con exactamente 10 de stock
        $producto = Product::create([
            'product_category_id' => $categoria->id,
            'nombre' => 'Perfume Test',
            'precio' => 10000,
            'stock_actual' => 10,
            'estado' => 1
        ]);

        // 3. Crear un pedido pendiente para el cliente
        $order = Order::create([
            'client_id' => $cliente->id,
            'estado' => 'pendiente_recogida',
            'total' => 20000
        ]);

        // Simular que el cliente metió 2 unidades en ese pedido
        $order->products()->attach($producto->id, [
            'cantidad' => 2,
            'precio_historico' => 10000
        ]);

        // El stock en la vida real habría bajado a 8 al comprar. Simulamos eso.
        $producto->update(['stock_actual' => 8]);

        // 4. Ejecutar la acción: El Admin cancela el pedido por la ruta
        $this->actingAs($admin)->put(route('orders.update', $order), [
            'estado' => 'cancelado'
        ]);

        // 5. Afirmación: Refrescar el producto de la BD y confirmar que el stock volvió a 10
        $producto->refresh();
        $this->assertEquals(10, $producto->stock_actual);
        
        // Confirmar que el pedido quedó cancelado
        $order->refresh();
        $this->assertEquals('cancelado', $order->estado);
    }
}
