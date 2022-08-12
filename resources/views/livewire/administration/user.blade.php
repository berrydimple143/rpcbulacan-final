<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
      @include('livewire.administration.change_password')
      @include('livewire.administration.change_status')
      @include('livewire.administration.user_add')
      @include('livewire.administration.user_edit')
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">List of Users</h4>
            
            <div class="row">	
	    	    <div class="col-5">
	                <div class="input-group">
                      <input wire:model="search" wire:keydown.enter="searchNow" type="text" class="form-control" placeholder="Search for users here ..." aria-label="Search for users here ...">
                      <div class="input-group-append">
                        <button wire:click="searchNow" class="btn btn-sm btn-primary" type="button"><i class="icon-search"></i>&nbsp;Search</button>
                      </div>
                    </div>		                
	            </div>
	            <div class="col-5">&nbsp;</div>
	            <div class="col-2" style="text-align: right;">
	                <button type="button" class="btn btn-outline-primary btn-icon-text" data-toggle="modal" data-target="#addUser"><i class="ti-plus btn-icon-prepend"></i>Add</button>
	            </div>
	        </div>
            
          <div class="table-responsive pt-3">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 50px; text-align: center;">#</th>
                  <th style="text-align: center;" wrap>First Name</th>
                  <th style="text-align: center;" wrap>Last Name</th>
                  <th style="text-align: center;" wrap>Email</th>
                  <th style="text-align: center;" wrap>Role(s)</th>
                  <th style="width: 60px; text-align: center;" wrap>Permission(s)</th>
                  <th style="width: 140px; text-align: center;">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($users as $user)
                <?php
                    $statBtn = "Activate";
                    $btnColor = "#972969";
                    if($user->status == "active") {
                        $statBtn = "De-activate";
                        $btnColor = "#FE51B5";
                    }
                    $roless = App\Http\Controllers\HelperController::convertArrayToString($user->getRoleNames(), ',');
                ?>
                @if(!(Illuminate\Support\Str::contains($roless, 'superadmin')))
                    <tr>
                      <td>{{ (($users->currentPage() * $users->perPage()) - $users->perPage()) + $loop->iteration  }}</td>
                      <td>{{ $user->first_name }}</td>
                      <td>{{ $user->last_name }}</td>
                      <td>{{ $user->email }}</td>
                      <td style="text-align: center;">{{ $roless }}</td>
                      <td style="text-align: center;">{{ App\Http\Controllers\HelperController::convertObjectToString($user->getPermissionsViaRoles(), ',') }}</td>
                      <td style="text-align: center;">
                        <button wire:click="changeStatus({{ $user->id }})" type="button" class="btn btn-sm btn-icon-text" style="background-color: {{ $btnColor }}; color: #fff;" data-toggle="modal" data-target="#changeStatus"><i class="ti-user btn-icon-prepend"></i>{{ $statBtn }}</button>
                        <button wire:click="changePass({{ $user->id }})" type="button" class="btn btn-sm btn-icon-text" style="background-color: #FE51B5; color: #fff;" data-toggle="modal" data-target="#changePassword"><i class="ti-key btn-icon-prepend"></i>Change Pass</button>
                        <button wire:click="edit({{ $user->id }})" type="button" class="btn btn-sm btn-icon-text" style="background-color: #FE51B5; color: #fff;" data-toggle="modal" data-target="#editUser"><i class="ti-pencil btn-icon-prepend"></i>Edit</button>
                        <button wire:click="deleteThisId({{ $user->id }})" type="button" class="btn btn-sm btn-icon-text" style="background-color: #972969; color: #fff;" data-toggle="modal" data-target="#deleteUser"><i class="ti-trash btn-icon-prepend"></i>Delete</button>
                        
                      </td>
                    </tr>
                @endif
                @empty
                    <tr><td colspan="7">No users yet ...</td></tr>
                @endforelse
              </tbody>
            </table>
            <br/>
            {!! $users->links() !!}
          </div>
          
          <div wire:ignore.self class="modal fade" id="deleteUser" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
	            <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <h5 class="modal-title" id="deleteModalLabel">Delete Confirm</h5>
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                            <span aria-hidden="true close-btn">×</span>
	                        </button>
	                    </div>
	                    <div class="modal-body">
	                        <p>Are you sure want to delete this user?</p>
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