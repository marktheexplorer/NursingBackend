<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Qualification;
<<<<<<< HEAD
use Image; 
use Validator;

class QualificationController extends Controller{
    /**
=======
use Validator;

class QualificationController extends Controller
{
     /**
>>>>>>> 82702cf8df23738becbdcb269d02afef18499e15
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function index(){
        $qualifications = Qualification::get();
        return view('qualification.index' , compact('qualifications'));   
=======
    public function index()
    {
        $qualifications = Qualification::get();
        return view('qualifications.index' , compact('qualifications'));   
>>>>>>> 82702cf8df23738becbdcb269d02afef18499e15
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function create(){
        return view('qualification.create');
=======
    public function create()
    {
        return view('qualifications.create');
>>>>>>> 82702cf8df23738becbdcb269d02afef18499e15
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function store(Request $request){ 
        $input = $request->input();
        $validator = validator::make($input,[
            'title' => 'required|string|max:60',
            'description' => 'required|string',
=======
    public function store(Request $request)
    { 
        $input = $request->input();
        $validator = validator::make($input,[
            'name' => 'required|string|max:60',
>>>>>>> 82702cf8df23738becbdcb269d02afef18499e15
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

<<<<<<< HEAD
        $input['title'] = ucfirst($input['title']);
        $input['description'] = $input['description'];
        $service = Qualification::create($input);

        flash()->success('New Qualification added successfully');
        return redirect()->route('qualification.index');
=======
                $input['name'] = $input['name'];
                $service = Qualification::create($input);

                flash()->success('New Qualification added successfully');
                return redirect()->route('qualifications.index');
>>>>>>> 82702cf8df23738becbdcb269d02afef18499e15
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function show($id){
        $qualification = Qualification::findOrFail($id);
        return view('qualification.view', compact('qualification'));
=======
    public function show($id)
    {
        $qualification = Qualification::findOrFail($id);
        return view('qualifications.view', compact('qualification'));
>>>>>>> 82702cf8df23738becbdcb269d02afef18499e15
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function edit($id){
        $qualification = Qualification::findOrFail($id);
        return view('qualification.edit', compact('qualification'));
=======
    public function edit($id)
    {
        $qualification = Qualification::findOrFail($id);
        return view('qualifications.edit', compact('qualification'));
>>>>>>> 82702cf8df23738becbdcb269d02afef18499e15
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function update(Request $request, $id){
        $input = $request->input();
        $validator = validator::make($input,[
            'title' => 'required|string|max:60',
            'description' => 'required|string'
=======
    public function update(Request $request, $id)
    {
        $input = $request->input();
        $validator = validator::make($input,[
            'name' => 'required|string|max:60'
>>>>>>> 82702cf8df23738becbdcb269d02afef18499e15
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

<<<<<<< HEAD
        $qualification = Qualification::findOrFail($id);
        $qualification->title = ucfirst($input['title']);
        $qualification->description = $input['description'];
        $qualification->save();

        flash()->success('Qualification updated successfully');
        return redirect()->route('qualification.index');
=======
                $qualification = Qualification::findOrFail($id);
                $qualification->name = $input['name'];
                $qualification->save();

                flash()->success('Qualification updated successfully');
                return redirect()->route('qualifications.index');
>>>>>>> 82702cf8df23738becbdcb269d02afef18499e15
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function destroy($id){
         $service = Qualification::findOrFail($id);
        if ($service->delete()) {
            $response = array(
                'status' => 'success',
                'message' => 'Qualification deleted successfully',
=======
    public function destroy($id)
    {
         $service = Service::findOrFail($id);
        if ($service->delete()) {
            $response = array(
                'status' => 'success',
                'message' => 'Service deleted successfully',
>>>>>>> 82702cf8df23738becbdcb269d02afef18499e15
            );
        } else {
            $response = array(
                'status' => 'error',
<<<<<<< HEAD
                'message' => 'Qualification can not be deleted.Please try again',
=======
                'message' => 'Service can not be deleted.Please try again',
>>>>>>> 82702cf8df23738becbdcb269d02afef18499e15
            );
        }
        return json_encode($response);
    }
<<<<<<< HEAD
}
=======

    public function block($id){
        $qualification = Qualification::find($id);
        $qualification->is_blocked = !$qualification->is_blocked;
        $qualification->save();
       
        if ($qualification->is_blocked)
            flash()->success("Qualification blocked successfully."); 
        else 
            flash()->success("Qualification Unblocked successfully."); 

        return redirect()->route('qualifications.index');  
    }
}
>>>>>>> 82702cf8df23738becbdcb269d02afef18499e15
