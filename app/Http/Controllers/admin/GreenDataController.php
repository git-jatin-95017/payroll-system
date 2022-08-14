<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExchangeRate;
use App\Models\LocationCode;
use App\Models\NationalSample;
use App\Models\GsCleanedPrice;
use App\Models\GsComponentItemPricesLocation;
use App\Models\GsComponentItemPricesCity;
use App\Models\GsComponentItemPricesCountry;
use App\Models\GsComponentItemPricesAdjustedCity;
use App\Models\GsFinalItemPrice;
use App\Models\GsItemBudget;
use App\Models\GsCityBudget;
use App\Models\GsQuantitySample;
use App\Models\HousingFinalPrice;
use App\Models\HousingFinalPriceCountry;
use App\Models\HousingFinalRentalPrice;
use App\Models\HousingHomeIndicesPrice;
use App\Models\HousingRentalIndicesPrice;
use App\Models\HousingPropertyTaxIndicesPrice;

use Illuminate\Support\Facades\DB;


class GreenDataController extends Controller
{
	public function index() {
		return view('admin.green-tables.index');
	}

	public function runScript(Request $request) {
		
		$request->validate([
	        'start_date' => 'required|date',
    		'end_date' => 'required|date|after_or_equal:start_date',
	    ]);

		ini_set('max_execution_time', '300');
		
		$startDate = $request->start_date;
		
		$endDate = $request->end_date;

		//STEP 1
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
				nation.store,
				nation.price_date
			FROM `national_samples` as nation
			INNER JOIN location_codes as location ON SUBSTR(nation.location_codes, 1, 3) = SUBSTR(location.location_codes, 1, 3) WHERE DATE(nation.price_date) BETWEEN '{$startDate}' AND '{$endDate}'
			) as first ON first.l_location_codes = stax.location_codes and first.item_codes = stax.item_codes WHERE DATE(stax.price_date) BETWEEN '{$startDate}' AND '{$endDate}'
		");

		foreach($result as $k => $v) {
			GsCleanedPrice::create([
				'location_codes'=> $v->l_location_codes,
				'item_codes'=> $v->item_codes,
				'zip_codes'=> $v->zip_codes,
				'product'=> $v->product,
				'price'=> $v->amount_with_tax,
				'currency_code'=> $v->currency_code,
				'amount'=> NULL,
				'units'=> $v->units??NULL,
				'website'=> $v->website,
				'store'=> $v->store,
				'store_address'=> NULL,
				'price_date' => !empty($v->price_date) ? $v->price_date : date('Y-m-d')
			]);
		}

