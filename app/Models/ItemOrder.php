<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOrder extends Model
{
    use HasFactory;

    protected $table = 'items_orders';
    
    /*** Para obtener el vinculo con la tabla orders */
    public function order(){
        return $this->belongsToMany('App\Models\Order');
    }

    /*** Para obtener el vinculo con la tabla rubros */
    public function rubros(){
        return $this->belongsToMany('App\Models\Rubro');
    }

    /**
     * Para obtener el vinculo con la tabla Item_award_types
     */
    public function itemAwardType(){
        return $this->belongsTo('App\Models\ItemAwardType');
    }

    /**
     * Para obtener el vinculo con la tabla budget_request_providers
     */
    public function budgetRequestProvider(){
        return $this->belongsTo('App\Models\BudgetRequestProvider');
    }

    

    /*** Para obtener el vinculo con la tabla rubros */
    public function rubro(){
        return $this->belongsToMany('App\Models\Rubro');
    }

    /**
     * Para obtener el vinculo con la tabla users
     */
    public function creatorUser(){
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Para obtener el vinculo con la tabla users
     */
    public function modifierUser(){
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Agregamos funciones que formatean los datos
     * para mayor utilidad en los views
     */
    public function provider(){
        if(empty($this->budget_request_provider_id)){
            return "";
        }else{
            return $this->budgetRequestProvider->provider->description;
        }
    }
    public function dncpPacIdFormat(){
        if(empty($this->dncp_pac_id)){
            return "";
        }else{
            return number_format($this->dncp_pac_id,0,",",".");
        }
    }
    public function amountFormat(){
        return number_format($this->amount,0,",",".");
    }

    public function itemFromDateFormat(){
        if(empty($this->item_from)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->item_from));
        }
    }

    public function itemToDateFormat(){
        if(empty($this->item_to)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->item_to));
        }
    }
}
