<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LocationCode;
use App\Models\NationalSample;
use App\Models\GsCleanedPrice;
use App\Models\GsComponentItemPricesLocation;
use App\Models\GsComponentItemPricesCity;
use App\Models\GsComponentItemPricesCountry;
use App\Models\GsComponentItemPricesAdjustedCity;
use Illuminate\Support\Facades\DB;


class GreenDataController extends Controller
{
	public function index() {
		return view('admin.green-tables.index');
	}

	public function runScript() {
		ini_set('max_execution_time', '300');
		
		$result = DB::select("SELECT *, (price * (1+rate)) as amount_with_tax
			FROM sale_tax_samples as stax
			INNER JOIN
			(SELECT nation.location_codes AS n_location_codes, 
				location.location_codes AS l_location_codes,
				item_codes,
				location.postal_code AS zip_codes,
				nation.product,
				nation.price,
				nation.currency AS currency_code,
				nation.units,
				nation.website,
				nation.store 
			FROM `national_samples` as nation
			INNER JOIN location_codes as location ON SUBSTR(nation.location_codes, 1, 3) = SUBSTR(location.location_codes, 1, 3)
			) as first ON first.l_location_codes = stax.location_codes and first.item_codes = stax.item_codes
		");

		foreach($result as $k => $v) {
			GsCleanedPrice::create([
				'location_codes'=> $v->l_location_codes,
				'item_codes'=> $v->item_codes,
				'zip_codes'=> $v->zip_codes,
				'product'=> $v->product,
				'price'=> $v->price,
				'currency_code'=> $v->currency_code,
				'amount'=> NULL,
				'units'=> $v->units??NULL,
				'website'=> $v->website,
				'store'=> $v->store,
				'store_address'=> NULL,
				'price_date' => date('Y-m-d')
			]);
		}

		$lowPrice = DB::select("SELECT temp.price FROM 
			(SELECT t.*,  @row_num :=@row_num + 1 AS row_num FROM gs_cleaned_prices t, 
			    (SELECT @row_num:=0) counter ORDER BY price) 
			temp WHERE temp.row_num = ROUND (.25* @row_num)");

		$mediumPrice = DB::select("SELECT temp.price FROM 
			(SELECT t.*,  @row_num :=@row_num + 1 AS row_num FROM gs_cleaned_prices t, 
			    (SELECT @row_num:=0) counter ORDER BY price) 
			temp WHERE temp.row_num = ROUND (.50* @row_num)");

		$highPrice = DB::select("SELECT temp.price FROM 
			(SELECT t.*,  @row_num :=@row_num + 1 AS row_num FROM gs_cleaned_prices t, 
			    (SELECT @row_num:=0) counter ORDER BY price) 
			temp WHERE temp.row_num = ROUND (.75* @row_num)");

		if ($lowPrice[0]->price) {
			$lowLevelData = GsCleanedPrice::where('price', '<=', $lowPrice[0]->price)->get();

			if ($lowLevelData->count() > 0) {
				foreach($lowLevelData as $k => $v) {
					GsComponentItemPricesLocation::create([
						'location_codes' => $v->location_codes,
						'item_codes' => $v->item_codes,
						'price_level' => 'low',
						'price' => $v->price,
						'currency' => $v->currency_code,
						'price_date'=> date('Y-m-d')
					]);
				}
			}
		}

		if ($mediumPrice[0]->price) {
			$mediumLevelData = GsCleanedPrice::where('price', '>', $lowPrice[0]->price)->where('price', '<=', $mediumPrice[0]->price)->get();
			
			if ($mediumLevelData->count() > 0) {
				foreach($mediumLevelData as $k => $v) {
					GsComponentItemPricesLocation::create([
						'location_codes' => $v->location_codes,
						'item_codes' => $v->item_codes,
						'price_level' => 'medium',
						'price' => $v->price,
						'currency' => $v->currency_code,
						'price_date'=> date('Y-m-d')
					]);
				}
			}
		}

		if ($highPrice[0]->price) {
			$highLevelData =  GsCleanedPrice::where('price', '>', $mediumPrice[0]->price)->where('price', '<=', $highPrice[0]->price)->get();

			if ($highLevelData->count() > 0) {
				foreach($highLevelData as $k => $v) {
					GsComponentItemPricesLocation::create([
						'location_codes' => $v->location_codes,
						'item_codes' => $v->item_codes,
						'price_level' => 'medium',
						'price' => $v->price,
						'currency' => $v->currency_code,
						'price_date'=> date('Y-m-d')
					]);
				}
			}
		}

		$fetchGSPriceLocationsData = DB::select("SELECT
				    SUBSTRING(location_codes, 1, 12),
				    AVG(price) as price,
				    price_level,
				    substring_index(group_concat(item_codes), ',', 1 ) as item_codes,
			    	substring_index(group_concat(currency), ',', 1 ) as currency_code,	
				    CONCAT(
				        LEFT(
				            location_codes,
				            LENGTH(location_codes) -4
				        ),
				        '0000'
				    ) AS masked_location_codes
				FROM
				    `gs_component_item_prices_locations`
				GROUP BY
				    SUBSTRING(location_codes, 1, 12),
				    price_level,
				    CONCAT(
				        LEFT(
				            location_codes,
				            LENGTH(location_codes) -4
				        ),
				        '0000'
				    )
				-- LIMIT 50		
		");

		if ($fetchGSPriceLocationsData) {
			foreach($fetchGSPriceLocationsData as $k => $v) {
				GsComponentItemPricesCity::create([
					'location_codes' => $v->masked_location_codes,
					'item_codes' => $v->item_codes,
					'price_level' => $v->price_level,
					'price' => $v->price,
					'currency' => $v->currency_code,
					'price_date'=> date('Y-m-d')
				]);
			}
		}


		//STEP 4
		$stepThreeData = DB::select("
			SELECT
			    SUBSTRING(location_codes, 1, 7),
			    AVG(price) as price,
			    price_level,
			    substring_index(group_concat(item_codes), ',', 1 ) as item_codes,
			   	substring_index(group_concat(currency), ',', 1 ) as currency_code,	
			    CONCAT(
			        LEFT(
			            location_codes,
			            LENGTH(location_codes) -13
			        ),
			        '000-0000-0000'
			    ) AS masked_location_codes
			FROM
			    `gs_component_item_prices_cities`
			GROUP BY
			    SUBSTRING(location_codes, 1, 7),
			    price_level,
			    CONCAT(
			        LEFT(
			            location_codes,
			            LENGTH(location_codes) -13
			        ),
			        '000-0000-0000'
			    )
			-- LIMIT 50;
		");

		if ($stepThreeData) {
			foreach($stepThreeData as $k => $v) {
				GsComponentItemPricesCountry::create([
					'location_codes' => $v->masked_location_codes,
					'item_codes' => $v->item_codes,
					'price_level' => $v->price_level,
					'price' => $v->price,
					'currency' => $v->currency_code,
					'price_date'=> date('Y-m-d H:i:s')
				]);
			}
		}


		//Step5
		$stepFourData = DB::select("SELECT
			    *
			FROM
			    (
			    SELECT
			        location_codes,
			        AVG(0.90 * price) AS city_avg,
			        price_level,
			        item_codes,
			        substring_index(group_concat(currency), ',', 1 ) as currency_code_city
			    FROM
			        `gs_component_item_prices_cities`
			    GROUP BY
			        location_codes,
			        item_codes,
			        price_level
			) AS city
			INNER JOIN(
			    SELECT
			        location_codes,
			        AVG(0.10 * price) AS country_avg_10,
			        price_level,
			        item_codes,
			        substring_index(group_concat(currency), ',', 1 ) as currency_code
			    FROM
			        `gs_component_item_prices_cities`
			    GROUP BY
			        location_codes,
			        item_codes,
			        price_level
			) AS country
			ON
			    city.item_codes = country.item_codes AND city.price_level = country.price_level");

		if ($stepFourData) {
			foreach($stepFourData as $k => $v) {
				GsComponentItemPricesAdjustedCity::create([
					'location_codes' => $v->location_codes,
					'item_codes' => $v->item_codes,
					'price_level' => $v->price_level,
					'price' => $v->city_avg + $v->country_avg_10,
					'currency' => $v->currency_code_city,
					'price_date'=> date('Y-m-d H:i:s')
				]);
			}
		}

		return redirect('admin/run-script-view')->with('status', 'Script Executed Successfully.');        
	}
}