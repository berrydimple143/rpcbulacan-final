<?php

namespace App\Http\Livewire\Administration;

use Livewire\Component;
use Livewire\WithPagination;
use DB;
use Exception;
use Spatie\Permission\Models\Role as UserRole;
use Spatie\Permission\Models\Permission;

class Role extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $search = '';
    public $orderBy = 'name';
    public $orderAsc = true;
    public $selected_id, $deleteId;
    public $name, $permission, $listOfPermissions;
    
    protected $listeners = ['resetInputs'];
    
    protected function rules() {
        return [
            'name' => ['required'],
        ];
    }
    
    public function resetInputs() {
        $this->name = "";
        $this->permission = "";
        $this->deleteId = "";
        $this->selected_id = "";
    }
    public function addPerm($id) {
        $this->selected_id = $id;
    }
    public function savePermission() {
        $this->validate(['permission' => ['required']]);
        $role = UserRole::where('id', $this->selected_id)->first();
        $role->givePermissionTo($this->permission);
        $this->resetInputs();
        $this->dispatchBrowserEvent('rolePermissionAdded');
    }
    public function render() {
        $this->listOfPermissions = Permission::orderBy('name')->get();
        return view('livewire.administration.role', [
            'roles' => UserRole::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage),
        ])->extends('layouts.admin');
    }
    public function store() {
        $this->validate();
        try {
            DB::beginTransaction();
                $role = UserRole::create(['name' => $this->name]);
            DB::commit();
            $this->resetInputs();
            $this->dispatchBrowserEvent('roleSaved');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('roleFailed', ['msg' => $e->getMessage()]);
            return $e->getMessage();
        }
    }
    public function edit($id) {
        $this->selected_id = $id;
        $role = UserRole::where('id', $id)->first();
        $this->name = $role->name;
    }
    public function update() {
        $this->validate();
        try {
            DB::beginTransaction();
                $role = UserRole::findOrFail($this->selected_id)->update(['name' => $this->name]);
            DB::commit();
            $this->resetInputs();
            $this->dispatchBrowserEvent('roleUpdated');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('roleFailed', ['msg' => $e->getMessage()]);
            return $e->getMessage();
        }
    }
    public function deleteThisId($id) {
        $this->deleteId = $id;
    }
    public function deleteNow() {
        $user = UserRole::where('id', $this->deleteId)->delete();
        $this->dispatchBrowserEvent('roleDeleted');
    }
}