		//STEP 1
		$lowPrice = DB::select("SELECT temp.price FROM 
			(SELECT t.*,  @row_num :=@row_num + 1 AS row_num FROM gs_cleaned_prices t, 
			    (SELECT @row_num:=0) counter WHERE DATE(t.price_date) BETWEEN '{$startDate}' AND '{$endDate}' ORDER BY price) 
			temp WHERE temp.row_num = ROUND (.25* @row_num)");

		$mediumPrice = DB::select("SELECT temp.price FROM 
			(SELECT t.*,  @row_num :=@row_num + 1 AS row_num FROM gs_cleaned_prices t, 
			    (SELECT @row_num:=0) counter WHERE DATE(t.price_date) BETWEEN '{$startDate}' AND '{$endDate}' ORDER BY price) 
			temp WHERE temp.row_num = ROUND (.50* @row_num)");

		$highPrice = DB::select("SELECT temp.price FROM 
			(SELECT t.*,  @row_num :=@row_num + 1 AS row_num FROM gs_cleaned_prices t, 
			    (SELECT @row_num:=0) counter WHERE DATE(t.price_date) BETWEEN '{$startDate}' AND '{$endDate}' ORDER BY price) 
			temp WHERE temp.row_num = ROUND (.75* @row_num)");

		if (!empty($lowPrice[0]->price)) {
			$lowLevelData = GsCleanedPrice::where('price', '<=', $lowPrice[0]->price)->get();

			if ($lowLevelData->count() > 0) {
				foreach($lowLevelData as $k => $v) {
					GsComponentItemPricesLocation::create([
						'location_codes' => $v->location_codes,
						'item_codes' => $v->item_codes,
						'price_level' => 'Low',
						'price' => $v->price,
						'currency' => $v->currency_code,
						'price_date'=> !empty($v->price_date) ? $v->price_date : date('Y-m-d')
					]);
				}
			}
		}

		if (!empty($mediumPrice[0]->price) && !empty($lowPrice[0]->price)) {
			$mediumLevelData = GsCleanedPrice::where('price', '>', $lowPrice[0]->price)->where('price', '<=', $mediumPrice[0]->price)->get();
			
			if ($mediumLevelData->count() > 0) {
				foreach($mediumLevelData as $k => $v) {
					GsComponentItemPricesLocation::create([
						'location_codes' => $v->location_codes,
						'item_codes' => $v->item_codes,
						'price_level' => 'Medium',
						'price' => $v->price,
						'currency' => $v->currency_code,
						'price_date'=> !empty($v->price_date) ? $v->price_date : date('Y-m-d')
					]);
				}
			}
		}

		if (!empty($highPrice[0]->price) && !empty($mediumPrice[0]->price)) {
			$highLevelData =  GsCleanedPrice::where('price', '>', $mediumPrice[0]->price)->where('price', '<=', $highPrice[0]->price)->get();

			if ($highLevelData->count() > 0) {
				foreach($highLevelData as $k => $v) {
					GsComponentItemPricesLocation::create([
						'location_codes' => $v->location_codes,
						'item_codes' => $v->item_codes,
						'price_level' => 'High',
						'price' => $v->price,
						'currency' => $v->currency_code,
						'price_date'=> !empty($v->price_date) ? $v->price_date : date('Y-m-d')
					]);
				}
			}
		}

		//STEP 3
		$fetchGSPriceLocationsData = DB::select("SELECT
				    SUBSTRING(location_codes, 1, 12),
				    AVG(price) as price,
				    price_level,
				    price_date,
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
				WHERE DATE(gs_component_item_prices_locations.price_date) BETWEEN '{$startDate}' AND '{$endDate}'
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
					'price_date'=> !empty($v->price_date) ? $v->price_date : date('Y-m-d')
				]);
			}
		}


