<?php

namespace App\Http\Livewire\Administration;

use App\Models\User as ClientUser;
use App\Models\Municipality;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use DB;
use Exception;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class User extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $search = '';
    public $orderBy = 'first_name';
    public $orderAsc = true;
    public $selected_id, $deleteId;
    public $first_name, $last_name, $email, $password, $new_password, $role, $municipality;
    public $status, $statusMsg = '', $statusBtn = '', $statusClass = '';
    public $listOfRoles, $listOfMunicipality, $showMunicipality = false;
    public $showMunicipality2 = false, $listOfOptions = [], $selectedMunicipalities = [];
    
    protected $listeners = ['resetInputs', 'setMunicipality', 'updateSaved', 'municipalitiesClicked', 'addMunCode', 'removeMunCode'];
    
    protected function rules() {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'password' => ['required', 'min:6'],
            'role' => ['required'],
        ];
    }
    public function mount() {
        $rl = Role::where('name', 'site lead')->first();
        $this->role = $rl->id;
    }
    public function municipalitiesClicked($mid) {
        $this->dispatchBrowserEvent('populate-municipality', ['mid' => $mid]);
    }
    public function removeMunCode($value) {
        $index = array_search($value, $this->selectedMunicipalities);
        if($index !== FALSE){
            unset($this->selectedMunicipalities[$index]);
        }
    }
    public function addMunCode($value, $source) {
        if($source == "checked") {
            $this->selectedMunicipalities[] = $value;
        } else {
            if(Str::contains($value, ',')) {
                $arrVal = explode(",", $value);
                for($i=0; $i < count($arrVal); $i++) {
                    if(!in_array($arrVal[$i], $this->selectedMunicipalities)) {
                        $this->selectedMunicipalities[] = $arrVal[$i];
                    }
                }
            } else {
                if(!in_array($value, $this->selectedMunicipalities)) {
                    $this->selectedMunicipalities[] = $value;
                }
            }
        }
    }
    public function updated($prop) {
        $this->validateOnly($prop);
    }
    public function setMunicipality($val, $data, $role, $roleName) {
        if($roleName == "admin" or $roleName == "site lead" or $roleName == "superadmin") {
        } else {
            $data['municipality'] = join(",",$val);
            $m = [];
            $muns = Municipality::whereIn('municipality_code_number', $val)->get();
            foreach($muns as $mun) {
                $m[] = $mun->municipality_name;
            }
            $data['municipality_name'] = join(",",$m);
        }
        try {
            DB::beginTransaction();            
            $user = ClientUser::create($data);
            $rl = Role::where('id', $role)->first();
            $user->assignRole($rl);
            DB::commit();
            $this->resetInputs();
            $this->dispatchBrowserEvent('userCreated');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('userFailed', ['msg' => $e->getMessage()]);
            return $e->getMessage();
        }
    }
    public function resetInputs() {
        $rl = Role::where('name', 'site lead')->first();
        $this->role = $rl->id;
        $this->email = "";
        $this->password = "";
        $this->new_password = "";
        $this->first_name = "";
        $this->last_name = "";
        $this->role = "";
        $this->selected_id = "";
        $this->deleteId = "";
        $this->municipality = "";
        $this->showMunicipality = false;
        $this->showMunicipality2 = false;
        $this->selectedMunicipalities = [];
    }
    public function render() {
        $this->listOfRoles = Role::where('name', '!=', 'superadmin')->orderBy('name')->get();
        $this->listOfMunicipality = Municipality::orderBy('municipality_name')->get();
        $term = $this->search;
        $data = [
            'users' => ClientUser::role(['admin', 'site lead', 'team lead', 'encoder'])->where('id_number', null)
                ->with('permissions')
                ->where(function ($query) use ($term) {
                    $query->where('id', 'like', "%" . $term . "%")
                    ->orWhere('first_name', 'like', '%'.$term.'%')
                    ->orWhere('last_name', 'like', '%'.$term.'%') 
                    ->orWhere('email', 'like', '%'.$term.'%')
                    ->orWhere('created_at', 'like', '%'.$term.'%');
                })->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->paginate($this->perPage)
        ];
        return view('livewire.administration.user', $data)->extends('layouts.admin');
    }
    public function searchNow() {
        $this->render();
    }
    public function store() {
        $this->validate();
        $pass = Hash::make($this->password);
        $data = [
            'email' => $this->email,
            'first_name' => ucwords(strtolower($this->first_name)),
            'last_name' => ucwords(strtolower($this->last_name)),
            'status' => 'active',
            'password' => $pass,
        ];
        $role = Role::where('id', $this->role)->first();
        $this->dispatchBrowserEvent('getMunVal', ['data' => $data, 'role' => $role]);
    }
    public function changeMun($value) {
        $role = Role::where('id', $value)->first();
        $this->role = $role->id;
        if($role->name == "team lead" or $role->name == "encoder") {
            $this->showMunicipality = true;
            $this->dispatchBrowserEvent('initiate-select');
        } else {
            $this->showMunicipality = false;
        }
    }
    public function changeMun2($value) {
        $usr = ClientUser::find($this->selected_id);
        $role = Role::where('id', $value)->first();
        $this->role = $role->id;
        if($role->name == "team lead" or $role->name == "encoder") {
            $this->showMunicipality2 = true;
            $user = ClientUser::where('id', $this->selected_id)->first();
            $this->listOfMunicipality = Municipality::orderBy('municipality_name')->get();
            if($role->name != $usr->roles->pluck('name')[0]) {
                $this->selectedMunicipalities = [];
            } else {
                $this->dispatchBrowserEvent('initialize-checkbox', ['mun' => $usr->municipality]);
            }
        } else {
            $this->showMunicipality2 = false;
        }
    }
    public function edit($id) {
        $this->selected_id = $id;
        $user = ClientUser::where('id', $id)->first();
        $this->email = $user->email;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->role = $user->roles->pluck('id')[0];
        $roleName = $user->roles->pluck('name')[0];
        $this->listOfRoles = Role::where('name', '!=', 'superadmin')->orderBy('name')->get();
        $this->listOfMunicipality = Municipality::orderBy('municipality_name')->get();
        if($roleName == "team lead" or $roleName == "encoder") {
            $this->showMunicipality2 = true;
            $this->dispatchBrowserEvent('initialize-checkbox', ['mun' => $user->municipality]);
        } else {
            $this->showMunicipality2 = false;
        }
    }
    public function changePass($id) {
        $this->selected_id = $id;
    }
    public function changeStatus($id) {
        $this->selected_id = $id;
        $user = ClientUser::find($id);
        $this->statusMsg = "Do you want to activate this user?";
        $this->statusBtn = "Activate";
        $this->statusClass = "success";
        $this->status = "active";
        if($user->status == "active") {
            $this->statusMsg = "Do you want to de-activate this user?";
            $this->statusBtn = "De-activate";
            $this->statusClass = "danger";
            $this->status = "inactive";
        }
    }
    public function changeStatusNow() {
        $user = ClientUser::findOrFail($this->selected_id)->update(['status' => $this->status]);
        $this->resetInputs();
        $this->dispatchBrowserEvent('statusUpdated', ['stat' => $this->status]);
    }
    public function changeNow() {
        $this->validate(['new_password' => ['required', 'min:6']]);
        try {
            $pass = Hash::make($this->new_password);
            DB::beginTransaction();
                $user = ClientUser::findOrFail($this->selected_id)->update(['password' => $pass]);
            DB::commit();
            $this->resetInputs();
            $this->dispatchBrowserEvent('passUpdated');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('passFailed');
            return $e->getMessage();
        }
    }
    public function updateSaved($val, $data, $role, $roleName, $id) {
        if($roleName == "admin" or $roleName == "site lead" or $roleName == "superadmin") {
            $data['municipality'] = '';
            $data['municipality_name'] = '';
        } else {
            $munValues = $this->selectedMunicipalities;
            if(count($munValues) > 1) {
                $data['municipality'] = join(",", $munValues);
                $m = [];
                $muns = Municipality::whereIn('municipality_code_number', $munValues)->get();
                foreach($muns as $mun) {
                    $m[] = $mun->municipality_name;
                }
                $data['municipality_name'] = join(",",$m);
            } else {
                $data['municipality'] = $munValues[0];
                $muni = Municipality::where('municipality_code_number', $munValues[0])->first();
                $data['municipality_name'] = $muni->municipality_name;
            }
        }
        
        try {
            DB::beginTransaction();
                $user = ClientUser::findOrFail($id)->update($data);
                $usr = ClientUser::find($id);
                $usrl = $usr->roles->pluck('id')[0];
                if($usrl != $role) {
                    $rlname = $usr->roles->pluck('name')[0];
                    $usr->removeRole($rlname);
                    $rl = Role::where('id', $role)->first();
                    $usr->assignRole($rl);
                }
            DB::commit();
            $this->resetInputs();
            $this->dispatchBrowserEvent('userUpdated');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('userFailed', ['msg' => $e->getMessage()]);
            return $e->getMessage();
        }
    }
    public function update() {
        $this->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->selected_id, 'id')],
        ]);
        $data = [
            'email' => $this->email,
            'first_name' => ucwords(strtolower($this->first_name)),
            'last_name' => ucwords(strtolower($this->last_name)),
        ];
        $role = Role::where('id', $this->role)->first();
        $this->dispatchBrowserEvent('getMunVal2', ['data' => $data, 'role' => $role, 'id' => $this->selected_id, 'mun' => $this->selectedMunicipalities]);
    }
    public function deleteThisId($id) {
        $this->deleteId = $id;
    }
    public function deleteNow() {
        $user = ClientUser::findOrFail($this->deleteId);
        $user->roles()->detach();
        $now = Carbon::now('GMT+8');
        $info = ['deleted' => 'yes', 'date_deleted' => $now, 'deleted_by' => Auth::user()->id];
        $usr = ClientUser::findOrFail($this->deleteId)->update($info);
        //$user->delete();
        $this->dispatchBrowserEvent('userDeleted');
    }
}