<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $brands = collect($this->brands())->mapWithKeys(function ($data, $name) {
            $brand = Brand::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, ...$data, 'is_active' => true]);
            return [$name => $brand];
        });
        $categories = collect($this->categories())->mapWithKeys(function ($description, $name) {
            $category = Category::updateOrCreate(['slug' => Str::slug($name)], compact('name', 'description') + ['is_active' => true]);
            return [$category->slug => $category];
        });

        $catalog = $this->products();
        foreach ($catalog as $data) {
            $product = Product::updateOrCreate(['slug' => Str::slug($data['brand'].' '.$data['name'])], [
                'brand_id' => $brands[$data['brand']]->id,
                'category_id' => $categories[$data['category']]->id,
                'name' => $data['name'], 'description' => $data['description'], 'gender' => $data['gender'],
                'watch_type' => $data['movement'] === 'smart' ? 'smart' : 'traditional',
                'movement' => $data['movement'], 'is_active' => true, 'is_featured' => true,
            ]);
            $product->specification()->updateOrCreate([], $this->specification($data));
            foreach ($data['variants'] as $index => [$name, $sku, $price]) {
                $product->variants()->updateOrCreate(['sku' => $sku], [
                    'name' => $name, 'price' => $price, 'compare_price' => null,
                    // Inventory cannot be sourced from a manufacturer; start at zero until physical stock is counted.
                    'stock_quantity' => 0, 'is_active' => true, 'is_default' => $index === 0,
                ]);
            }
            $product->variants()->whereNotIn('sku', array_column($data['variants'], 1))->delete();
        }

        // Keep reruns deterministic: exactly the 20 products defined above (two per brand).
        $catalogSlugs = array_map(fn ($data) => Str::slug($data['brand'].' '.$data['name']), $catalog);
        $obsolete = Product::whereIn('brand_id', $brands->pluck('id'))->whereNotIn('slug', $catalogSlugs);
        $orderedProductIds = DB::table('order_items')
            ->join('product_variants', 'product_variants.id', '=', 'order_items.variant_id')
            ->whereIn('product_variants.product_id', (clone $obsolete)->pluck('products.id'))
            ->pluck('product_variants.product_id')->unique();

        (clone $obsolete)->whereNotIn('id', $orderedProductIds)->delete();
        (clone $obsolete)->whereIn('id', $orderedProductIds)->update(['is_active' => false, 'is_featured' => false]);
        DB::table('product_variants')->whereIn('product_id', $orderedProductIds)
            ->update(['is_active' => false, 'is_default' => false]);
    }

    private function brands(): array
    {
        return [
            'Seiko' => ['tier'=>'premium','description'=>'Japanese watchmaking known for dependable mechanical movements and purposeful design.'],
            'Citizen' => ['tier'=>'premium','description'=>'Japanese precision with a strong tradition of light-powered timekeeping.'],
            'Casio' => ['tier'=>'everyday','description'=>'Practical digital and analog watches made for everyday reliability.'],
            'Orient' => ['tier'=>'premium','description'=>'Japanese mechanical watches built around in-house movements and accessible value.'],
            'Tissot' => ['tier'=>'premium','description'=>'Swiss watchmaking balancing heritage, sport and contemporary detail.'],
            'Hamilton' => ['tier'=>'premium','description'=>'Swiss-made watches with American roots and a long connection to field timekeeping.'],
            'Longines' => ['tier'=>'luxury','description'=>'Elegant Swiss watches grounded in precision, sport and classic proportion.'],
            'Rado' => ['tier'=>'luxury','description'=>'Modern Swiss design recognised for material experimentation and clean silhouettes.'],
            'Garmin' => ['tier'=>'smart_sport','description'=>'Connected sport watches built around training, navigation and daily health.'],
            'Omega' => ['tier'=>'luxury','description'=>'Swiss precision with enduring links to exploration, timing and ocean performance.'],
        ];
    }

    private function categories(): array
    {
        return [
            'Dress & Formal'=>'Refined watches with balanced proportions for considered occasions.',
            'Dive & Sport'=>'Robust watches designed for water, activity and clear reading at a glance.',
            'Field & Adventure'=>'Legible, resilient watches influenced by exploration and utility.',
            'Casual & Everyday'=>'Versatile watches made to settle naturally into a daily rotation.',
            'Chronographs'=>'Multi-register timing watches with technical character and sporting roots.',
            'Smart Watches'=>'Connected watches for training, navigation and everyday health insights.',
        ];
    }

    private function products(): array
    {
        return [
            $this->p('Seiko','Presage Cocktail Time','dress-formal','automatic','men','Seiko’s signature cocktail-bar-inspired automatic dress watch.','40.5 mm','50 m','4R35','41 hours',[['Blue Moon / bracelet','SRPB41',475],['Skydiving / leather','SRPB43',450]]),
            $this->p('Seiko','Prospex Turtle','dive-sport','automatic','men','The recognizable cushion-case Prospex ISO dive watch.','45 mm','200 m','4R36','41 hours',[['Black / silicone','SRPE93',525],['PADI blue-red / silicone','SRPE99',575]]),
            $this->p('Citizen','Tsuyosa Automatic','casual-everyday','automatic','unisex','Citizen’s popular integrated-bracelet automatic watch with sapphire crystal.','40 mm','50 m','8210','42 hours',[['Yellow dial','NJ0150-56Z',450],['Blue dial','NJ0150-56L',450]]),
            $this->p('Citizen','Promaster Dive Eco-Drive','dive-sport','quartz','men','An ISO-compliant light-powered professional dive watch.','44 mm','200 m','E168',null,[['Black dial','BN0150-28E',395],['Blue dial','BN0151-09L',395]]),
            $this->p('Casio','Vintage AQ-230','casual-everyday','quartz','unisex','A compact analog-digital Casio classic with dual time, alarm and stopwatch.','38.8 × 29.8 mm','Water resistant',null,null,[['Silver / white dial','AQ-230A-7AVT',49.95],['Silver / blue dial','AQ-230A-2A2VT',59.95]]),
            $this->p('Casio','G-Shock GA-2100','dive-sport','quartz','unisex','The slim octagonal “CasiOak” with Carbon Core Guard construction.','48.5 × 45.4 mm','200 m',null,null,[['Stealth black','GA2100-1A1',99],['Red','GA2100-4A',99]]),
            $this->p('Orient','Bambino Version 2','dress-formal','automatic','men','A popular mechanical dress watch with classical styling and domed crystal.','40.5 mm','30 m','F6724','40 hours',[['Cream / brown leather','FAC00009N0',335],['Black / black leather','FAC0000CA0',335]]),
            $this->p('Orient','Kamasu','dive-sport','automatic','men','A well-known automatic diver with sapphire crystal and in-house movement.','41.8 mm','200 m','F6922','40 hours',[['Red dial','RA-AA0003R39B',375],['Green dial','RA-AA0004E19B',375]]),
            $this->p('Tissot','PRX Powermatic 80','casual-everyday','automatic','unisex','The celebrated 1970s-inspired integrated-bracelet Swiss sports watch.','40 mm','100 m','Powermatic 80','80 hours',[['Blue dial','T137.407.11.041.00',850],['Black dial','T137.407.11.051.00',850]]),
            $this->p('Tissot','Gentleman Powermatic 80 Silicium','dress-formal','automatic','men','A versatile Swiss automatic watch with silicon balance spring.','40 mm','100 m','Powermatic 80 Silicium','80 hours',[['Blue / bracelet','T127.407.11.041.00',950],['Black / bracelet','T127.407.11.051.00',950]]),
            $this->p('Hamilton','Khaki Field Mechanical 38','field-adventure','mechanical','unisex','Hamilton’s hand-wound original soldier’s watch.','38 mm','50 m','H-50','80 hours',[['Black / green NATO','H69439931',625],['White / green NATO','H69439411',625]]),
            $this->p('Hamilton','Khaki Field Murph 38','field-adventure','automatic','unisex','The compact version of Hamilton’s film-famous Murph field watch.','38 mm','100 m','H-10','80 hours',[['Black / leather','H70405730',995],['White / leather','H70405710',995]]),
            $this->p('Longines','HydroConquest Automatic 41','dive-sport','automatic','men','Longines’ modern ceramic-bezel automatic dive watch.','41 mm','300 m','L888','72 hours',[['Blue / bracelet','L3.781.4.96.6',1900],['Black / bracelet','L3.781.4.56.6',1900]]),
            $this->p('Longines','Spirit Zulu Time 39','field-adventure','automatic','unisex','A COSC-certified true GMT travel watch with ceramic 24-hour bezel.','39 mm','100 m','L844.4','72 hours',[['Blue / bracelet','L3.802.4.93.6',3350],['Black / bracelet','L3.802.4.53.6',3350]]),
            $this->p('Rado','Captain Cook Automatic 42','dive-sport','automatic','men','Rado’s 1962 dive design reimagined with a ceramic bezel.','42 mm','300 m','R763','80 hours',[['Blue / bracelet','R32505203',2500],['Red gradient / bracelet','R32105353',2650]]),
            $this->p('Rado','True Square Automatic Open Heart','dress-formal','automatic','unisex','A square monobloc high-tech ceramic open-heart automatic watch.','38 mm','50 m',null,'80 hours',[['Black ceramic','R27086162',3000],['White ceramic','R27073012',2950]]),
            $this->p('Garmin','fēnix 8 AMOLED 47 mm','smart-watches','smart','unisex','Garmin’s flagship AMOLED multisport GPS smartwatch.','47 mm','100 m',null,null,[['Slate / black silicone','010-02904-00',1099.99],['Sapphire titanium / orange','010-02904-20',1199.99]]),
            $this->p('Garmin','Venu 3','smart-watches','smart','unisex','A health-focused AMOLED GPS smartwatch with calling and sleep coaching.','45 mm','50 m',null,null,[['Slate / black','010-02784-01',449.99],['Silver / whitestone','010-02784-00',449.99]]),
            $this->p('Omega','Speedmaster Moonwatch Professional','chronographs','mechanical','men','The legendary Master Chronometer hand-wound Moonwatch.','42 mm','50 m','3861','50 hours',[['Hesalite / bracelet','310.30.42.50.01.001',7800],['Sapphire / bracelet','310.30.42.50.01.002',9000]]),
            $this->p('Omega','Seamaster Diver 300M','dive-sport','automatic','men','Omega’s signature ceramic-dial Master Chronometer dive watch.','42 mm','300 m','8800','55 hours',[['Blue / bracelet','210.30.42.20.03.001',6500],['Black / rubber','210.32.42.20.01.001',6200]]),
        ];
    }

    private function p($brand,$name,$category,$movement,$gender,$description,$caseSize,$water,$caliber,$reserve,$variants): array
    {
        return compact('brand','name','category','movement','gender','description','caseSize','water','caliber','reserve','variants');
    }

    private function specification(array $p): array
    {
        $smart = $p['movement'] === 'smart';
        return [
            'case_size'=>$p['caseSize'],'case_material'=>null,'case_thickness'=>null,'water_resistance'=>$p['water'],
            'glass_type'=>null,'weight'=>null,'dial_color'=>null,'movement_caliber'=>$p['caliber'],
            'power_reserve'=>$p['reserve'],'frequency'=>null,'jewels'=>null,'functions'=>null,'strap_material'=>null,
            'clasp_type'=>null,'battery_life'=>$smart ? ($p['name']==='Venu 3' ? 'Up to 14 days' : 'Up to 16 days') : null,
            'display_type'=>$smart ? 'AMOLED' : null,'connectivity'=>$smart ? 'Bluetooth, ANT+, Wi-Fi, GPS' : null,
            'compatibility'=>$smart ? 'iOS and Android' : null,
            'country_of_origin'=>in_array($p['brand'],['Seiko','Citizen','Casio','Orient']) ? 'Japan' : ($p['brand']==='Garmin' ? null : 'Switzerland'),
            'custom_specifications'=>['References'=>implode(', ',array_column($p['variants'],1))],
        ];
    }
}
