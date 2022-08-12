<?php

namespace App\Http\Livewire\Administration;

use App\Models\User;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Image;

class Digital extends Component
{
    public $uid, $filename, $valid_id;
    protected $listeners = ['initDownload', 'download'];
     
    public function mount(Request $request) {
        if($request->input('uid')) {
            $this->uid = $request->input('uid');
        }
        if($request->input('valid_id')) {
            $this->valid_id = $request->input('valid_id');
        }
    }
    public function initDownload($filename) {
        $this->dispatchBrowserEvent('download-div', ['flname' => $filename]);
    }
    public function download($data, $flname) {
        $img = Image::make($data);
        // for devsite path
        //$destinationPath = public_path('images/temp');
        
        //for live site
        $destinationPath = base_path().'/public_html/images/temp';
        
        $img->save($destinationPath.'/'.$flname);
        $url = asset('images/temp/'.$flname);
        $image = Image::make($url)->encode('png');
        $headers = [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'attachment; filename='. $flname,
        ];
        return response()->streamDownload(function() use ($image) {
            print($image);
        }, $flname, $headers);
    }
    
    public function render() {
        $user = User::where('id_number', $this->uid)->first();
        $auth_user = Auth::user();
        $now = Carbon::now('GMT+8');
        $info = [
            'valid_id_presented' => $this->valid_id,
            'username' => $auth_user->first_name,
            'released_by' => $auth_user->id,
            'date_released' => $now,
            'id_released' => 'yes'
        ];
        $usr = User::where('id_number', $this->uid)->update($info);
        $name = $user->first_name." ".$user->last_name;
        $address = "Brgy. ".$user->barangay_name.", ".$user->municipality_name.", ".$user->province_name;
        $qrImg = $this->uid.".png";
        $img = asset('images/qrcodes/'.$qrImg);
        $data = [
            'name' => ucwords($name),
            'id' => $this->uid,
            'address' => ucwords($address),
            'qrcode_size' => 90,
            'img' => $img
        ];
        $this->filename = $this->uid.".png";
        return view('livewire.administration.digital', $data)->extends('layouts.download', ['filename' => $this->filename]);
    }
    
}
