<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use Image; 
use Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::get();
        return view('services.index' , compact('services'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('services.create');
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
            'title' => 'required|string|max:60',
            'description' => 'required|string',
            'service_image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($request->has('service_image') && ($request->file('service_image') != null)) {
                $image = $request->file('service_image');
                $input['service_image'] = time().'.'.$image->getClientOriginalExtension();   

                $destinationPath = config('image.service_image_path');
                $img = Image::make($image->getRealPath());
                $image->move($destinationPath, $input['service_image']);
            }

                $input['title'] = $input['title'];
                $input['description'] = $input['description'];
                $service = Service::create($input);

                flash()->success('New Service added successfully');
                return redirect()->route('services.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return view('services.view', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('services.edit', compact('service'));
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
            'title' => 'required|string|max:60',
            'description' => 'required|string',
            'service_image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($request->has('service_image') && ($request->file('service_image') != null)) {
                $image = $request->file('service_image');
                $input['service_image'] = time().'.'.$image->getClientOriginalExtension();   

                $destinationPath = config('image.service_image_path');
                $img = Image::make($image->getRealPath());
                $image->move($destinationPath, $input['service_image']);
            }

                $service = Service::findOrFail($id);
                $service->title = $input['title'];
                $service->description = $input['description'];
                $service->service_image = isset($input['service_image'])? $input['service_image'] : null;
                $service->save();

                flash()->success('Service updated successfully');
                return redirect()->route('services.index');
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
                'message' => 'Service deleted successfully',
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Service can not be deleted.Please try again',
            );
        }
        return json_encode($response);
    }

    public function block($id){
        $service = Service::find($id);
        $service->is_blocked = !$service->is_blocked;
        $service->save();
       
        if ($service->is_blocked)
            flash()->success("Service blocked successfully."); 
        else 
            flash()->success("Service Unblocked successfully."); 

        return redirect()->route('services.index');  
    }
}
