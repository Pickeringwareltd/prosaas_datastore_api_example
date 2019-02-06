<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->post('/create/card', 'CardController@createCard');

$router->get('/get/tables', 'CardController@getTables');
$router->get('/get/table/{table_name}/fields', 'CardController@getTableColumns');
$router->delete('/delete/table/{table_name}', 'CardController@removeTable');

$router->get('/get/{table_name}/row/{id}', 'InstanceController@getInstanceFromTable');
$router->get('/get/{table_name}/all', 'InstanceController@getAllInstancesFromTable');

$router->post('/add/{table_name}/row', 'InstanceController@addInstanceToTable');
$router->post('/update/{table_name}/row/{id}', 'InstanceController@updateInstanceInTable');
$router->delete('/delete/{table_name}/row/{id}', 'InstanceController@deleteInstanceFromTable');

$router->get('/get/{table_name}/row/{id}/children', 'InstanceController@getChildInstancesFromTable');



