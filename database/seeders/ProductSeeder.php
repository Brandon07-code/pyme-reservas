<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(int $categoriaPerfumesId): void
    {
      
        $perfumes = [
            
            ['nombre' => '212 VIP Men', 'marca' => 'Carolina Herrera', 'genero' => 'Masculino', 'precio' => 20000, 'img' => 'products/212_vip_men.jpg'],
            ['nombre' => 'Bad Boy', 'marca' => 'Carolina Herrera', 'genero' => 'Masculino', 'precio' => 25000, 'img' => 'products/bad_boy.jpg'],
            ['nombre' => 'CH Men Privé', 'marca' => 'Carolina Herrera', 'genero' => 'Masculino', 'precio' => 20000, 'img' => 'products/ch_men.jpg'],
            ['nombre' => 'One Million', 'marca' => 'Paco Rabanne', 'genero' => 'Masculino', 'precio' => 15000, 'img' => 'products/one_million.jpg'],
            ['nombre' => 'Invictus', 'marca' => 'Paco Rabanne', 'genero' => 'Masculino', 'precio' => 15000, 'img' => 'products/invictus.jpg'],
            ['nombre' => 'Sauvage', 'marca' => 'Dior', 'genero' => 'Masculino', 'precio' => 25000, 'img' => 'products/sauvage.jpg'],
            ['nombre' => 'Acqua di Gio', 'marca' => 'Giorgio Armani', 'genero' => 'Masculino', 'precio' => 15000, 'img' => 'products/acqua_di_gio.jpg'],
            ['nombre' => 'Stronger With You', 'marca' => 'Emporio Armani', 'genero' => 'Masculino', 'precio' => 20000, 'img' => 'products/stronger_with_you.jpg'],
            ['nombre' => 'Le Male', 'marca' => 'Jean Paul Gaultier', 'genero' => 'Masculino', 'precio' => 15000, 'img' => 'products/le_male.jpg'],
            ['nombre' => 'Le Beau', 'marca' => 'Jean Paul Gaultier', 'genero' => 'Masculino', 'precio' => 20000, 'img' => 'products/le_beau.jpg'],
            ['nombre' => 'Eros', 'marca' => 'Versace', 'genero' => 'Masculino', 'precio' => 15000, 'img' => 'products/eros.jpg'],
            ['nombre' => 'Eros Flame', 'marca' => 'Versace', 'genero' => 'Masculino', 'precio' => 20000, 'img' => 'products/eros_flame.jpg'],
            ['nombre' => 'Lattafa Asad', 'marca' => 'Lattafa', 'genero' => 'Masculino', 'precio' => 12000, 'img' => 'products/asad.jpg'],
            ['nombre' => 'Afnan 9PM', 'marca' => 'Afnan', 'genero' => 'Masculino', 'precio' => 12000, 'img' => 'products/9pm.jpg'],
            ['nombre' => 'Club de Nuit Intense Man', 'marca' => 'Armaf', 'genero' => 'Masculino', 'precio' => 15000, 'img' => 'products/club_nuit_intense.jpg'],
            ['nombre' => 'Rasasi Hawas', 'marca' => 'Rasasi', 'genero' => 'Masculino', 'precio' => 15000, 'img' => 'products/hawas.jpg'],
            ['nombre' => 'Bleu de Chanel', 'marca' => 'Chanel', 'genero' => 'Masculino', 'precio' => 25000, 'img' => 'products/bleu_chanel.jpg'],
            ['nombre' => 'Y EDP', 'marca' => 'Yves Saint Laurent', 'genero' => 'Masculino', 'precio' => 20000, 'img' => 'products/ysl_y.jpg'],
            ['nombre' => 'La Nuit de L\'Homme', 'marca' => 'Yves Saint Laurent', 'genero' => 'Masculino', 'precio' => 20000, 'img' => 'products/la_nuit.jpg'],
            ['nombre' => 'Terre d\'Hermès', 'marca' => 'Hermès', 'genero' => 'Masculino', 'precio' => 25000, 'img' => 'products/terre_hermes.jpg'],
            ['nombre' => 'Aventus', 'marca' => 'Creed', 'genero' => 'Masculino', 'precio' => 25000, 'img' => 'products/aventus.jpg'],
            ['nombre' => 'Spicebomb Extreme', 'marca' => 'Viktor&Rolf', 'genero' => 'Masculino', 'precio' => 20000, 'img' => 'products/spicebomb.jpg'],
            ['nombre' => 'The Most Wanted', 'marca' => 'Emporio Armani', 'genero' => 'Masculino', 'precio' => 20000, 'img' => 'products/most_wanted.jpg'],
            ['nombre' => 'Dylan Blue', 'marca' => 'Versace', 'genero' => 'Masculino', 'precio' => 15000, 'img' => 'products/dylan_blue.jpg'],
            ['nombre' => 'Explorer', 'marca' => 'Montblanc', 'genero' => 'Masculino', 'precio' => 15000, 'img' => 'products/explorer.jpg'],
            ['nombre' => 'Legend', 'marca' => 'Montblanc', 'genero' => 'Masculino', 'precio' => 12000, 'img' => 'products/legend.jpg'],
            ['nombre' => 'Fahrenheit', 'marca' => 'Hermès', 'genero' => 'Masculino', 'precio' => 20000, 'img' => 'products/fahrenheit.jpg'],
            ['nombre' => 'Light Blue', 'marca' => 'Dolce & Gabbana', 'genero' => 'Masculino', 'precio' => 15000, 'img' => 'products/light_blue_men.jpg'],
            ['nombre' => 'K', 'marca' => 'Dolce & Gabbana', 'genero' => 'Masculino', 'precio' => 20000, 'img' => 'products/dg_k.jpg'],
            ['nombre' => 'Polo Blue', 'marca' => 'Ralph Lauren', 'genero' => 'Masculino', 'precio' => 15000, 'img' => 'products/polo_blue.jpg'],

            ['nombre' => 'Good Girl', 'marca' => 'Carolina Herrera', 'genero' => 'Femenino', 'precio' => 25000, 'img' => 'products/good_girl.jpg'],
            ['nombre' => '212 Sexy', 'marca' => 'Carolina Herrera', 'genero' => 'Femenino', 'precio' => 15000, 'img' => 'products/212_sexy.jpg'],
            ['nombre' => 'CH Women', 'marca' => 'Carolina Herrera', 'genero' => 'Femenino', 'precio' => 20000, 'img' => 'products/ch_women.jpg'],
            ['nombre' => 'La Vie Est Belle', 'marca' => 'Lancôme', 'genero' => 'Femenino', 'precio' => 20000, 'img' => 'products/la_vie_est_belle.jpg'],
            ['nombre' => 'Idôle', 'marca' => 'Lancôme', 'genero' => 'Femenino', 'precio' => 20000, 'img' => 'products/idole.jpg'],
            ['nombre' => 'Black Opium', 'marca' => 'Yves Saint Laurent', 'genero' => 'Femenino', 'precio' => 25000, 'img' => 'products/black_opium.jpg'],
            ['nombre' => 'Libre', 'marca' => 'Yves Saint Laurent', 'genero' => 'Femenino', 'precio' => 25000, 'img' => 'products/libre.jpg'],
            ['nombre' => 'Chance', 'marca' => 'Chanel', 'genero' => 'Femenino', 'precio' => 25000, 'img' => 'products/chance.jpg'],
            ['nombre' => 'Coco Mademoiselle', 'marca' => 'Chanel', 'genero' => 'Femenino', 'precio' => 25000, 'img' => 'products/coco_mademoiselle.jpg'],
            ['nombre' => 'Sofía Vergara Love', 'marca' => 'Sofía Vergara', 'genero' => 'Femenino', 'precio' => 12000, 'img' => 'products/sofia_love.jpg'],
            ['nombre' => 'Sofía Vergara Tempting', 'marca' => 'Sofía Vergara', 'genero' => 'Femenino', 'precio' => 12000, 'img' => 'products/sofia_tempting.jpg'],
            ['nombre' => 'Olympea', 'marca' => 'Paco Rabanne', 'genero' => 'Femenino', 'precio' => 20000, 'img' => 'products/olympea.jpg'],
            ['nombre' => 'Lady Million', 'marca' => 'Paco Rabanne', 'genero' => 'Femenino', 'precio' => 15000, 'img' => 'products/lady_million.jpg'],
            ['nombre' => 'Pure XS', 'marca' => 'Paco Rabanne', 'genero' => 'Femenino', 'precio' => 20000, 'img' => 'products/pure_xs_women.jpg'],
            ['nombre' => 'Sì', 'marca' => 'Giorgio Armani', 'genero' => 'Femenino', 'precio' => 20000, 'img' => 'products/si_armani.jpg'],
            ['nombre' => 'My Way', 'marca' => 'Giorgio Armani', 'genero' => 'Femenino', 'precio' => 25000, 'img' => 'products/my_way.jpg'],
            ['nombre' => 'Alien', 'marca' => 'Mugler', 'genero' => 'Femenino', 'precio' => 25000, 'img' => 'products/alien.jpg'],
            ['nombre' => 'Angel', 'marca' => 'Mugler', 'genero' => 'Femenino', 'precio' => 25000, 'img' => 'products/angel.jpg'],
            ['nombre' => 'Scandal', 'marca' => 'Jean Paul Gaultier', 'genero' => 'Femenino', 'precio' => 20000, 'img' => 'products/scandal.jpg'],
            ['nombre' => 'La Belle', 'marca' => 'Jean Paul Gaultier', 'genero' => 'Femenino', 'precio' => 20000, 'img' => 'products/la_belle.jpg'],
            ['nombre' => 'Light Blue', 'marca' => 'Dolce & Gabbana', 'genero' => 'Femenino', 'precio' => 15000, 'img' => 'products/light_blue_women.jpg'],
            ['nombre' => 'The Only One', 'marca' => 'Dolce & Gabbana', 'genero' => 'Femenino', 'precio' => 20000, 'img' => 'products/the_only_one.jpg'],
            ['nombre' => 'Bright Crystal', 'marca' => 'Versace', 'genero' => 'Femenino', 'precio' => 15000, 'img' => 'products/bright_crystal.jpg'],
            ['nombre' => 'Crystal Noir', 'marca' => 'Versace', 'genero' => 'Femenino', 'precio' => 20000, 'img' => 'products/crystal_noir.jpg'],
            ['nombre' => 'Daisy', 'marca' => 'Chanel', 'genero' => 'Femenino', 'precio' => 20000, 'img' => 'products/daisy.jpg'], // Marc Jacobs as Chanel typo intended? Kept for realism if dupe
            ['nombre' => 'J\'adore', 'marca' => 'Dior', 'genero' => 'Femenino', 'precio' => 25000, 'img' => 'products/jadore.jpg'],
            ['nombre' => 'Miss Dior', 'marca' => 'Dior', 'genero' => 'Femenino', 'precio' => 25000, 'img' => 'products/miss_dior.jpg'],
            ['nombre' => 'Cloud', 'marca' => 'Ariana Grande', 'genero' => 'Femenino', 'precio' => 12000, 'img' => 'products/cloud.jpg'],
            ['nombre' => 'Sweet Like Candy', 'marca' => 'Ariana Grande', 'genero' => 'Femenino', 'precio' => 12000, 'img' => 'products/sweet_like_candy.jpg'],
            ['nombre' => 'Lattafa Yara', 'marca' => 'Lattafa', 'genero' => 'Femenino', 'precio' => 15000, 'img' => 'products/yara.jpg'],

            ['nombre' => 'Baccarat Rouge 540', 'marca' => 'Maison Francis Kurkdjian', 'genero' => 'Unisex', 'precio' => 25000, 'img' => 'products/baccarat.jpg'],
            ['nombre' => 'Erba Pura', 'marca' => 'Xerjoff', 'genero' => 'Unisex', 'precio' => 25000, 'img' => 'products/erba_pura.jpg'],
            ['nombre' => 'Ombre Nomade', 'marca' => 'Louis Vuitton', 'genero' => 'Unisex', 'precio' => 25000, 'img' => 'products/ombre_nomade.jpg'],
            ['nombre' => 'Lost Cherry', 'marca' => 'Tom Ford', 'genero' => 'Unisex', 'precio' => 25000, 'img' => 'products/lost_cherry.jpg'],
            ['nombre' => 'Tobacco Vanille', 'marca' => 'Tom Ford', 'genero' => 'Unisex', 'precio' => 25000, 'img' => 'products/tobacco_vanille.jpg'],
            ['nombre' => 'Lattafa Khamrah', 'marca' => 'Lattafa', 'genero' => 'Unisex', 'precio' => 15000, 'img' => 'products/khamrah.jpg'],
            ['nombre' => 'CK One', 'marca' => 'Calvin Klein', 'genero' => 'Unisex', 'precio' => 12000, 'img' => 'products/ck_one.jpg'],
            ['nombre' => 'Club de Nuit Untold', 'marca' => 'Armaf', 'genero' => 'Unisex', 'precio' => 15000, 'img' => 'products/untold.jpg'],
            ['nombre' => 'Amber Oud Gold Edition', 'marca' => 'Al Haramain', 'genero' => 'Unisex', 'precio' => 15000, 'img' => 'products/amber_oud.jpg'],
            ['nombre' => 'Oud for Greatness', 'marca' => 'Initio', 'genero' => 'Unisex', 'precio' => 25000, 'img' => 'products/oud_greatness.jpg'],
        ];

        foreach ($perfumes as $p) {
            Product::create([
                'product_category_id' => $categoriaPerfumesId,
                'nombre' => $p['nombre'],
                'marca' => $p['marca'],
                'genero' => $p['genero'],
                'precio' => $p['precio'],
                
                'stock_actual' => rand(0, 15), 
                'imagen_url' => $p['img'],
                'estado' => true
            ]);
        }
    }
}