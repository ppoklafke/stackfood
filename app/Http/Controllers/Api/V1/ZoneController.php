<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Zone;
use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MatanYadaev\EloquentSpatial\Objects\Point;

class ZoneController extends Controller
{
    public function get_zones()
    {
        $zones= Zone::where('status',1)->get();
        foreach($zones as $zone){
            $area = json_decode($zone->coordinates[0]->toJson(),true);
            $zone['formated_coordinates']=Helpers::format_coordiantes($area['coordinates']);
        }
        return response()->json($zones, 200);
    }

    public function zonesCheck(Request $request){
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
            'zone_id' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $zone = Zone::where('id',$request->zone_id)->whereContains('coordinates', new Point($request->lat, $request->lng, POINT_SRID))->exists();

        return response()->json($zone, 200);

    }

}
