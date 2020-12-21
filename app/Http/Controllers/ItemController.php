<?php

namespace App\Http\Controllers;

use App\Item;
use App\Client;
use App\Pickup;
use App\Township;
use App\DeliveryMan;
use App\Way;
use App\Expense;
use Carbon;
use Auth;
use App\SenderGate;
use App\SenderPostoffice;
use Session;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;
use App\Bank;
use App\Transaction;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //$items=Item::doesntHave('way')->get();
      $items=Item::whereHas('pickup',function($query){
              $query->where('status',1);
            })
            ->doesntHave('way')
            ->get();
  
      // dd($myitems);
      
      $deliverymen = DeliveryMan::with(['townships'=> function($q){
                     $q->orderBy('name','asc');
                      }])->get();

        //dd($deliverymen);
      $ways = Way::orderBy('id', 'desc')->get();
      $notifications=DB::table('notifications')->select('data')->where('notifiable_type','App\Way')->get();
      $data=[];
      foreach ($notifications as $noti) {
        $notiway=json_decode($noti->data);
        if($notiway->ways->status_code=="005"){
          array_push($data, $notiway->ways);
        }
      }
      // dd($data);
      return view('item.index',compact('items','deliverymen','ways','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('item.create',compact('townships'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // dd($request);

      $qty=$request->qty;
       // dd($qty);
      $myqty=$request->myqty;
      $damount=$request->depositamount;

      //dd($damount);
      $validator = $request->validate([
        'receiver_name'  => ['required','string'],
        'receiver_phoneno'=>['required','string'],
        'receiver_address'=>['required','string'],
        'receiver_township'=>['required','not_in:null'],
        'expired_date'=>['required','date'],
        // 'deposit'=>['required'],
        'delivery_fees'=>['required'],
        'amount'=>['required'],
      ]);

      if($validator){
        //dd('c');
        //dd($request->deposit);
        $item=new Item;
        $item->codeno=$request->codeno;
        $item->expired_date=$request->expired_date;
        $item->deposit=$request->deposit;
        $item->amount =$request->amount;
        if($request->othercharges!=null){
          $item->delivery_fees=$request->delivery_fees+$request->othercharges;
        }else{
          $item->delivery_fees=$request->delivery_fees;
        }
        
        $item->receiver_name=$request->receiver_name;
        $item->receiver_address=$request->receiver_address;
        $item->receiver_phone_no=$request->receiver_phoneno;
        $item->remark=$request->remark;
        $item->paystatus=$request->amountstatus;
        $item->pickup_id=$request->pickup_id;
        $item->township_id=$request->receiver_township;

        if($request->mygate!=null){
          $item->sender_gate_id=$request->mygate;
        }

        if($request->myoffice!=null){
          $item->sender_postoffice_id=$request->myoffice;
        }

        $role=Auth::user()->roles()->first();
        $rolename=$role->name;
        if($rolename=="staff"){
          $user=Auth::user();
          $staffid=$user->staff->id;
          $item->staff_id=$staffid;
        }
        $item->save();

        if($qty==1){
          $allpaiditemsamount = Item::where('pickup_id',$request->pickup_id)->where('paystatus',2)->sum('delivery_fees');
          // dd($allpaiditemsamount);
          $checkitems = Item::orderBy('id', 'desc')->take($myqty)->get();
          //dd($checkitems->sum('deposit'));
          // amount မကိုက်တဲ့ အခြေအနေ
          if($checkitems->sum('deposit')!=$damount){
            //dd("hi");
            //foreach ($checkitems as $value) {
            $pickup=Pickup::find($checkitems[0]->pickup_id);
            $pickup->status=2;
            $pickup->save();
            //}
            return redirect()->route('checkitem',$pickup->id); 
          }else{
            if($request->paidamount!=null){
              $expense=new Expense;
              $expense->amount=$request->paidamount;
              $expense->client_id=$request->client_id;
              if($rolename=="staff"){
                $user=Auth::user();
                $staffid=$user->staff->id;
                $expense->staff_id=$staffid;
              }
              $expense->status=$request->paystatus;
              $expense->description="Client Deposit";
              $expense->city_id=1;
              $expense->expense_type_id=1;
              $expense->save();

              $expense=new Expense;
              $unexpenseamount=$request->depositamount-$request->paidamount;
              $expense->amount=$unexpenseamount;
              $expense->client_id=$request->client_id;
              if($rolename=="staff"){
                $user=Auth::user();
                $staffid=$user->staff->id;
                $expense->staff_id=$staffid;
              }
              $expense->status=2;
              $expense->description="Client Deposit";
              $expense->city_id=1;
              $expense->expense_type_id=1;
              $expense->save();
            }else{
              $expense=new Expense;
              $expense->amount=$damount;
              $expense->guest_amount=$damount-$allpaiditemsamount;
              $expense->client_id=$request->client_id;
              if($rolename=="staff"){
                $user=Auth::user();
                $staffid=$user->staff->id;
                $expense->staff_id=$staffid;
              }
              $expense->status=$request->paystatus;
              $expense->description="Client Deposit";
              $expense->city_id=1;
              $expense->expense_type_id=1;
              $expense->save();
            }
            // insert into transaction if paid
            if($request->paystatus == 1){
              $transaction = new Transaction;
              $transaction->bank_id = $request->payment_method;
              $transaction->expense_id = $expense->id;
              if($request->paidamount!=null){
                 $transaction->amount = $request->paidamount;
              }else{
                $transaction->amount = $request->depositamount;
              }
              
              $transaction->description = "Client Deposit";
              $transaction->save();

              $bank = Bank::find($request->payment_method);
               if($request->paidamount!=null){
                $bank->amount=$bank->amount-$request->paidamount;
               }else{
                 $bank->amount = $bank->amount-$request->depositamount;
               }
              $bank->save();
            }
          }
        }

        //dd("hello");
        $pickup = Pickup::find($item->pickup_id);
        if (($pickup->schedule->quantity - count($pickup->items)) > 0) {
          return redirect()->back()->with("successMsg",'New Item is ADDED');
        }else{
          return redirect()->route('items.index')->with("successMsg",'New Item is ADDED in your data');
        }


      }
      else
      {
        return redirect::back()->withErrors($validator);
      }
            
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        $item=$item;
        return $item;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $item=$item;
        $townships=Township::orderBy('name','asc')->get();
        $sendergates=SenderGate::orderBy('name','asc')->get();
        $senderoffice=SenderPostoffice::orderBy('name','asc')->get();
        return view('item.edit',compact('item','townships','sendergates','senderoffice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
          $validator = $request->validate([
            'receiver_name'  => ['required','string'],
            'receiver_phoneno'=>['required','string'],
            'receiver_address'=>['required','string'],
            'receiver_township'=>['required'],
            'expired_date'=>['required','date'],
            'deposit'=>['required'],
            'delivery_fees'=>['required'],
            'amount'=>['required'],
        ]);

         if($validator){
            $item=$item;
            $item->codeno=$request->codeno;
            $item->expired_date=$request->expired_date;
            $item->deposit=$request->deposit;
            $item->amount =$request->amount;
            $item->delivery_fees=$request->delivery_fees;
            $item->receiver_name=$request->receiver_name;
            $item->receiver_address=$request->receiver_address;
            $item->receiver_phone_no=$request->receiver_phoneno;
            $item->remark=$request->remark;
            $item->paystatus=$request->amountstatus;
            $item->township_id=$request->receiver_township;
           if($request->mygate!=null){
              $item->sender_gate_id=$request->mygate;
            }
            if($request->myoffice!=null){
              $item->sender_postoffice_id=$request->myoffice;
            }
             $role=Auth::user()->roles()->first();
             $rolename=$role->name;
              if($rolename=="staff"){
                $user=Auth::user();
                 $staffid=$user->staff->id;
                $item->staff_id=$staffid;
            }
            $item->save();
           return redirect()->route('items.index')->with("successMsg",'Updatesuccessfully');
        }
        else
        {
            return redirect::back()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item=$item;
        $item->delete();
       return redirect()->route('items.index')->with('successMsg','Existing Item is DELETED in your data');
    }

    // here accept client id
    public function collectitem($cid, $pid)
    {

        $itemcode="";
        $client = Client::find($cid);
        $codeno=$client->codeno;
        // dd($codeno);
        $mytime = Carbon\Carbon::now();
        //dd($checktime);
        $array = explode('-', $mytime->toDateString());
        $datecode=$array[2]."001";
        
        // dd($datecode);
        // $items=Item::all();
        $item=Item::whereDate('created_at',Carbon\Carbon::today())->orderBy('id','desc')->first();
        //dd($item);
        if(!$item){
           $itemcode=$codeno.$datecode;
           // dd($itemcode);
        }else{
        $code=$item->codeno;
        $mycode=substr($code, 11,14);
        //dd($mycode);
        $itemcode=$codeno.$array[2].$mycode+1;
            
        }
        //dd($itemcode);
        //dd($datecode);
        $pickup = Pickup::find($pid);
        // $townships=Township::all();
        $townships = Township::orderBy('name','asc')->where('status',1)->get();

        $sendergates=SenderGate::orderBy('name','asc')->get();
        $senderoffice=SenderPostoffice::orderBy('name','asc')->get();

        $pickupeditem = Item::where('pickup_id',$pickup->id)->orderBy('id','desc')->first();
        $banks = Bank::orderBy('name','asc')->get();
        return view('item.create',compact('banks','client','pickup','townships','itemcode','pickupeditem','sendergates','senderoffice'));
    }


    public function delichargebytown(Request $request){
       $id=$request->id;
       //dd($id);
       $township=Township::find($id);
       $deliverycharge=$township->delivery_fees;
       //dd($deliverycharge);
       return $deliverycharge;
    }

    public function itemdetail(Request $request){
        $id=$request->id;
        $item=Item::find($id);
        return $item;
    }

    public function assignWays(Request $request)
    {
        //dd($request);
        $myways=$request->ways;
        //dd($myways);
        foreach($myways as $myway){
            $way=new Way;
            $way->status_code="005";
            $way->item_id=$myway;
            $way->delivery_man_id=$request->delivery_man;
            $role=Auth::user()->roles()->first();
            $rolename=$role->name;
              if($rolename=="staff"){
                $user=Auth::user();
                $staffid=$user->staff->id;
                $way->staff_id=$staffid;
            }
            $way->status_id=5;
            $way->save();


        }
return redirect()->route('items.index')->with("successMsg",'way assign successfully');
    }


    public function updatewayassign(Request $request){
        $id=$request->wayid;

            $way=Way::find($id);
            $way->delivery_man_id=$request->delivery_man;
            $role=Auth::user()->roles()->first();
            $rolename=$role->name;
              if($rolename=="staff"){
                $user=Auth::user();
                $staffid=$user->staff->id;
                $way->staff_id=$staffid;
            }
            $way->save();
            return redirect()->route('items.index')->with("successMsg",'way assign update successfully');
    }

    public function deletewayassign($id){
        $way=Way::find($id);
        $way->delete();
        return redirect()->route('items.index')->with("successMsg",'way assign delete successfully');
    }

    public function townshipbystatus(Request $request){
        $id=$request->id;
        $township=Township::where('status',$id)->get();
        return $township;
    }

    public function checkitem($pickupid){
    
    $checkitems=Item::where('pickup_id',$pickupid)->get();
    $banks = Bank::all();
    return view('dashboard.checkitem',compact('checkitems','banks'))->with("successMsg",'items amount are wrong');
      //dd($pickupid);
      
    }

    public function updateamount(Request $request){
      $checkitemarray=$request->myarray;
      foreach ($checkitemarray as $value) {

       $item=Item::find($value["id"]);
       $deliveryfee=$item->delivery_fees;
       $item->deposit=$value["amount"];
       $item->amount=$value["amount"]+$deliveryfee;
       $item->save();

       $pickup=Pickup::find($item->pickup_id);
       $pickup->status=1;
       $pickup->save();
      }

      $expense=new Expense;
      $expense->amount=$request->totaldeposit;
      $expense->client_id=$pickup->schedule->client_id;
      $role=Auth::user()->roles()->first();
      $rolename=$role->name;
      
      if($rolename=="staff"){
        $user=Auth::user();
        $staffid=$user->staff->id;
        $expense->staff_id=$staffid;
      }
      $expense->status=$request->paystatus;
      $expense->description="Client Deposit";
      $expense->city_id=1;
      $expense->expense_type_id=1;
      $expense->save();

      // insert into transaction if paid
      if($request->paystatus == 1){
        $transaction = new Transaction;
        $transaction->bank_id = $request->payment_method;
        $transaction->expense_id = $expense->id;
        $transaction->amount = $request->totaldeposit;
        $transaction->description = "Client Deposit";
        $transaction->save();

        $bank = Bank::find($request->payment_method);
        $bank->amount = $bank->amount-$request->totaldeposit;
        $bank->save();
      }

      return "success";
    }
}
