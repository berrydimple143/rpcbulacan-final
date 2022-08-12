<?php

namespace App\Http\Livewire\Administration;

use App\Models\User;

use Livewire\Component;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Deactivated extends Component
{
    public function render() {
        return view('livewire.administration.deactivated')->extends('layouts.auth');
    }
}