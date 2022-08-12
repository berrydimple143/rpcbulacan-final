<?php

namespace App\Http\Livewire\Administration;

use App\Models\Province;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\User;
use App\Http\Controllers\HelperController;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use DB;
use Exception;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Registrant extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $search = '';
    public $orderBy = 'id_number';
    public $orderAsc = 'asc';
    public $selected_id, $deleteId, $selected, $valid_id_presented;
    public $first_name, $last_name, $municipality, $barangay, $district_no, $fb_url, $id_number, $province='Bulacan';
    public $listOfMunicipality, $listOfBarangay, $birth_year, $birth_year_edit, $gender;
    public $listOfMunicipality2, $listOfBarangay2, $type = '', $municipality2 = '', $barangay2 = '', $source = '';
    
    protected $listeners = ['resetInputs', 'saved', 'changed'];
    
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
    public function downloadId($id) {
        $this->selected_id = $id;
    }
    public function downloadIDNow() {
        $this->validate([
            'valid_id_presented' => ['required']
        ]);
        try {
            $user = User::where('id', $this->selected_id)->first();
            $id_no = $user->id_number;
            $qrImg = $id_no.'.png';
            
            // for devsite path
            //$qr = \QrCode::size(90)->format('png')->generate($id_no, public_path('images/qrcodes/'.$qrImg));
            
            // for live site
            $path = base_path().'/public_html/images/qrcodes/'.$qrImg;
            $qr = \QrCode::size(90)->format('png')->generate($id_no, $path);
            
            $rt = config('app.url')."control/digital/id?uid=".$id_no."&valid_id=".$this->valid_id_presented;
            $this->dispatchBrowserEvent('download-id', ['rt' => $rt]);
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('download-failed', ['title' => 'Ooopss!', 'msg' => 'Download failed.', 'type' => 'error']);
        }
    }
    public function mount(Request $request) {
        if($request->input('type')) {
            $this->type = $request->input('type');
        }
        if($request->input('municipality2')) {
            $this->municipality2 = $request->input('municipality2');
        }
        if($request->input('source')) {
            $this->source = $request->input('source');
        }
        if(session()->has('type')){
            $this->type = session('type');
        }
        if(session()->has('municipality')){
            $this->municipality = session('municipality');
        }
        if(session()->has('municipality2')){
            $this->municipality2 = session('municipality2');
        }
        if(session()->has('barangay')){
            $this->barangay = session('barangay');
        }
        if(session()->has('baranga2')) {
            $this->baranga2 = session('baranga2');
        }
        if(session()->has('orderBy')){
            $this->orderBy = session('orderBy');
        }
        if(session()->has('orderAsc')) {
            $this->orderAsc = session('orderAsc');
        }
    }
    public function updated($prop) {
        $this->dispatchBrowserEvent('update-birth-year');
        $this->validateOnly($prop);
    }
    public function getBarangay($id) {
        $mun = Municipality::where('municipality_code_number', $id)->first();
        $this->listOfBarangay = Barangay::where('municipality_code_number', $id)->orderBy('barangay_name')->get();
        $this->district_no = $mun->district_no;
    }
    public function sortBy($field, $pos) {
        $order = "";
        if($field == "fname") {
            $order = "first_name";
        } else if($field == "lname") {
            $order = "last_name";
        } else if($field == "mun") {
            $order = "municipality_name";
        } else if($field == "bar") {
            $order = "barangay_name";
        }
        $rt = config('app.url')."control/registrants";
        return redirect($rt)->with('type', $this->type)->with('municipality', $this->municipality)
            ->with('municipality2', $this->municipality2)
            ->with('barangay', $this->barangay)
            ->with('barangay2', $this->barangay2)
            ->with('orderBy', $order)
            ->with('orderAsc', $pos);
    }
    public function resetInputs() {
        $this->first_name = "";
        $this->last_name = "";
        $this->district_no = "";
        $this->fb_url = "";
        $this->id_number = "";
        $this->municipality = "";
        $this->barangay = "";
        $this->selected_id = ""; 
        $this->birth_year = "";
        $this->birth_year_edit = "";
        $this->deleteId = ""; 
        $this->type == "";
        $this->gender == "";
    }
    public function getCode($value) {
        $id_no = HelperController::generateID($this->municipality, $value, $this->district_no);
        if($id_no != 'limit') {
            $this->id_number = $id_no;
        }
    }
    public function generateData($value, $type) {
        if($type == "municipality") {
            $this->municipality2 = $value;
            $this->selected = "municipality";
        } else if($type == "barangay") {
            $this->barangay2 = $value;
            $this->selected = "barangay";
        }
        $this->type = $type;
        $this->render();
    }
    public function searchNow() {
        $this->render();
    }
    public function render() {
        $user = Auth::user();
        $role = $user->roles->pluck('name')[0];
        $arr = User::role(['superadmin', 'admin', 'team lead', 'site lead', 'encoder'])->get('id');
        $deleted = User::where('deleted', 'yes')->get('id');
        $term = $this->search;
        $info = [
            'noOfReg' => User::where('id_number', '!=', null)->whereNotIn('id', $arr)->whereNotIn('id', $deleted)
            ->where(function ($query) use ($term) {
                $query->where('id', "like", "%" . $term . "%")
                ->orWhere('first_name', 'like', '%'.$term.'%')
                ->orWhere('last_name', 'like', '%'.$term.'%')  
                ->orWhere('birth_year', 'like', '%'.$term.'%')
                ->orWhere('fb_url', 'like', '%'.$term.'%')
                ->orWhere('id_number', 'like', '%'.$term.'%')
                ->orWhere('province_name', 'like', '%'.$term.'%')
                ->orWhere('municipality_name', 'like', '%'.$term.'%')  
                ->orWhere('barangay_name', 'like', '%'.$term.'%')
                ->orWhere('created_at', 'like', '%'.$term.'%');
            })->count(),
            'users' => User::where('id_number', '!=', null)->whereNotIn('id', $arr)->whereNotIn('id', $deleted)
                ->where(function ($query) use ($term) {
                $query->where('id', 'like', "%" . $term . "%")
                ->orWhere('first_name', 'like', '%'.$term.'%')
                ->orWhere('last_name', 'like', '%'.$term.'%') 
                ->orWhere('birth_year', 'like', '%'.$term.'%')
                ->orWhere('fb_url', 'like', '%'.$term.'%')
                ->orWhere('id_number', 'like', '%'.$term.'%')
                ->orWhere('province_name', 'like', '%'.$term.'%')
                ->orWhere('municipality_name', 'like', '%'.$term.'%')  
                ->orWhere('barangay_name', 'like', '%'.$term.'%')
                ->orWhere('created_at', 'like', '%'.$term.'%');
            })->orderBy($this->orderBy, $this->orderAsc)->paginate($this->perPage)
        ];
        if($role == "team lead" or $role == "encoder") {
            if($this->source == "link") {
                $this->source = "";
                $munID = $this->municipality2;
                $munplty = $user->municipality;
                if(Str::contains($munplty, ',')) {
                    $arr_mun = explode(',', $munplty);
                    $this->listOfMunicipality = Municipality::whereIn('municipality_code_number', $arr_mun)->orderBy('municipality_name')->get();
                } else {
                    $munID = $munplty;
                }
                if(!empty($munID)) {
                    $this->listOfBarangay2 = Barangay::where('municipality_code_number', $munID)->orderBy('barangay_name')->get();
                    $this->listOfMunicipality2 = $this->listOfMunicipality;
                    $info = [
                        'noOfReg' => User::where('id_number', '!=', null)->where('municipality', $munID)->whereNotIn('id', $arr)->whereNotIn('id', $deleted)
                        ->where(function ($query) use ($term) {
                            $query->where('id', "like", "%" . $term . "%")
                            ->orWhere('first_name', 'like', '%'.$term.'%')
                            ->orWhere('last_name', 'like', '%'.$term.'%')       
                            ->orWhere('birth_year', 'like', '%'.$term.'%')
                            ->orWhere('fb_url', 'like', '%'.$term.'%')
                            ->orWhere('id_number', 'like', '%'.$term.'%')
                            ->orWhere('province_name', 'like', '%'.$term.'%')
                            ->orWhere('municipality_name', 'like', '%'.$term.'%')  
                            ->orWhere('barangay_name', 'like', '%'.$term.'%')
                            ->orWhere('created_at', 'like', '%'.$term.'%');
                        })->count(),
                        'users' => User::where('id_number', '!=', null)->where('municipality', $munID)->whereNotIn('id', $arr)->whereNotIn('id', $deleted)
                        ->where(function ($query) use ($term) {
                            $query->where('id', 'like', "%" . $term . "%")
                            ->orWhere('first_name', 'like', '%'.$term.'%')
                            ->orWhere('last_name', 'like', '%'.$term.'%')   
                            ->orWhere('birth_year', 'like', '%'.$term.'%')
                            ->orWhere('fb_url', 'like', '%'.$term.'%')
                            ->orWhere('id_number', 'like', '%'.$term.'%')
                            ->orWhere('province_name', 'like', '%'.$term.'%')
                            ->orWhere('municipality_name', 'like', '%'.$term.'%')  
                            ->orWhere('barangay_name', 'like', '%'.$term.'%')
                            ->orWhere('created_at', 'like', '%'.$term.'%');
                        })->orderBy($this->orderBy, $this->orderAsc)->paginate($this->perPage)
                    ];
                }
            } else {
                $munplty = $user->municipality;
                if(Str::contains($munplty, ',')) {
                    $arr_mun = explode(',', $munplty);
                    $this->listOfMunicipality = Municipality::whereIn('municipality_code_number', $arr_mun)->orderBy('municipality_name')->get();
                } else {
                    $munID = $munplty;
                    $this->listOfMunicipality = Municipality::where('municipality_code_number', $munID)->orderBy('municipality_name')->get();
                }
                if($this->type == "") {
                    $munplty = $user->municipality;
                    if(Str::contains($munplty, ',')) {
                        $arr_mun = explode(',', $munplty);
                        $munID = $arr_mun[0];
                    } else {
                        $munID = $munplty;
                    }
                    $this->municipality2 = $munID;
                } else {
                    $munID = $this->municipality2;
                }
                $this->listOfMunicipality2 = $this->listOfMunicipality;
                if(!empty($munID)) {
                    $this->listOfBarangay2 = Barangay::where('municipality_code_number', $munID)->orderBy('barangay_name')->get();
                    $info = [
                        'noOfReg' => User::where('id_number', '!=', null)->where('municipality', $munID)->whereNotIn('id', $arr)->whereNotIn('id', $deleted)
                        ->where(function ($query) use ($term) {
                            $query->where('id', "like", "%" . $term . "%")
                            ->orWhere('first_name', 'like', '%'.$term.'%')
                            ->orWhere('last_name', 'like', '%'.$term.'%')    
                            ->orWhere('birth_year', 'like', '%'.$term.'%')
                            ->orWhere('fb_url', 'like', '%'.$term.'%')
                            ->orWhere('id_number', 'like', '%'.$term.'%')
                            ->orWhere('province_name', 'like', '%'.$term.'%')
                            ->orWhere('municipality_name', 'like', '%'.$term.'%')  
                            ->orWhere('barangay_name', 'like', '%'.$term.'%')
                            ->orWhere('created_at', 'like', '%'.$term.'%');
                        })->count(),
                        'users' => User::where('id_number', '!=', null)->where('municipality', $munID)->whereNotIn('id', $arr)->whereNotIn('id', $deleted)
                        ->where(function ($query) use ($term) {
                            $query->where('id', 'like', "%" . $term . "%")
                            ->orWhere('first_name', 'like', '%'.$term.'%')
                            ->orWhere('last_name', 'like', '%'.$term.'%')   
                            ->orWhere('birth_year', 'like', '%'.$term.'%')
                            ->orWhere('fb_url', 'like', '%'.$term.'%')
                            ->orWhere('id_number', 'like', '%'.$term.'%')
                            ->orWhere('province_name', 'like', '%'.$term.'%')
                            ->orWhere('municipality_name', 'like', '%'.$term.'%')  
                            ->orWhere('barangay_name', 'like', '%'.$term.'%')
                            ->orWhere('created_at', 'like', '%'.$term.'%');
                        })->orderBy($this->orderBy, $this->orderAsc)->paginate($this->perPage)
                    ];
                    if($this->type == 'barangay') {
                        $info = [
                            'noOfReg' => User::where('id_number', '!=', null)->where('municipality', $munID)->where('barangay', $this->barangay2)->whereNotIn('id', $arr)->whereNotIn('id', $deleted)
                                ->where(function ($query) use ($term) {
                                $query->where('id', "like", "%" . $term . "%")
                                ->orWhere('first_name', 'like', '%'.$term.'%')
                                ->orWhere('last_name', 'like', '%'.$term.'%')   
                                ->orWhere('birth_year', 'like', '%'.$term.'%')
                                ->orWhere('fb_url', 'like', '%'.$term.'%')
                                ->orWhere('id_number', 'like', '%'.$term.'%')
                                ->orWhere('province_name', 'like', '%'.$term.'%')
                                ->orWhere('municipality_name', 'like', '%'.$term.'%')  
                                ->orWhere('barangay_name', 'like', '%'.$term.'%')
                                ->orWhere('created_at', 'like', '%'.$term.'%');
                            })->count(),
                            'users' => User::where('id_number', '!=', null)->where('municipality', $munID)->where('barangay', $this->barangay2)->whereNotIn('id', $arr)->whereNotIn('id', $deleted)
                            ->where(function ($query) use ($term) {
                            $query->where('id', 'like', "%" . $term . "%")
                            ->orWhere('first_name', 'like', '%'.$term.'%')
                            ->orWhere('last_name', 'like', '%'.$term.'%')  
                            ->orWhere('birth_year', 'like', '%'.$term.'%')
                            ->orWhere('fb_url', 'like', '%'.$term.'%')
                            ->orWhere('id_number', 'like', '%'.$term.'%')
                            ->orWhere('province_name', 'like', '%'.$term.'%')
                            ->orWhere('municipality_name', 'like', '%'.$term.'%')  
                            ->orWhere('barangay_name', 'like', '%'.$term.'%')
                            ->orWhere('created_at', 'like', '%'.$term.'%');
                            })->orderBy($this->orderBy, $this->orderAsc)->paginate($this->perPage)
                        ];
                    }
                }
            }
        } else {
            $this->listOfMunicipality = Municipality::orderBy('municipality_name')->get();
            $munplty = $this->municipality2;
            if((!empty($munplty)) or $munplty != null or $munplty != "") {
                if(Str::contains($munplty, ',')) {
                    $arr_mun = explode(',', $munplty);
                    $munID = $arr_mun[0];
                } else {
                    if($this->type != '') {
                        $munID = $munplty;
                    }
                }
                if(!empty($munID)) {
                    $this->listOfBarangay2 = Barangay::where('municipality_code_number', $munID)->orderBy('barangay_name')->get();
                    if(($this->type == 'municipality' and $this->barangay2 == '') or $this->selected == "municipality") {
                        $info = [
                            'noOfReg' => User::where('id_number', '!=', null)->where('municipality', $munID)->whereNotIn('id', $arr)->whereNotIn('id', $deleted)
                            ->where(function ($query) use ($term) {
                                $query->where('id', "like", "%" . $term . "%")
                                ->orWhere('first_name', 'like', '%'.$term.'%')
                                ->orWhere('last_name', 'like', '%'.$term.'%')  
                                ->orWhere('birth_year', 'like', '%'.$term.'%')
                                ->orWhere('fb_url', 'like', '%'.$term.'%')
                                ->orWhere('id_number', 'like', '%'.$term.'%')
                                ->orWhere('province_name', 'like', '%'.$term.'%')
                                ->orWhere('municipality_name', 'like', '%'.$term.'%')  
                                ->orWhere('barangay_name', 'like', '%'.$term.'%')
                                ->orWhere('created_at', 'like', '%'.$term.'%');
                            })->count(),
                            'users' => User::where('id_number', '!=', null)->where('municipality', $munID)->whereNotIn('id', $arr)->whereNotIn('id', $deleted)
                            ->where(function ($query) use ($term) {
                            $query->where('id', 'like', "%" . $term . "%")
                            ->orWhere('first_name', 'like', '%'.$term.'%')
                            ->orWhere('last_name', 'like', '%'.$term.'%')    
                            ->orWhere('birth_year', 'like', '%'.$term.'%')
                            ->orWhere('fb_url', 'like', '%'.$term.'%')
                            ->orWhere('id_number', 'like', '%'.$term.'%')
                            ->orWhere('province_name', 'like', '%'.$term.'%')
                            ->orWhere('municipality_name', 'like', '%'.$term.'%')  
                            ->orWhere('barangay_name', 'like', '%'.$term.'%')
                            ->orWhere('created_at', 'like', '%'.$term.'%');
                            })->orderBy($this->orderBy, $this->orderAsc)->paginate($this->perPage)
                        ];
                    } else if($this->type == 'barangay') {
                        if($this->barangay2 == "") {
                            $info = [
                                'noOfReg' => User::where('id_number', '!=', null)->where('municipality', $munID)->whereNotIn('id', $arr)->whereNotIn('id', $deleted)
                                        ->where(function ($query) use ($term) {
                                        $query->where('id', "like", "%" . $term . "%")
                                        ->orWhere('first_name', 'like', '%'.$term.'%')
                                        ->orWhere('last_name', 'like', '%'.$term.'%')      
                                        ->orWhere('birth_year', 'like', '%'.$term.'%')
                                        ->orWhere('fb_url', 'like', '%'.$term.'%')
                                        ->orWhere('id_number', 'like', '%'.$term.'%')
                                        ->orWhere('province_name', 'like', '%'.$term.'%')
                                        ->orWhere('municipality_name', 'like', '%'.$term.'%')  
                                        ->orWhere('barangay_name', 'like', '%'.$term.'%')
                                        ->orWhere('created_at', 'like', '%'.$term.'%');
                                    })->count(),
                                'users' => User::where('id_number', '!=', null)->where('municipality', $munID)->whereNotIn('id', $arr)->whereNotIn('id', $deleted)
                                    ->where(function ($query) use ($term) {
                                    $query->where('id', 'like', "%" . $term . "%")
                                    ->orWhere('first_name', 'like', '%'.$term.'%')
                                    ->orWhere('last_name', 'like', '%'.$term.'%')   
                                    ->orWhere('birth_year', 'like', '%'.$term.'%')
                                    ->orWhere('fb_url', 'like', '%'.$term.'%')
                                    ->orWhere('id_number', 'like', '%'.$term.'%')
                                    ->orWhere('province_name', 'like', '%'.$term.'%')
                                    ->orWhere('municipality_name', 'like', '%'.$term.'%')  
                                    ->orWhere('barangay_name', 'like', '%'.$term.'%')
                                    ->orWhere('created_at', 'like', '%'.$term.'%');
                                    })->orderBy($this->orderBy, $this->orderAsc)->paginate($this->perPage)
                            ];
                        } else {
                            $info = [
                                'noOfReg' => User::where('id_number', '!=', null)->where('municipality', $munID)->where('barangay', $this->barangay2)->whereNotIn('id', $arr)->whereNotIn('id', $deleted)
                                    ->where(function ($query) use ($term) {
                                        $query->where('id', "like", "%" . $term . "%")
                                        ->orWhere('first_name', 'like', '%'.$term.'%')
                                        ->orWhere('last_name', 'like', '%'.$term.'%')      
                                        ->orWhere('birth_year', 'like', '%'.$term.'%')
                                        ->orWhere('fb_url', 'like', '%'.$term.'%')
                                        ->orWhere('id_number', 'like', '%'.$term.'%')
                                        ->orWhere('province_name', 'like', '%'.$term.'%')
                                        ->orWhere('municipality_name', 'like', '%'.$term.'%')  
                                        ->orWhere('barangay_name', 'like', '%'.$term.'%')
                                        ->orWhere('created_at', 'like', '%'.$term.'%');
                                    })->count(),
                                'users' => User::where('id_number', '!=', null)->where('municipality', $munID)->where('barangay', $this->barangay2)->whereNotIn('id', $arr)->whereNotIn('id', $deleted)
                                ->where(function ($query) use ($term) {
                                    $query->where('id', 'like', "%" . $term . "%")
                                    ->orWhere('first_name', 'like', '%'.$term.'%')
                                    ->orWhere('last_name', 'like', '%'.$term.'%')     
                                    ->orWhere('birth_year', 'like', '%'.$term.'%')
                                    ->orWhere('fb_url', 'like', '%'.$term.'%')
                                    ->orWhere('id_number', 'like', '%'.$term.'%')
                                    ->orWhere('province_name', 'like', '%'.$term.'%')
                                    ->orWhere('municipality_name', 'like', '%'.$term.'%')  
                                    ->orWhere('barangay_name', 'like', '%'.$term.'%')
                                    ->orWhere('created_at', 'like', '%'.$term.'%');
                                    })->orderBy($this->orderBy, $this->orderAsc)->paginate($this->perPage)
                            ];
                        }
                    }
                } 
            } 
            $this->listOfMunicipality2 = $this->listOfMunicipality;
        }
        return view('livewire.administration.registrant', $info)->extends('layouts.admin');
    }
    public function export() {
        $mun = Municipality::where('municipality_code_number', $this->municipality2)->first();
        if($this->type == "municipality") {
            $filename = 'users_'.$mun->municipality_name.'.xlsx';
            return Excel::download(new UsersExport('municipality', '', $this->municipality2), $filename);
        } else if($this->type == "barangay") {
            $bar = Barangay::where('id', $this->barangay2)->first();
            $filename = 'users_'.$mun->municipality_name.'_'.$bar->barangay_name.'.xlsx';
            return Excel::download(new UsersExport('barangay', $this->municipality2, $this->barangay2), $filename);
        } else if($this->type == "") {
            return Excel::download(new UsersExport('all', '', ''), 'registrants.xlsx');
        }
    }
    public function store() {
        $this->validate();
        $fname = $this->first_name;
        $lname = $this->last_name;
        $mun = $this->municipality;
        $dist = $this->district_no;
        $bar = $this->barangay;
        $rt = route('registrants');
        $id_no = HelperController::generateID($mun, $bar, $dist);
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
            'birth_year' => $this->birth_year,
            'fb_url' => $this->fb_url,
            'gender' => $this->gender,
            'id_number' => $id_no,
        ];
        if(User::where('first_name', $fname)->where('last_name', $lname)->where('municipality', $mun)->where('barangay', $bar)->where('district_no', $dist)->count()) {
            $this->resetInputs();
            $this->dispatchBrowserEvent('user-exist', ['title' => '', 'msg' => 'Name is existing. If you have already registered, click Cancel. Otherwise, click OK  to proceed.', 'type' => 'error', 'rt' => $rt, 'info' => $data]);
        } else {
            $this->resetInputs();
            if($id_no == 'limit') {
                $this->dispatchBrowserEvent('user-limit', ['title' => 'Ooopss!', 'msg' => 'User limit exceeded.', 'type' => 'error', 'rt' => $rt]);
            }
            $msg = "Registration successful.<br/><br/><h2>ID No.: <b>".$id_no."</b></h2>";
            $this->dispatchBrowserEvent('registrant-create', ['msg' => $msg, 'info' => $data]);
        }
    }
    public function saved($info, $msg, $byear) {
        $info['birth_year'] = $byear;
        try {
            DB::beginTransaction();            
            $sy = User::create($info);
            DB::commit();
            $this->resetInputs();
            $this->dispatchBrowserEvent('user-saved', ['title' => 'Congrats!', 'msg' => $msg, 'type' => 'success']);
        } catch (Exception $e) {
            DB::rollBack();
            $this->resetInputs();
            $this->dispatchBrowserEvent('user-failed', ['title' => 'Ooopss!', 'msg' => 'Registration failed.', 'type' => 'error']);
        }
    }
    public function edit($id) {
        $this->selected_id = $id;
        $user = User::where('id', $id)->first();
        $this->first_name = $user->first_name;
        $this->fb_url = $user->fb_url;
        $this->last_name = $user->last_name;
        $this->birth_year_edit = $user->birth_year;
        $this->gender = $user->gender;
        $this->dispatchBrowserEvent('init-birth-year');
    }
    public function changed($info, $byear, $id) {
        $info['birth_year'] = $byear;
        try {
            DB::beginTransaction();
                $user = User::findOrFail($id)->update($info);
            DB::commit();
            $this->resetInputs();
            $this->dispatchBrowserEvent('registrantUpdated');
        } catch (Exception $e) {
            DB::rollBack();
            $this->resetInputs();
            $this->dispatchBrowserEvent('user-failed', ['title' => 'Ooopss!', 'msg' => $e->getMessage(), 'type' => 'error']);
            return $e->getMessage();
        }
    }
    public function update() {
        $this->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'birth_year' => ['nullable'],
            'fb_url' => ['nullable', 'url'],
            'gender' => ['nullable']
        ]);
        $uid = $this->selected_id;
        $data = [
            'fb_url' => $this->fb_url,
            'gender' => $this->gender,
            'first_name' => ucwords(strtolower($this->first_name)),
            'last_name' => ucwords(strtolower($this->last_name)),
        ];
        $this->resetInputs();
        $this->dispatchBrowserEvent('registrantUpdate', ['info' => $data, 'id' => $uid]);
    }
    public function deleteThisId($id) {
        $this->deleteId = $id;
    }
    public function deleteNow() {
        //$user = User::findOrFail($this->deleteId);
        //$user->delete();
        $uid = $this->deleteId;
        $now = Carbon::now('GMT+8');
        $info = ['deleted' => 'yes', 'date_deleted' => $now, 'deleted_by' => Auth::user()->id];
        $user = User::findOrFail($uid)->update($info);
        $this->resetInputs();
        $this->dispatchBrowserEvent('registrantDeleted');
    }
}