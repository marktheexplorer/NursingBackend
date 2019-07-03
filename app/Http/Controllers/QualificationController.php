<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Qualification;
use Image; 
use Validator;

class QualificationController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $qualifications = Qualification::get();
        return view('qualification.index' , compact('qualifications'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('qualification.create');
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
            'title' => 'required|string|max:60',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $input['title'] = ucfirst($input['title']);
        $input['description'] = $input['description'];
        $service = Qualification::create($input);

        flash()->success('New Qualification added successfully');
        return redirect()->route('qualification.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $qualification = Qualification::findOrFail($id);
        return view('qualification.view', compact('qualification'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $qualification = Qualification::findOrFail($id);
        return view('qualification.edit', compact('qualification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $input = $request->input();
        $validator = validator::make($input,[
            'title' => 'required|string|max:60',
            'description' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $qualification = Qualification::findOrFail($id);
        $qualification->title = ucfirst($input['title']);
        $qualification->description = $input['description'];
        $qualification->save();

        flash()->success('Qualification updated successfully');
        return redirect()->route('qualification.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
         $service = Qualification::findOrFail($id);
        if ($service->delete()) {
            $response = array(
                'status' => 'success',
                'message' => 'Qualification deleted successfully',
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Qualification can not be deleted.Please try again',
            );
        }
        return json_encode($response);
    }
}