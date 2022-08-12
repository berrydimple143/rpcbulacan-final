<?php

namespace App\Http\Livewire\Administration;

use App\Models\User;

use Livewire\Component;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Register extends Component
{
    public $email, $password, $first_name, $last_name, $password_confirmation;
    protected $listeners = ['resetInputs'];
    
    protected function rules() {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'password' => ['required', 'min:6', 'confirmed'],
        ];
    }
    
    public function updated($prop) {
        $this->validateOnly($prop);
    }
    
    public function resetInputs() {
        $this->email = "";
        $this->password = "";
        $this->password_confirmation = "";
        $this->first_name = "";
        $this->last_name = "";
    }
    
    public function render() {
        return view('livewire.administration.register')->extends('layouts.auth');
    }
    public function store()
    {
        $this->validate();
        try {
            $pass = Hash::make($this->password);
            $data = [
                'email' => $this->email,
                'first_name' => ucwords(strtolower($this->first_name)),
                'last_name' => ucwords(strtolower($this->last_name)),
                'password' => $pass,
            ];
            DB::beginTransaction();            
            $User = User::create($data);
            $role = Role::where('name','admin')->first();
            $User->assignRole($role);
            DB::commit();
            $this->resetInputs();
            $this->dispatchBrowserEvent('userCreated', ['title' => '', 'msg' => '', 'type' => 'success']);
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('userFailed', ['title' => '', 'msg' => $e->getMessage(), 'type' => 'error']);
            return $e->getMessage();
        }
    }
}
