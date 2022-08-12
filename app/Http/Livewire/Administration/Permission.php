<?php

namespace App\Http\Livewire\Administration;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use DB;
use Exception;
use Spatie\Permission\Models\Permission as UserPermission;

class Permission extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $search = '';
    public $orderBy = 'name';
    public $orderAsc = true;
    public $selected_id, $deleteId;
    public $name;
    
    protected $listeners = ['resetInputs'];
    
    protected function rules() {
        return [
            'name' => ['required'],
        ];
    }
    
    public function resetInputs() {
        $this->name = "";
        $this->deleteId = "";
        $this->selected_id = "";
    }
    
    public function render() {
        return view('livewire.administration.permission', [
            'permissions' => UserPermission::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage),
        ])->extends('layouts.admin');
    }
    public function store() {
        $this->validate();
        try {
            DB::beginTransaction();
                $perm = UserPermission::create(['name' => $this->name]);
            DB::commit();
            $this->resetInputs();
            $this->dispatchBrowserEvent('permissionSaved');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('permissionFailed', ['msg' => $e->getMessage()]);
            return $e->getMessage();
        }
    }
    public function edit($id) {
        $this->selected_id = $id;
        $perm = UserPermission::where('id', $id)->first();
        $this->name = $perm->name;
    }
    public function update() {
        $this->validate();
        try {
            DB::beginTransaction();
                $user = UserPermission::findOrFail($this->selected_id)->update(['name' => $this->name]);
            DB::commit();
            $this->resetInputs();
            $this->dispatchBrowserEvent('permissionUpdated');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('permissionFailed', ['msg' => $e->getMessage()]);
            return $e->getMessage();
        }
    }
}