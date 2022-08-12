<?php

namespace App\Http\Livewire\Registration;

use App\Models\Province;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\User;

use App\Http\Controllers\HelperController;

use Livewire\Component;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use PDF;

class Registration extends Component
{
    public $uppercase = 'style="text-transform:uppercase"', $datenow = '';
    public $first_name, $last_name, $fb_url, $id_number, $birth_year, $gender;
    public $province='Bulacan', $municipality, $barangay, $district_no;
    public $listOfMunicipality, $listOfBarangay;
    
    protected $listeners = ['resetInputs', 'save', 'download'];
    
    protected function rules() {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'municipality' => ['required'],
            'barangay' => ['required'],
            'birth_year' => ['nullable'],
            'district_no' => ['required'],
            'fb_url' => ['nullable'],
            'gender' => ['nullable']
        ];
    }
    
    public function resetInputs() {
        $this->first_name = "";
        $this->last_name = "";
        $this->municipality = "";
        $this->barangay = "";
        $this->district_no = "";
        $this->fb_url = "";
        $this->id_number = "";
        $this->birth_year = "";
        $this->gender = "";
    }
    public function mount(Request $request) {
        if($request->input('fname')) {
            $this->first_name = $request->input('fname');
        }
        if($request->input('lname')) {
            $this->last_name = $request->input('lname');
        }
        if($request->input('fburl')) {
            $this->fb_url = $request->input('fburl');
        }
        if($request->input('birth_year')) {
            $this->birth_year = $request->input('birth_year');
        }
        if($request->input('gender')) {
            $this->gender = $request->input('gender');
        }
    }
    public function render() {
        $this->datenow = Carbon::now('GMT+8');
        $this->listOfMunicipality = Municipality::orderBy('municipality_name')->get();
        if(!empty($this->municipality)) {
            $this->listOfBarangay = Barangay::where('municipality_code_number', $this->municipality)->orderBy('barangay_name')->get();
            $mun = Municipality::where('municipality_code_number', $this->municipality)->first();
            $this->district_no = $mun->district_no;
        }
        return view('livewire.registration.registration')->extends('layouts.site');
    }
    
    public function getCode($value) {
        $id_no = HelperController::generateID($this->municipality, $value, $this->district_no);
        if($id_no != 'limit') {
            $this->id_number = $id_no;
        }
    }
    public function updated($prop) {
        $this->validateOnly($prop);
    }
    public function download($info) {
        $rt = config('app.url')."pdf/?id_number=".$info['id_number']."&first_name=".$info['first_name']."&last_name=".$info['last_name']."&municipality_name=".$info['municipality_name']."&province_name=".$info['province_name']."&barangay_name=".$info['barangay_name'];
        return redirect($rt);
    }
    public function save($info) {
        $id_no = $info['id_number'];
        $counter = User::where('id_number', $id_no)->where('municipality', $info['municipality'])->where('district_no', $info['district_no'])->count();
        if($counter > 0) {
            $rt = config('app.url')."?fname=".$info['first_name']."&lname=".$info['last_name']."&fburl=".$info['fb_url'];
            $this->dispatchBrowserEvent('id-exist', ['title' => 'Ooopss!', 'msg' => 'ID Number was already taken. Please repeat the process.', 'type' => 'error', 'rt' => $rt]);
        } else {
            try {
                DB::beginTransaction();            
                $sy = User::create($info);
                DB::commit();
                $this->resetInputs();
                $dInfo = [
                    'id_number' => $id_no,
                    'first_name' => $info['first_name'],
                    'last_name' => $info['last_name'],
                    'municipality_name' => $info['municipality_name'],
                    'province_name' => $info['province_name'],
                    'barangay_name' => $info['barangay_name'],
                ];
                $msg = "Registration successful.<br/><br/><h2>ID No.: <b>".$id_no."</b></h2>";
                $this->dispatchBrowserEvent('user-saved', ['title' => 'Congrats!', 'msg' => $msg, 'type' => 'success', 'info' => $dInfo]);
            } catch (Exception $e) {
                DB::rollBack();
                $this->dispatchBrowserEvent('user-failed', ['title' => 'Ooopss!', 'msg' => 'Registration failed.', 'type' => 'error']);
            }
        }
    }
    public function store() {
        $this->validate();
        $fname = $this->first_name;
        $lname = $this->last_name;
        $mun = $this->municipality;
        $dist = $this->district_no;
        $bar = $this->barangay;
        $rt = route('home');
        $id_no = $this->id_number;
        $munfirst = Municipality::where('municipality_code_number', $mun)->first();
        $barfirst = Barangay::where('id', $bar)->first();
        $data = [
            'first_name' => ucwords(strtolower($fname)),
            'last_name' => ucwords(strtolower($lname)),
            'municipality' => $mun,
            'municipality_name' => $munfirst->municipality_name,
            'province' => '044',
            'province_name' => 'Bulacan',
            'barangay' => $bar,
            'barangay_name' => $barfirst->barangay_name,
            'district_no' => $dist,
            'fb_url' => $this->fb_url,
            'birth_year' => $this->birth_year,
            'gender' => $this->gender,
            'id_number' => $id_no,
        ];
        if(User::where('first_name', $fname)->where('last_name', $lname)->where('municipality', $mun)->where('barangay', $bar)->where('district_no', $dist)->count()) { 
            $this->dispatchBrowserEvent('user-exist', ['title' => '', 'msg' => 'Name is existing. If you have already registered, click Cancel. Otherwise, click OK  to proceed.', 'type' => 'error', 'rt' => $rt, 'info' => $data]);
        } else {
            if($id_no == 'limit') {
                $this->dispatchBrowserEvent('user-limit', ['title' => 'Ooopss!', 'msg' => 'User limit exceeded.', 'type' => 'error', 'rt' => $rt]);
            }
            $msg = "Please click 'OK' to confirm.";
            $this->dispatchBrowserEvent('user-create', ['title' => 'Confirmation!', 'msg' => $msg, 'type' => 'success', 'info' => $data]);
        }
    }
}