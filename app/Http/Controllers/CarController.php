<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\updateCarReq;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Return_;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $car = Car::all();
        return response()->json()([
            'status'=> 'success',
            'car'=>$car
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request)
    {
     try {
            DB::beginTransaction();
            $car = Car::create([
                'name'=> $request->name,
                'color'=> $request->color,
            ]);

            DB::commit();
            return response()->json([
                'status'=> 'success',
                'car'=> $car,
            ]);
        }
      catch (\Throwable $th) {
        DB::rollBack();

        Log::error($th);
        return response()->json([
            'status'=>'error',
        ], 500);
     }
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        return response()->json([
            'status'=>'success',
            'cars'=>$car,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateCarReq $request, Car $car)
    {
        $newData = [];
        if (isset($request->name)) {
            $newData['name'] = $request->name ;
        }
        if (isset($request->color)) {
            $newData['color'] = $request->color ;
        }
        $car -> update($newData);
        return response()-> json([
            'status'=>'success',
            'cars'=>$car,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        
        $car -> delete();
        return response()->json([
            'status'=>'success'
        ]);
    }
}
