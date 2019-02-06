<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

use DB;

class CardController extends BaseController
{
    public function createCard(Request $request)
    {

    	$card_details = $request->input('card_details', '');
        $card_title = $card_details['card_title'];
        $card_fields = $card_details['card_fields'];

    	return $this->createTable($card_title, $card_fields);

    }

    /**
     * Create dynamic table along with dynamic fields
     *
     * @param       $table_name
     * @param array $fields
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createTable($table_name, $fields = [])
    {

        // check if table is not already exists
        if (!Schema::hasTable($table_name)) {
            Schema::connection('mysql')->create($table_name, function (Blueprint $table) use ($fields, $table_name) {
                $table->increments('id');
                if (count($fields) > 0) {
                    foreach ($fields as $field) {
                        $table->{$field['type']}($field['name']);
                    }
                }
                $table->timestamps();
            });

            return response()->json(['message' => 'Given table has been successfully created!'], 200);
        }

        return response()->json(['message' => 'Table already exists.'], 400);

    }

    public function getTableColumns($table_name)
	{

		$table = $table_name;
		$field_array = Schema::getColumnListing($table);

		$new_field = [];

		$fields = [];

		foreach($field_array as $field){
			$name = $field;
			$type = DB::connection('mysql')->getDoctrineColumn($table, $name)->getType()->getName();
			
			$new_field['name'] = $name;
			$new_field['type'] = $type;

			array_push($fields, collect($new_field));
		}

	    return response()->json(['fields' => $fields], 200);

	}

	public function getTables()
	{

		$tables = DB::select('SHOW TABLES');
	    return response()->json(['tables' => $tables], 200);

	}

    /**
     * To delete the tabel from the database 
     * 
     * @param $table_name
     *
     * @return bool
    */
    public function removeTable($table_name)
    {
    	try{

    		if (Schema::hasTable($table_name)) {
    			Schema::dropIfExists($table_name);
        		return response()->json(['message' => 'Success!'], 200);
        	} else {
        		return response()->json(['error' => 'Table doesnt exist'], 400);
        	}

    	} catch (\Exception $e) {

    		return response()->json(['error' =>  $e->getMessage()], 400);

		}
   
    }
}
