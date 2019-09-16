<?php

namespace App\Http\Controllers;
use App\Countyareas;
use Illuminate\Http\Request;
use Validator;
use App\Rules\UniqueArea;

class CountyareaController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */

    public function index(){
        $county = Countyareas::where('area', '=', '0')->orderBy('county', 'asc')->get();
        return view('countyarea.index' , compact('county'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $input = $request->input();

        $validator = validator::make($input,[
            'county' => "required|string|max:60|min:4|unique:county_areas",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($input['countyid'] == 0){
            $temp['county'] = $input['county'];
            $temp['area'] = '0';
            $service = Countyareas::create($temp);
            flash()->success('New County added successfully');
        }else{
            $temp = Countyareas::findOrFail($input['countyid']);
            if(empty($temp)){
                flash()->success('Invalid Perameters');
            }else{
                $temp->county = $input['county'];
                $temp->save();
                flash()->success('County Edit successfully');
            }
        }
        return redirect()->route('county.index');
    }

    public function store_area(Request $request){
        $input = $request->input();
        $validator = validator::make($input,[
            'area' => 'required|string|max:60|min:4',
            'countyid' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $county = Countyareas::findOrFail($input['countyid']);
        if(empty($county)){
            flash()->success('Invalid County');
            return redirect()->route('county.index');
        }

        $countyarea = Countyareas::where('area', '=', $input['area'])->where('county', '=', $input['countyid'])->first();
        if(!empty($countyarea)){
            $validator->errors()->add('area', 'The area in this county has already been taken.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($input['areaid'] == 0){
            $temp['county'] = $county->id;
            $temp['area'] = $input['area'];
            $areasave = Countyareas::create($temp);
            flash()->success('County Area added successfully');
        }else{
            $temp = Countyareas::findOrFail($input['areaid']);
            if(empty($temp)){
                flash()->success('Invalid Perameters');
            }else{
                $temp->area = $input['area'];
                $temp->save();
                flash()->success('County Area Edit successfully');
            }
        }
        return redirect()->route('county.show', [$input['countyid']]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $county = Countyareas::findOrFail($id);
        if(empty($county)){
            flash()->success('Invalid County');
            return redirect()->route('county.index');
        }else{
            $countyareas = Countyareas::where('county', '=', $county->id)->where('area', '!=', '0')->orderBy('area', 'asc')->get();
            return view('countyarea.view', compact('county', 'countyareas'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    public function blocked($id){
        $county = Countyareas::findOrFail($id);
        if(empty($county)){
            flash()->success('Invalid County');
            return redirect()->route('county.index');
        }

        $county->is_blocked = !$county->is_blocked;
        $county->save();

        if ($county->is_blocked)
            flash()->success("County blocked successfully.");
        else
            flash()->success("County Unblocked successfully.");
        return redirect()->route('county.index');
    }


    public function delete_area($id){
        $county = Countyareas::findOrFail($id);
        if(empty($county)){
            flash()->success('Invalid County');
            return redirect()->route('county.index');
        }

        $county->is_area_blocked = !$county->is_area_blocked;
        $county->save();
        // dd($county);

        if ($county->is_area_blocked)
            flash()->success("Area Unblocked successfully.");
        else
            flash()->success("Area blocked successfully.");
        return redirect()->route('county.show', [$county->county]);
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
