<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(int $categoriaPerfumesId): void
    {
        $perfumes = [
            // MASCULINOS (6)
            ['nombre' => '212 VIP Men', 'marca' => 'Carolina Herrera', 'genero' => 'Masculino', 'precio' => 20000, 'img' => 'products/212_vip_men.jpg'],
            ['nombre' => 'One Million', 'marca' => 'Paco Rabanne', 'genero' => 'Masculino', 'precio' => 15000, 'img' => 'products/one_million.jpg'],
            ['nombre' => 'Stronger With You', 'marca' => 'Emporio Armani', 'genero' => 'Masculino', 'precio' => 20000, 'img' => 'products/stronger_with_you.jpg'],
            ['nombre' => 'Le Male', 'marca' => 'Jean Paul Gaultier', 'genero' => 'Masculino', 'precio' => 15000, 'img' => 'products/le_male.jpg'],
            ['nombre' => 'Lattafa Asad', 'marca' => 'Lattafa', 'genero' => 'Masculino', 'precio' => 12000, 'img' => 'products/asad.jpg'],
            ['nombre' => 'Sauvage', 'marca' => 'Dior', 'genero' => 'Masculino', 'precio' => 25000, 'img' => 'products/sauvage.jpg'],

            // FEMENINOS (4)
            ['nombre' => 'Good Girl', 'marca' => 'Carolina Herrera', 'genero' => 'Femenino', 'precio' => 25000, 'img' => 'products/good_girl.jpg'],
            ['nombre' => 'Sofía Vergara Love', 'marca' => 'Sofía Vergara', 'genero' => 'Femenino', 'precio' => 12000, 'img' => 'products/sofia_love.jpg'],
            ['nombre' => 'Yara Lattafa', 'marca' => 'Lattafa', 'genero' => 'Femenino', 'precio' => 15000, 'img' => 'products/yara.jpg'],
            ['nombre' => '212 Heroes For Her', 'marca' => 'Carolina Herrera', 'genero' => 'Femenino', 'precio' => 20000, 'img' => 'products/212_heroes_her.jpg'],

            // UNISEX (2)
            ['nombre' => 'Baccarat Rouge 540', 'marca' => 'Maison Francis Kurkdjian', 'genero' => 'Unisex', 'precio' => 25000, 'img' => 'products/baccarat.jpg'],
            ['nombre' => 'Eros', 'marca' => 'Versace', 'genero' => 'Unisex', 'precio' => 15000, 'img' => 'products/eros.jpg'], // Eros catalogado como unisex en esta tienda
        ];

        foreach ($perfumes as $p) {
            Product::create([
                'product_category_id' => $categoriaPerfumesId,
                'nombre' => $p['nombre'],
                'marca' => $p['marca'],
                'genero' => $p['genero'],
                'precio' => $p['precio'],
                'stock_actual' => rand(2, 10), // Inventario realista
                'imagen_url' => $p['img'],
                'estado' => true
            ]);
        }
    }
}