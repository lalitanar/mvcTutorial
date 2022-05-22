<?php

namespace App\Http\Controllers;

use App\Http\Resources\EastudentsResource; //Add this line
use App\Models\eastudents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; //Add this line
use Illuminate\Support\Facades\Log; //Add this line

class EastudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch Data
        $eastud = eastudents:: latest()->get();

        // Return Message and Data
        return response()->json(['EAStudents fetch sucessfully', EastudentsResource::collection($eastud)]);
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\eastudents  $eastudents
     * @return \Illuminate\Http\Response
     */
    public function show(eastudents $eastudents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\eastudents  $eastudents
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, eastudents $eastudents)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\eastudents  $eastudents
     * @return \Illuminate\Http\Response
     */
    public function destroy(eastudents $eastudents)
    {
        //
    }
}