		//STEP 4
		$stepThreeData = DB::select("
			SELECT
			    SUBSTRING(location_codes, 1, 7),
			    AVG(price) as price,
			    price_level,
			    price_date,
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
			WHERE DATE(gs_component_item_prices_cities.price_date) BETWEEN '{$startDate}' AND '{$endDate}'
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
					'price_date'=> !empty($v->price_date) ? $v->price_date : date('Y-m-d')
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
			    WHERE DATE(gs_component_item_prices_cities.price_date) BETWEEN '{$startDate}' AND '{$endDate}'
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
			    WHERE DATE(gs_component_item_prices_cities.price_date) BETWEEN '{$startDate}' AND '{$endDate}'
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
					'price_date'=> !empty($v->price_date) ? $v->price_date : date('Y-m-d')
				]);
			}
		}

		//STEP 6
		$stepFiveData = DB::select("
			SELECT
			    location_codes,
			    AVG(price) as price,
			    price_level,
			    price_date,
			    SUBSTRING(item_codes, 1, 7) AS master_item_codes,
			    substring_index(group_concat(currency), ',', 1 ) as currency_code
			FROM
			    `gs_component_item_prices_adjusted_cities`
			WHERE DATE(gs_component_item_prices_adjusted_cities.price_date) BETWEEN '{$startDate}' AND '{$endDate}'
			GROUP BY
			    location_codes,
			    SUBSTRING(item_codes, 1, 7),
			    price_level
		");

		if ($stepFiveData) {
			foreach($stepFiveData as $k => $v) {
				GsFinalItemPrice::create([
					'location_codes' => $v->location_codes,
					'master_item_codes' => $v->master_item_codes . '-0000',
					'price_level' => $v->price_level,
					'price' => $v->price,
					'currency' => $v->currency_code,
					'price_date'=> !empty($v->price_date) ? $v->price_date : date('Y-m-d')
				]);
			}
		}

		//STEP 7
		$stepSixData = DB::select("
			SELECT
			    final_prices.location_codes,
			    final_prices.master_item_codes,
			    price_level,
			    (
			        final_prices.price * gs_quantity.quantities
			    ) AS budget,
			    final_prices.price_date,
			    final_prices.currency as currency_code
			FROM
			    `gs_final_item_prices` AS final_prices
			INNER JOIN gs_quantity_samples AS gs_quantity
			ON
			    SUBSTRING(
			        final_prices.location_codes,
			        1,
			        12
			    ) = SUBSTRING(
			        gs_quantity.location_codes,
			        1,
			        12
			    ) 
			AND final_prices.master_item_codes = SUBSTRING(gs_quantity.item_codes, 1, 7)
			WHERE DATE(final_prices.price_date) BETWEEN '{$startDate}' AND '{$endDate}'		
		");

		if ($stepSixData) {
			foreach($stepSixData as $k => $v) {
				GsItemBudget::create([
					'location_codes' => $v->location_codes,
					'master_item_codes' => $v->master_item_codes,
					'price_level' => $v->price_level,
					'budget' => $v->budget,
					'currency' => $v->currency_code,
					'price_date'=> !empty($v->price_date) ? $v->price_date : date('Y-m-d')
				]);
			}
		}

		//STEP 8
		$stepSevenData = DB::select("
			SELECT
			    location_codes,
			    price_level,
			    price_date,
			     substring_index(group_concat(currency), ',', 1 ) as currency_code,
			    SUM(budget) AS sum_by_level
			FROM
			    `gs_item_budgets`
			WHERE DATE(gs_item_budgets.price_date) BETWEEN '{$startDate}' AND '{$endDate}'
			GROUP BY
			    location_codes, price_level;
		");

		if ($stepSevenData) {
			foreach($stepSevenData as $k => $v) {
				GsCityBudget::create([
					'location_codes' => $v->location_codes,
					'price_level' => $v->price_level,
					'budget' => $v->sum_by_level,
					'currency' => $v->currency_code,
					'price_date'=> !empty($v->price_date) ? $v->price_date : date('Y-m-d')
				]);
			}
		}

		//Housing Data Insertion Script

		$first = DB::select("
			INSERT INTO housing_final_prices (price_level,location_codes,housing_codes,price_type,house_type,bedrooms,price,currency,price_date)
			SELECT filtered.*
			FROM (
			    SELECT (CASE
			        WHEN sorted.row_num < ROUND (.15* @row_num) THEN 'lowest'
			        WHEN sorted.row_num > ROUND (.15* @row_num) AND sorted.row_num < ROUND (.25* @row_num) THEN 'low'
			        WHEN sorted.row_num > ROUND (.25* @row_num) AND sorted.row_num < ROUND (.50* @row_num) THEN 'median'
			        WHEN sorted.row_num > ROUND (.50* @row_num) AND sorted.row_num < ROUND (.75* @row_num) THEN 'high'
			        WHEN sorted.row_num > ROUND (.75* @row_num) AND sorted.row_num < ROUND (.85* @row_num) THEN 'Highest'
			        ELSE 'Highest'
			    END
			    ) as price_level,
			    location_codes,housing_codes,price_type,house_type,bedrooms,price,currency,price_date
			    FROM (
			            SELECT t.*,  @row_num :=@row_num + 1 AS row_num 
			            FROM housing_samples t, (SELECT @row_num:=0) counter
			            WHERE DATE(t.price_date) BETWEEN '{$startDate}' AND '{$endDate}'
			            ORDER BY price
			    ) as sorted
			    order by sorted.price DESC
			) as filtered
		");


		$second = DB::select("
			INSERT INTO housing_final_price_countries (price, price_level, location_codes, housing_codes, price_type, house_type, bedrooms, currency, price_date)
			SELECT cte1.level_avg_price, cte1.price_level ,
			cte2.location_codes, cte2.housing_codes, cte2.price_type, cte2.house_type, cte2.bedrooms, cte2.currency,  cte2.price_date
			FROM 
			(
			        SELECT SUBSTRING(location_codes,1,3) AS location_codes, price_level, AVG(price) as level_avg_price
			        FROM housing_final_prices
			        WHERE DATE(housing_final_prices.price_date) BETWEEN '{$startDate}' AND '{$endDate}'
			        GROUP BY SUBSTRING(location_codes,1,3), price_level
			) cte1
			INNER JOIN (
			        SELECT * FROM housing_final_prices WHERE DATE(housing_final_prices.price_date) BETWEEN '{$startDate}' AND '{$endDate}'
			) cte2 
			ON cte1.price_level = cte2.price_level AND cte1.location_codes = SUBSTRING(cte2.location_codes,1,3)
		");

		$three = DB::select("
			INSERT INTO housing_final_rental_prices (price, price_level, location_codes, housing_codes, price_type, house_type, bedrooms, currency, price_date)
			SELECT * FROM (
			        SELECT price, price_level, location_codes, housing_codes, price_type, house_type, bedrooms, currency, price_date 
			FROM housing_final_prices 
			WHERE SUBSTRING(housing_codes, 1,3)='002' AND DATE(housing_final_prices.price_date) BETWEEN '{$startDate}' AND '{$endDate}'
			) AS cte1
		");


		$four = DB::select("
			INSERT INTO housing_home_indices_prices(location_codes, price)
				SELECT
				    location_codes,
				    indices
				FROM
				    (
				    SELECT
				        *,
				        (m_avg / c_avg) * 100 AS indices
				    FROM
				        (
				        SELECT
				            SUM(
				                (housing_count / metro_count) * avg_item_price
				            ) AS m_avg,
				            metro_location_codes
				        FROM
				            (
				            SELECT
				                housing_codes,
				                COUNT(housing_codes) AS housing_count,
				                SUBSTRING(location_codes, 1, 12) AS housing_location_codes,
				                ROUND(AVG(price)) AS avg_item_price
				            FROM
				                housing_final_prices
				            WHERE
				                SUBSTRING(housing_codes, 1, 3) = '002'
				            GROUP BY
				                SUBSTRING(location_codes, 1, 12),
				                housing_codes
				        ) AS ct1
				    INNER JOIN(
				        SELECT COUNT(*) AS metro_count,
				            SUBSTRING(location_codes, 1, 12) AS metro_location_codes
				        FROM
				            housing_final_prices
				        WHERE
				            SUBSTRING(housing_codes, 1, 3) = '002'
				        GROUP BY
				            SUBSTRING(location_codes, 1, 12)
				    ) AS ct2
				ON
				    ct1.housing_location_codes = ct2.metro_location_codes
				GROUP BY
				    metro_location_codes
				    ) AS metro_weight_avg
				INNER JOIN(
				    SELECT SUM(
				            country_avg_item_price * housing_weight
				        ) AS c_avg,
				        country_code
				    FROM
				        (
				        SELECT
				            housing_codes,
				            AVG(price) AS country_avg_item_price,
				            SUBSTRING(location_codes, 1, 3) AS country_code
				        FROM
				            housing_final_prices
				        WHERE
				            SUBSTRING(housing_codes, 1, 3) = '002'
				        GROUP BY
				            SUBSTRING(location_codes, 1, 3),
				            housing_codes
				    ) AS country_avg
				INNER JOIN(
				    SELECT housing_codes,
				        (housing_count / metro_count) AS housing_weight
				    FROM
				        (
				        SELECT
				            housing_codes,
				            COUNT(housing_codes) AS housing_count,
				            SUBSTRING(location_codes, 1, 12) AS housing_location_codes,
				            ROUND(AVG(price)) AS avg_item_price
				        FROM
				            housing_final_prices
				        WHERE
				            SUBSTRING(housing_codes, 1, 3) = '002'
				        GROUP BY
				            SUBSTRING(location_codes, 1, 12),
				            housing_codes
				    ) AS ct1
				INNER JOIN(
				    SELECT COUNT(*) AS metro_count,
				        SUBSTRING(location_codes, 1, 12) AS metro_location_codes
				    FROM
				        housing_final_prices
				    WHERE
				        SUBSTRING(housing_codes, 1, 3) = '002'
				    GROUP BY
				        SUBSTRING(location_codes, 1, 12)
				) AS ct2
				ON
				    ct1.housing_location_codes = ct2.metro_location_codes
				) housing_weight
				ON
				    country_avg.housing_codes = housing_weight.housing_codes
				GROUP BY
				    country_code
				) AS country_weight_avg
				ON
				    SUBSTRING(
				        metro_weight_avg.metro_location_codes,
				        1,
				        3
				    ) = country_weight_avg.country_code
				) AS price_indicies
				INNER JOIN(
				    SELECT location_codes
				    FROM
				        housing_final_prices
				) AS all_location_codes
				ON
				    SUBSTRING(
				        all_location_codes.location_codes,
				        1,
				        12
				    ) = price_indicies.metro_location_codes
		");

		return redirect('admin/run-script-view')->with('status', 'Script Executed Successfully.');        
	}


	public function store(Request $request) {
		
		 $validatedData = $request->validate([
           'flush_table' => 'required'
        ], ['flush_table.required' => 'Please select atleast one table to delete.'], []);

		$tableId = $request->flush_table;

		if (in_array(1, $tableId)) {
			GsCleanedPrice::truncate();
		}

		if (in_array(2, $tableId)) {
			GsComponentItemPricesLocation::truncate();
		}

		if (in_array(3, $tableId)) {
			GsComponentItemPricesCity::truncate();
		}

		if (in_array(4, $tableId)) {
			GsComponentItemPricesCountry::truncate();
		}

		if (in_array(5, $tableId)) {
			GsComponentItemPricesAdjustedCity::truncate();
		}

		if (in_array(6, $tableId)) {
			GsFinalItemPrice::truncate();
		}

		if (in_array(7, $tableId)) {
			GsQuantitySample::truncate();
		}

		if (in_array(8, $tableId)) {
			GsItemBudget::truncate();
		}

		if (in_array(9, $tableId)) {
			GsCityBudget::truncate();
		}

		//
		if (in_array(10, $tableId)) {
			HousingFinalPrice::truncate();
		}

		if (in_array(11, $tableId)) {
			HousingFinalPriceCountry::truncate();
		}

		if (in_array(12, $tableId)) {
			HousingFinalRentalPrice::truncate();
		}
		
		if (in_array(13, $tableId)) {
			HousingHomeIndicesPrice::truncate();
		}
		
		if (in_array(14, $tableId)) {
			HousingRentalIndicesPrice::truncate();
		}

		if (in_array(15, $tableId)) {
			HousingPropertyTaxIndicesPrice::truncate();
		}

		return redirect('admin/run-script-view')->with('status', 'Data deleted Successfully.');        
	}

	public function exchangeRate(Request $request) {
		$request->validate([
	        'start_date' => 'required|date',
    		'end_date' => 'required|date|after_or_equal:start_date',
	    ]);
		
		$startDate = $request->start_date;
		
		$endDate = $request->end_date;

		// set API Endpoint and access key (and any options of your choice)

		$endpoint = 'live';

		$access_key = 'df990ab0e9fc0c0468d72082bb60dde7';

		$url = 'https://api.currencylayer.com/'.$endpoint.'?access_key='.$access_key.'& currencies = USD,GBP,EUR & start_date = '.$startDate.' & end_date = '. $endDate;

		$result = file_get_contents($url);

		$exchangeRates = json_decode($result, true);

		if (count($exchangeRates['quotes']) > 0) {
			ExchangeRate::truncate();
			foreach($exchangeRates['quotes'] as $key => $value) {
				$currency = str_replace('USD', '', $key);
				ExchangeRate::create([
					'currency' => $currency,
					'rate' =>$value,
					'date' => date('Y-m-d H:i:s')
				]);
			}
		}

		return redirect('admin/run-script-view')->with('status', 'Exhange rate table data inserted successfully.');  
	}
}