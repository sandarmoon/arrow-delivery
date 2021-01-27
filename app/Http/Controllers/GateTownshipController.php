<?php

namespace App\Http\Controllers;

use App\Township;
use App\City;
use App\SenderGate;
use Illuminate\Http\Request;

class GateTownshipController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gatetownships=Township::whereNull('sender_gate_id')->get();
        return view('township.index',compact('gatetownships'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $gates=SenderGate::all();
        // dd($gates);
        return view('township.gate.create',compact('gates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->name);
        $validator = $request->validate([
            // 'rcity'  => ['required'],
            'name' => ['required'],
            'gate'=>['required'],
            // 'city'=>['required']
        ]);

        // $city=explode('_', $request->city);
        //dd($city);
        if($validator){
            // $gate=" Gate";
            // $post=" Post Office";
            $township=new Township;
            // if($request->rcity==2){
            //    $township->name=$city[1] .$gate; 
            //    //dd("gate");
            // }
            //  else if($request->rcity==3){
            //    $township->name=$city[1] .$post; 
            //    //dd("post");
            // }else{
            //      $township->name=$request->name;
            // }
            $township->name=$request->name;
            $township->delivery_fees=0;
            $township->sender_gate_id=$request->gate;
            // $township->city_id=$city;
            // $township->status=$request->rcity;
            $township->save();
            return redirect()->route('townships.index')->with("successMsg",'New Township is ADDED in your data');
        }
        else
        {
            return redirect::back()->withErrors($validator);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Township  $township
     * @return \Illuminate\Http\Response
     */
    public function show(Township $township)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Township  $township
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	// dd($id);
        $gates=SenderGate::all();
        $township=Township::find($id);
        // dd($township);
        return view('township.gate.edit',compact('gates','township'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Township  $township
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $township)
    {
    	$township=Township::find($township);
        $validator = $request->validate([
            'name'  => ['required'],
            // 'delifee'=>['required','numeric'],
            'gate'=>['required']
        ]);
        // $city=explode('_', $request->city);
        //dd($city);

        if($validator){
            // $gate="Gate";
            // $post="post office";
            // $township=$township;
            // if($request->rcity==2){
            //    $township->name=$request->name; 
            //    //dd("gate");
            // }
            //  else if($request->rcity==3){
            //    $township->name=$request->name; 
            //    //dd("post");
            // }else{
            //      $township->name=$request->name;
            // }
            $township->name=$request->name;
            $township->delivery_fees=0;
            $township->sender_gate_id=$request->gate;
            
            $township->save();
            return redirect()->route('townships.index')->with("successMsg",'Update successfully');
        }
        else
        {
            return redirect::back()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Township  $township
     * @return \Illuminate\Http\Response
     */
    public function destroy($township)
    {
         $township=Township::find($township);
        $township->delete();
       return redirect()->route('townships.index')->with('successMsg','Existing Township is DELETED in your data');
    }

    public function getTownshipgate($gid){
        $data=Township::where('sender_gate_id',$gid)->get();
        return $data;
    }
}
