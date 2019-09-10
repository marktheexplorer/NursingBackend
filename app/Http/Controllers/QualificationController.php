<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Qualification;
use Validator;

class QualificationController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $qualifications = Qualification::get();
        return view('qualifications.index' , compact('qualifications'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('qualifications.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $input = $request->input();

        $attributes = ['name' => 'Title'];
        $validator = validator::make($input,
            [
                'name' => 'required|string|max:60',
            ],
            [
                'name.required' => 'The title field is required.',
                'name.max' => 'The title may not be greater than 60 characters.',
            ]
         );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $input['name'] = $input['name'];
        $service = Qualification::create($input);

        flash()->success('New Discipline added successfully');
        return redirect()->route('qualifications.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $qualification = Qualification::findOrFail($id);
        return view('qualifications.view', compact('qualification'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $qualification = Qualification::findOrFail($id);
        return view('qualifications.edit', compact('qualification'));
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
        $input = $request->input();
        $validator = validator::make($input,[
            'name' => 'required|string|max:60'
        ],
            [
                'name.required' => 'The title field is required.',
                'name.max' => 'The title may not be greater than 60 characters.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

                $qualification = Qualification::findOrFail($id);
                $qualification->name = $input['name'];
                $qualification->save();

                flash()->success('Discipline updated successfully');
                return redirect()->route('qualifications.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $service = Service::findOrFail($id);
        if ($service->delete()) {
            $response = array(
                'status' => 'success',
                'message' => 'Discipline deleted successfully',
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Discipline can not be deleted.Please try again',
            );
        }
        return json_encode($response);
    }

    public function block($id){
        $qualification = Qualification::find($id);
        $qualification->is_blocked = !$qualification->is_blocked;
        $qualification->save();
       
        if ($qualification->is_blocked)
            flash()->success("Discipline blocked successfully."); 
        else 
            flash()->success("Discipline Unblocked successfully."); 

        return redirect()->route('qualifications.index');  
    }
}
