<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Diagnose;
use Validator;

class DiagnoseController extends Controller
{
    public function index(){
    	$diagnosis = Diagnose::get();
    	return view('diagnose.index' , compact('diagnosis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('diagnose.create');
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
        $validator = validator::make($input,[
            'title' => 'required|string|max:60'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

                $input['title'] = $input['title'];
                $diagnose = Diagnose::create($input);

                flash()->success('New Diagnose added successfully');
                return redirect()->route('diagnosis.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $diagnose = Diagnose::findOrFail($id);
        return view('diagnose.view', compact('diagnose'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $diagnose = Diagnose::findOrFail($id);
        return view('diagnose.edit', compact('diagnose'));
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
            'title' => 'required|string|max:60'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $diagnose = Diagnose::findOrFail($id);
        $diagnose->title = $input['title'];
        $diagnose->save();

        flash()->success('Diagnose updated successfully');
        return redirect()->route('diagnosis.index');
    }

    public function block($id){
        $diagnose = Diagnose::find($id);
        $diagnose->is_blocked = !$diagnose->is_blocked;
        $diagnose->save();
       
        if ($diagnose->is_blocked)
            flash()->success("Diagnose blocked successfully."); 
        else 
            flash()->success("Diagnose Unblocked successfully."); 

        return redirect()->route('diagnosis.index');  
    }
}
