<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
    @include('livewire.administration.attach_permission')
    @include('livewire.administration.role_add')
    @include('livewire.administration.role_edit')
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">List of User Roles</h4>
            
            <div class="row">	
	    	    <div class="col-5">
	                <div class="input-group">
                      <input type="text" class="form-control" placeholder="Search for roles here ..." aria-label="Search for users here ...">
                      <div class="input-group-append">
                        <button class="btn btn-sm btn-primary" type="button">Search</button>
                      </div>
                    </div>		                
	            </div>
	            <div class="col-5">&nbsp;</div>
	            <div class="col-2" style="text-align: right;">
	                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addRole"><i class="mdi mdi-plus-circle-outline"></i>&nbsp;Add</button>
	            </div>
	        </div>
            
          <div class="table-responsive pt-3">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 50px; text-align: center;">#</th>
                  <th style="text-align: center;">Role Name</th>
                  <th style="text-align: center;">Permissions</th>
                  <th style="width: 100px; text-align: center;" nowrap>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($roles as $role)
                <tr>
                  <td>{{ (($roles->currentPage() * $roles->perPage()) - $roles->perPage()) + $loop->iteration  }}</td>
                  <td>{{ $role->name }}</td>
                  <td>{{ App\Http\Controllers\HelperController::getRolePermissions($role->name) }}</td>
                  <td style="text-align: center;">
                    <button wire:click="addPerm({{ $role->id }})" type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addPermission"><i class="mdi mdi-library-plus"></i>&nbsp;Add Permission</button>
                    <button wire:click="edit({{ $role->id }})" type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editRole"><i class="mdi mdi-lead-pencil"></i>&nbsp;Edit</button>
                    <button wire:click="deleteThisId({{ $role->id }})" type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteRole"><i class="mdi mdi-delete-sweep"></i>&nbsp;Delete</button>
                  </td>
                </tr>
                @empty
                    <tr><td colspan="4">No roles yet ...</td></tr>
                @endforelse
              </tbody>
            </table>
            <br/>
            {!! $roles->links() !!}
          </div>
          
          <div wire:ignore.self class="modal fade" id="deleteRole" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
	            <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <h5 class="modal-title" id="deleteModalLabel">Delete Confirm</h5>
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                            <span aria-hidden="true close-btn">×</span>
	                        </button>
	                    </div>
	                    <div class="modal-body">
	                        <p>Are you sure want to delete this role?</p>
	                    </div>
	                    <div class="modal-footer">
	                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
	                        <button type="button" wire:click="deleteNow" class="btn btn-danger" data-dismiss="modal">Yes, Delete it</button>
	                    </div>
	                </div>
	            </div>
	        </div>
          
        </div>
      </div>
    </div>
</div>