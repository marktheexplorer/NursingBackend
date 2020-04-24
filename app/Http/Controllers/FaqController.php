<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Faq;
use Validator;
use DB;

class FaqController extends Controller{
    public function __construct(){ 
        $this->middleware('preventBackHistory');
        $this->middleware('auth'); 
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $faqs = Faq::orderBy('faq_order', 'ASC')->get();
        return view('faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('faqs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $input = $request->input();
        $validator =  Validator::make($input,[
            'question' => 'required|string|max:200',
            'answer' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $faq = new Faq();
        $faq->question = $input['question'];
        $faq->answer = $input['answer'];
        $faq->role_id = $input['role_id'];
        $faq_no = Faq::count();
        $faq->faq_order = $faq_no + 1;
        $faq->save();

        flash()->success('New faq added successfully');
        return redirect()->route('faqs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $faq = Faq::findOrFail($id);
        return view('faqs.view', compact('faq'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $faq = Faq::findOrFail($id);
        return view('faqs.edit', compact('faq'));
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
        $validator =  Validator::make($input,[
            'question' => 'required|string|max:200',
            'answer' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $faq = Faq::findOrFail($id);
        $faq->fill($input);
        $faq->save();

        flash()->success("Faq details updated successfully.");
        return redirect()->route('faqs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $faq = Faq::findOrFail($id);
        if ($faq->delete()) {
            $response = array(
                'status' => 'success',
                'message' => 'Faq deleted successfully',
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Faq can not be deleted.Please try again',
            );
        }
        return json_encode($response);
    }

    public function reorder(){
        $faqs = Faq::orderBy('faq_order', 'asc')->get();
        return view('faqs.reorder', compact('faqs'));
    }

    public function updateorder(Request $request){
        $input = $request->input();
        $temp = explode(",", $input['faqorders']);
        $counter = 1;
        foreach ($temp as $key => $value){
            DB::table('faqs')->where('id', $value)->update(['faq_order' => $counter]);
            $counter++;
        };

        flash()->success("Faqs order saved successfully.");
        return redirect()->route('faqs.reorder');
    }
}
