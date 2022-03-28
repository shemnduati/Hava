<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    public function tripDetails($tripId){
        $client = new Client();
        $url = 'https://hr.hava.bz/trips/recent.json';
        $res = $client->request('GET', $url);
        $response = $res->getBody();

        $trip = json_decode($response, true);

        $collection = collect($trip['trips']);

        $filtered = $collection->where('id', $tripId);
        $key = $filtered->keys();

        return $filtered[$key[0]];
    }
    public function search(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'required|string',
            'distance' => 'required',
            'time' => 'required'
        ]);

        $client = new Client();
        $res = $client->request('GET', 'https://hr.hava.bz/trips/recent.json');
        $response = $res->getBody();
        $keyword = $request->keyword;


        $trip = json_decode($response, true);

        $collection = collect($trip['trips']);

        if ($request->canceled) {
            $filtered = $collection->filter(function ($value) use ($keyword) {
                return (
                    strstr($value['dropoff_location'], $keyword) ||
                    strstr($value['dropoff_location'], ucfirst($keyword)) ||
                    strstr($value['pickup_location'], ucfirst($keyword)) ||
                    strstr($value['pickup_location'], $keyword) ||
                    strstr($value['driver_name'], ucfirst($keyword)) ||
                    strstr($value['driver_name'], $keyword) ||
                    strstr($value['car_make'], ucfirst($keyword)) ||
                    strstr($value['car_make'], $keyword) ||
                    strstr($value['car_model'], ucfirst($keyword)) ||
                    strstr($value['car_model'], $keyword) ||
                    strstr($value['car_number'], $keyword) ||
                    strstr($value['car_number'], strtoupper($keyword)) ||
                    strstr($value['type'], strtoupper($keyword)) ||
                    strstr($value['type'], $keyword)

                );
            });

            switch ($request->distance) {
                case 0:
                    if ($request->time == 0) {
                        return $filtered->toJson();
                    } elseif ($request->time == 1) {
                        $final = $filtered->where('duration', "<", 5);
                        return $final->toJson();
                    } elseif ($request->time == 2) {
                        $final = $filtered->whereBetween('duration', [5, 10]);
                        return $final->toJson();
                    } elseif ($request->time == 3) {
                        $final = $filtered->whereBetween('duration', [10, 20]);
                        return $final->toJson();
                    } elseif ($request->time == 4) {
                        $final = $filtered->where('duration', ">", 10);
                        return $final->toJson();
                    }
                    break;
                case 1:
                    if ($request->time == 0) {
                        $final = $filtered->where('distance', "<", 3);
                        return $final->toJson();
                    } elseif ($request->time == 1) {
                        $final = $filtered->where('duration', "<", 5)->where('distance', "<", 3);
                        return $final->toJson();
                    } elseif ($request->time == 2) {
                        $final = $filtered->whereBetween('duration', [5, 10])->where('distance', "<", 3);
                        return $final->toJson();
                    } elseif ($request->time == 3) {
                        $final = $filtered->whereBetween('duration', [10, 20])->where('distance', "<", 3);
                        return $final->toJson();
                    } elseif ($request->time == 4) {
                        $final = $filtered->where('duration', ">", 10)->where('distance', "<", 3);
                        return $final->toJson();
                    }
                    break;
                case 2:
                    if ($request->time == 0) {
                        $final = $filtered->whereBetween('distance', [3, 8]);
                        return $final->toJson();
                    } elseif ($request->time == 1) {
                        $final = $filtered->where('duration', "<", 5)->whereBetween('distance', [3, 8]);
                        return $final->toJson();
                    } elseif ($request->time == 2) {
                        $final = $filtered->whereBetween('duration', [5, 10])->whereBetween('distance', [3, 8]);
                        return $final->toJson();
                    } elseif ($request->time == 3) {
                        $final = $filtered->whereBetween('duration', [10, 20])->whereBetween('distance', [3, 8]);
                        return $final->toJson();
                    } elseif ($request->time == 4) {
                        $final = $filtered->where('duration', ">", 10)->whereBetween('distance', [3, 8]);
                        return $final->toJson();
                    }
                    break;
                case 3:
                    if ($request->time == 0) {
                        $final = $filtered->whereBetween('distance', [10, 20]);
                        return $final->toJson();
                    } elseif ($request->time == 1) {
                        $final = $filtered->where('duration', "<", 5)->whereBetween('distance', [10, 20]);
                        return $final->toJson();
                    } elseif ($request->time == 2) {
                        $final = $filtered->whereBetween('duration', [5, 10])->whereBetween('distance', [10, 20]);
                        return $final->toJson();
                    } elseif ($request->time == 3) {
                        $final = $filtered->whereBetween('duration', [10, 20])->whereBetween('distance', [10, 20]);
                        return $final->toJson();
                    } elseif ($request->time == 4) {
                        $final = $filtered->where('duration', ">", 10)->whereBetween('distance', [10, 20]);
                        return $final->toJson();
                    }
                    break;
                case 4:
                    if ($request->time == 0) {
                        $final = $filtered->where('distance', ">", 15);
                        return $final->toJson();
                    } elseif ($request->time == 1) {
                        $final = $filtered->where('duration', "<", 5)->where('distance', ">", 15);
                        return $final->toJson();
                    } elseif ($request->time == 2) {
                        $final = $filtered->whereBetween('duration', [5, 10])->where('distance', ">", 15);
                        return $final->toJson();
                    } elseif ($request->time == 3) {
                        $final = $filtered->whereBetween('duration', [10, 20])->where('distance', ">", 15);
                        return $final->toJson();
                    } elseif ($request->time == 4) {
                        $final = $filtered->where('duration', ">", 10)->where('distance', ">", 15);
                        return $final->toJson();
                    }
                    break;
            }
        }
        if (!$request->canceled) {
            $canceled = $collection->where('status', "COMPLETED");
            $filtered = $canceled->filter(function ($value) use ($keyword) {
                return (
                    strstr($value['dropoff_location'], $keyword) ||
                    strstr($value['dropoff_location'], ucfirst($keyword)) ||
                    strstr($value['pickup_location'], ucfirst($keyword)) ||
                    strstr($value['pickup_location'], $keyword) ||
                    strstr($value['driver_name'], ucfirst($keyword)) ||
                    strstr($value['driver_name'], $keyword) ||
                    strstr($value['car_make'], ucfirst($keyword)) ||
                    strstr($value['car_make'], $keyword) ||
                    strstr($value['car_model'], ucfirst($keyword)) ||
                    strstr($value['car_model'], $keyword) ||
                    strstr($value['car_number'], $keyword) ||
                    strstr($value['car_number'], strtoupper($keyword))

                );
            });

            switch ($request->distance) {
                case 0:
                    if ($request->time == 0) {
                        return $filtered->toJson();
                    } elseif ($request->time == 1) {
                        $final = $filtered->where('duration', "<", 5);
                        return $final->toJson();
                    } elseif ($request->time == 2) {
                        $final = $filtered->whereBetween('duration', [5, 10]);
                        return $final->toJson();
                    } elseif ($request->time == 3) {
                        $final = $filtered->whereBetween('duration', [10, 20]);
                        return $final->toJson();
                    } elseif ($request->time == 4) {
                        $final = $filtered->where('duration', ">", 10);
                        return $final->toJson();
                    }
                    break;
                case 1:
                    if ($request->time == 0) {
                        $final = $filtered->where('distance', "<", 3);
                        return $final->toJson();
                    } elseif ($request->time == 1) {
                        $final = $filtered->where('duration', "<", 5)->where('distance', "<", 3);
                        return $final->toJson();
                    } elseif ($request->time == 2) {
                        $final = $filtered->whereBetween('duration', [5, 10])->where('distance', "<", 3);
                        return $final->toJson();
                    } elseif ($request->time == 3) {
                        $final = $filtered->whereBetween('duration', [10, 20])->where('distance', "<", 3);
                        return $final->toJson();
                    } elseif ($request->time == 4) {
                        $final = $filtered->where('duration', ">", 10)->where('distance', "<", 3);
                        return $final->toJson();
                    }
                    break;
                case 2:
                    if ($request->time == 0) {
                        $final = $filtered->whereBetween('distance', [3, 8]);
                        return $final->toJson();
                    } elseif ($request->time == 1) {
                        $final = $filtered->where('duration', "<", 5)->whereBetween('distance', [3, 8]);
                        return $final->toJson();
                    } elseif ($request->time == 2) {
                        $final = $filtered->whereBetween('duration', [5, 10])->whereBetween('distance', [3, 8]);
                        return $final->toJson();
                    } elseif ($request->time == 3) {
                        $final = $filtered->whereBetween('duration', [10, 20])->whereBetween('distance', [3, 8]);
                        return $final->toJson();
                    } elseif ($request->time == 4) {
                        $final = $filtered->where('duration', ">", 10)->whereBetween('distance', [3, 8]);
                        return $final->toJson();
                    }
                    break;
                case 3:
                    if ($request->time == 0) {
                        $final = $filtered->whereBetween('distance', [10, 20]);
                        return $final->toJson();
                    } elseif ($request->time == 1) {
                        $final = $filtered->where('duration', "<", 5)->whereBetween('distance', [10, 20]);
                        return $final->toJson();
                    } elseif ($request->time == 2) {
                        $final = $filtered->whereBetween('duration', [5, 10])->whereBetween('distance', [10, 20]);
                        return $final->toJson();
                    } elseif ($request->time == 3) {
                        $final = $filtered->whereBetween('duration', [10, 20])->whereBetween('distance', [10, 20]);
                        return $final->toJson();
                    } elseif ($request->time == 4) {
                        $final = $filtered->where('duration', ">", 10)->whereBetween('distance', [10, 20]);
                        return $final->toJson();
                    }
                    break;
                case 4:
                    if ($request->time == 0) {
                        $final = $filtered->where('distance', ">", 15);
                        return $final->toJson();
                    } elseif ($request->time == 1) {
                        $final = $filtered->where('duration', "<", 5)->where('distance', ">", 15);
                        return $final->toJson();
                    } elseif ($request->time == 2) {
                        $final = $filtered->whereBetween('duration', [5, 10])->where('distance', ">", 15);
                        return $final->toJson();
                    } elseif ($request->time == 3) {
                        $final = $filtered->whereBetween('duration', [10, 20])->where('distance', ">", 15);
                        return $final->toJson();
                    } elseif ($request->time == 4) {
                        $final = $filtered->where('duration', ">", 10)->where('distance', ">", 15);
                        return $final->toJson();
                    }
                    break;
                default:
                    return $filtered->toJson();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
