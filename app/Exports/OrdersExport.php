<?php

namespace App\Exports;

//use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

// class ItemAwardsExport implements FromCollection


class OrdersExport implements FromView
{
    //use Exportable;

    // protected $order_id;
    
    // public function __construct()
    // {
        
    // }

    public function view(): view    
    {        
        $orders = DB::table('vista_full')//vista que muestra los datos generales
                    ->select(['fiscal_name',
'fiscal_lastname',
'providers_description', 
'components_code',
'orders_number',
'orders_date',
'orders_total_amount',
'districts_description',
'orders_locality',
'components_description',
'sign_date',
'orders_plazo',
'order_states_description'])                    
                    ->where('monto_adjudica', null)
                    ->orderBy('provider','asc') 
                    ->get();
        //$orders = Order::all();
        return view("order.orders.ordersexcel", compact("orders"));

        

    }
}