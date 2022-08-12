<?php

namespace App\Http\Livewire\Administration;

use App\Models\User;

use Livewire\Component;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email, $password;
    
    protected $listeners = ['resetInputs'];
    
    protected function rules() {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }
    
    public function resetInputs() {
        $this->email = "";
        $this->password = "";
    }
    
    public function updated($prop) {
        $this->validateOnly($prop);
    }
    
    public function mount() {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
    }
    
    public function render() {
        return view('livewire.administration.login')->extends('layouts.auth');
    }
    
    public function authenticate() {
        $this->validate();
        try {
            $user = User::where('email', $this->email)->first();
            if($user) {
                if (Hash::check($this->password, $user->password)) {
                    if($user->status == "active") {
                        Auth::login($user);
                        return redirect()->route('dashboard');
                    } else {
                        return redirect()->route('account.deactivated');
                    }
                } else {
                    $this->dispatchBrowserEvent('mismatch', ['title' => 'Ooopss!', 'msg' => 'Email and password combination is incorrect.', 'type' => 'error']);
                }
            } else {
                $this->dispatchBrowserEvent('email-not-found', ['title' => 'Not found!', 'msg' => 'Sorry! The email you typed was not found in our database.', 'type' => 'error']);
            }
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('loginFailed', ['title' => '', 'msg' => $e->getMessage(), 'type' => 'error']);
            return $e->getMessage();
        }
    }
}