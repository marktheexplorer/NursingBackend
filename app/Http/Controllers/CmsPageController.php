<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CmsPage;
use Validator;

class CmsPageController extends Controller{
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
        $cms = CmsPage::orderBy('updated_at', 'desc')->get();
        return view('cms_pages.index', compact('cms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('cms_pages.create');
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
            'title' => 'required|string|max:200',
            'content' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $slug = $this->slugify($input['title']);
        $cms = new CmsPage();
        $cms->title = $input['title'];
        $cms->slug = $slug;
        $cms->content = $input['content'];
        $cms->save();

        flash()->success('New cms page added successfully');
        return redirect()->route('cms.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $cms = CmsPage::findOrFail($id);
        return view('cms_pages.view', compact('cms'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $cms = CmsPage::findOrFail($id);
        return view('cms_pages.edit', compact('cms'));
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
            'title' => 'required|string|max:200',
            'content' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $slug = $this->slugify($input['title']);

        $cms = CmsPage::findOrFail($id);
        $cms->title = $input['title'];
        $cms->slug = $slug;
        $cms->content = $input['content'];
        $cms->save();

        flash()->success("Cms page details updated successfully.");
        return redirect()->route('cms.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $cms = CmsPage::findOrFail($id);
        if ($cms->delete()) {
            $response = array(
                'status' => 'success',
                'message' => 'Cms Page deleted successfully',
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Cms page can not be deleted.Please try again',
            );
        }
        return json_encode($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view_cms($slug){
        $cms = CmsPage::where('slug', 'LIKE', '%' . $slug . '%')->first();
        return view('cms_view', compact('cms'));
    }

    public static function slugify($text){
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
        return 'n-a';
        }

        return $text;
    }
}