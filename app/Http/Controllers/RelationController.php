<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Relation;
use Validator;

class RelationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $relations = Relation::get();
        return view('relations.index' , compact('relations'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('relations.create');
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
                'title' => 'required|string|max:60',
            ],
            [
                'title.required' => 'The title field is required.',
                'title.max' => 'The title may not be greater than 60 characters.',
            ]
         );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $input['title'] = $input['title'];
        $relation = Relation::create($input);

        flash()->success('New Relation added successfully');
        return redirect()->route('relations.index');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $relation = Relation::findOrFail($id);
        return view('relations.edit', compact('relation'));
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
        ],
            [
                'title.required' => 'The title field is required.',
                'title.max' => 'The title may not be greater than 60 characters.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

                $relation = Relation::findOrFail($id);
                $relation->title = $input['title'];
                $relation->save();

                flash()->success('Relation updated successfully');
                return redirect()->route('relations.index');
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
