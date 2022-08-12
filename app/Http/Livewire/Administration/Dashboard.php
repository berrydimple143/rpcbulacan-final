<?php

namespace App\Http\Livewire\Administration;

use App\Models\User;
use App\Models\Municipality;
use App\Models\Barangay;

use Livewire\Component;
use Illuminate\Http\Request;
use DB;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Dashboard extends Component
{
    public function render() {
        $user = Auth::user();
        $mun = $user->municipality;
        $role = $user->roles->pluck('name')[0];
        $arr_mun = [];
        if(!empty($mun) or $mun != null) {
            if(Str::contains($mun, ',')) {
                $arr_mun = explode(',', $mun);
                
            } else {
                $arr_mun[] = $mun;
            }
        }
        $arr = User::role(['superadmin', 'admin', 'team lead', 'site lead', 'encoder'])->get('id');
        if($role == "team lead" or $role == "encoder") {
            $today = User::whereDate('created_at', Carbon::today())->whereIn('municipality', $arr_mun)->whereNotIn('id', $arr)->count();
            $total = User::whereNotIn('id', $arr)->whereIn('municipality', $arr_mun)->count();
            $locations = Municipality::whereIn('municipality_code_number', $arr_mun)->orderBy('municipality_name')->get();
        } else {
            $today = User::whereDate('created_at', Carbon::today())->whereNotIn('id', $arr)->count();
            $total = User::whereNotIn('id', $arr)->count();
            $locations = Municipality::orderBy('municipality_name')->get();
        }
        return view('livewire.administration.dashboard', [
            'today' => $today,
            'total' => $total,
            'todaymsg' => "Today’s Registrants",
            'totalmsg' => "Total Registrants",
            'locations' => $locations
        ])->extends('layouts.admin');
    }
}