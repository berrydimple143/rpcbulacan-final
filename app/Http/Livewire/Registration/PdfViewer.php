<?php

namespace App\Http\Livewire\Registration;

use Livewire\Component;
use Illuminate\Http\Request;
use Image;

class PdfViewer extends Component
{
    public $id_number;
    public $first_name;
    public $last_name;
    public $municipality_name;
    public $province_name;
    public $barangay_name;
    public $qrcode_size = 90;
    public $pdfroute;
    public $filename;
    
    protected $listeners = ['initiateDownload'];
    
    public function initiateDownload($data, $flname) {
        $img = Image::make($data);
        $destinationPath = base_path().'/public_html/images/temp';
        //$destinationPath = public_path('images/temp');
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
    public function mount(Request $request) {
        if($request->input('id_number')) {
            $this->id_number = $request->input('id_number');
        }
        if($request->input('first_name')) {
            $this->first_name = $request->input('first_name');
        }
        if($request->input('last_name')) {
            $this->last_name = $request->input('last_name');
        }
        if($request->input('municipality_name')) {
            $this->municipality_name = $request->input('municipality_name');
        }
        if($request->input('province_name')) {
            $this->province_name = $request->input('province_name');
        }
        if($request->input('barangay_name')) {
            $this->barangay_name = $request->input('barangay_name');
        }
    }
    public function render() {
        $id_no = $this->id_number;
        $qrImg = $id_no.'.png';
        $path = base_path().'/public_html/images/qrcodes/'.$qrImg;
        $qr = \QrCode::size($this->qrcode_size)->format('png')->generate($id_no, $path);
        $name = $this->first_name." ".$this->last_name;
        $address = "Brgy. ".$this->barangay_name.", ".$this->municipality_name.", ".$this->province_name;
        $img = asset('images/qrcodes/'.$qrImg);
        $data = [
            'name' => ucwords($name),
            'id' => $id_no,
            'address' => ucwords($address),
            'qrcode_size' => $this->qrcode_size,
            'img' => $img
        ];
        $this->filename = $id_no.".png";
        return view('livewire.registration.pdf', $data)->extends('layouts.pdf');
    }
    public function downloadnow() {
        $this->dispatchBrowserEvent('download-div', ['flname' => $this->filename]);
    }
}