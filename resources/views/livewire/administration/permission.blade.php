<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
    @include('livewire.administration.permission_add')
    @include('livewire.administration.permission_edit')
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">List of User Permissions</h4>
            
            <div class="row">	
	    	    <div class="col-5">
	                <div class="input-group">
                      <input type="text" class="form-control" placeholder="Search for permissions here ..." aria-label="Search for users here ...">
                      <div class="input-group-append">
                        <button class="btn btn-sm btn-primary" type="button">Search</button>
                      </div>
                    </div>		                
	            </div>
	            <div class="col-5">&nbsp;</div>
	            <div class="col-2" style="text-align: right;">
	                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPerm"><i class="mdi mdi-plus-circle-outline"></i>&nbsp;Add</button>
	            </div>
	        </div>
            
          <div class="table-responsive pt-3">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 50px; text-align: center;">#</th>
                  <th style="text-align: center;">Permission Name</th>
                  <th style="width: 100px; text-align: center;" nowrap>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($permissions as $perm)
                <tr>
                  <td>{{ (($permissions->currentPage() * $permissions->perPage()) - $permissions->perPage()) + $loop->iteration  }}</td>
                  <td>{{ $perm->name }}</td>
                  <td style="text-align: center;">
                    <button wire:click="edit({{ $perm->id }})" type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editPermission"><i class="mdi mdi-lead-pencil"></i>&nbsp;Edit</button>
                    <button type="button" class="btn btn-sm btn-danger"><i class="mdi mdi-delete-sweep"></i>&nbsp;Delete</button>
                  </td>
                </tr>
                @empty
                    <tr><td colspan="3">No permissions yet ...</td></tr>
                @endforelse
              </tbody>
            </table>
            <br/>
            {!! $permissions->links() !!}
          </div>
        </div>
      </div>
    </div>
</div>