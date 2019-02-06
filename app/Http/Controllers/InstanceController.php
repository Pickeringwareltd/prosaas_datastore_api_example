<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

use DB;

class InstanceController extends BaseController
{

    public function getInstanceFromTable($table_name, $id){
        $data = $this->getFromTable($table_name, $id);

        if(count($data) > 0){
            return response()->json($data, 200);
        } else {
            return response()->json(['message', 'Isntance doesnt exist'], 400);
        }
    }

    protected function getFromTable($table_name, $id)
    {
        if (Schema::hasTable($table_name)) {

            try {
                $result = \DB::table($table_name)->where('id', $id)->get();
                return $result;
            } catch (\Exception $e) {

                return $e->getMessage();

            }

        } else {
            return 'Table doesnt exist';
        }

    }

    public function getAllInstancesFromTable($table_name){
        $data = $this->getAllFromTable($table_name);

        if(count($data) > 0){
            return response()->json($data, 200);
        } else {
            return response()->json(['message', 'No instances exist'], 400);
        }
    }

    protected function getAllFromTable($table_name)
    {
        if (Schema::hasTable($table_name)) {

            try {
                $result = \DB::table($table_name)->get();
                return $result;
            } catch (\Exception $e) {

                return $e->getMessage();

            }

        } else {
            return 'Table doesnt exist';
        }

    }

    public function getChildInstancesFromTable($table_name, $id){
        $data = $this->getChildrenFromTable($table_name, $id);

        if(count($data) > 0){
            return response()->json($data, 200);
        } else {
            return response()->json(['message', 'No children exist'], 400);
        }
    }

    protected function getChildrenFromTable($table_name, $id)
    {
        if (Schema::hasTable($table_name)) {

            try {
                $result = \DB::table('linker')->where('parent_table_name', $table_name)->where('parent_id', $id)->get();
                return $result;
            } catch (\Exception $e) {
                return $e->getMessage();
            }

        } else {
            return 'Table doesnt exist';
        }

    }

    public function addInstanceToTable($table_name, Request $request)
    {
    	$table_data = $request->input('table_data', '');

		$result = $this->addToTable($table_name, $table_data);

		if($result === true){
			return response()->json(['message', 'Success!'], 200);
		} else {
			return response()->json(['message', $result], 400);
		}
    }

    protected function addToTable($table_name, $table_data)
    {
    	if (Schema::hasTable($table_name)) {

    		try {
	    		$result = \DB::table($table_name)->insert([
		    		$table_data
				]);

				return $result;
    		} catch (\Exception $e) {

	    		return $e->getMessage();

			}

		} else {
			return 'Table doesnt exist';
		}

    }

    public function updateInstanceInTable($table_name, $id, Request $request)
    {
    	$table_data = $request->input('table_data', '');

		$result = $this->updateInstance($table_name, $id, $table_data);

        if($result == $id){
            return response()->json(['message', 'Success!'], 200);
        } else {
            return response()->json(['message', $result], 400);
        }
    }

    protected function updateInstance($table_name, $id, $table_data)
    {
    	if (Schema::hasTable($table_name)) {

    		try {

	    		$result = \DB::table($table_name)->where('id', $id)->update( $table_data );
				return $result;

    		} catch (\Exception $e) {

	    		return $e->getMessage();

			}

		} else {
			return 'Table doesnt exist';
		}

    }

    public function deleteInstanceFromTable($table_name, $id){
        $result = $this->deleteInstance($table_name, $id);

        if($result == true){
            return response()->json(['message', 'Success!'], 200);
        } else if($result == 0){ 
            return response()->json(['message', 'Failed!'], 400);
        } else {
            return response()->json(['message', $result], 400);
        }
    }

    protected function deleteInstance($table_name, $id)
    {
        if (Schema::hasTable($table_name)) {

            try {

                $result = \DB::table($table_name)->where('id', $id)->delete();
                return $result;

            } catch (\Exception $e) {

                return $e->getMessage();

            }

        } else {
            return 'Table doesnt exist';
        }

    }
}
