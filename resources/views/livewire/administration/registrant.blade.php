<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
    @include('livewire.administration.registrant_add')
    @include('livewire.administration.registrant_edit')
    @include('livewire.administration.registrant_download')
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">List of Registrants&nbsp;&nbsp;&nbsp;<a href="{{ route('registrants') }}"><button type="button" class="btn btn-sm btn-outline-primary btn-icon-text"><i class="mdi mdi-refresh"></i>&nbsp;Refresh</button></a></h4>
        <div class="row">	
    	    <div class="col-3">
               <input wire:model="search" wire:keydown.enter="searchNow" type="text" class="form-control" placeholder="Search for registrants here ..." aria-label="Search for registrants here ...">
            </div>
            <div class="col-3">
                <select wire:model="municipality2" wire:change="generateData($event.target.value, 'municipality')" class="form-control">
                    <option value=''>Select municipality here ...</option>
                    @if(!empty($listOfMunicipality2))
                        @foreach($listOfMunicipality2 as $mun)
                            <option value="{{ $mun->municipality_code_number }}">{{ $mun->municipality_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-3">
                <select wire:model="barangay2" wire:change="generateData($event.target.value, 'barangay')" class="form-control">
                    <option value=''>Select barangay here ...</option>
                    @if(!empty($listOfBarangay2))
                        @foreach($listOfBarangay2 as $barangay)
                            <option value="{{ $barangay->id  }}">{{ $barangay->barangay_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-3" style="text-align: right;">
                @hasanyrole('admin|site lead|team lead|superadmin')
                <button wire:click="export" type="button" class="btn btn-outline-primary btn-icon-text"><i class="ti-download btn-icon-prepend"></i>Export</button>
                @endhasanyrole
                <button type="button" class="btn btn-outline-primary btn-icon-text" data-toggle="modal" data-target="#addRegistrant"><i class="ti-plus btn-icon-prepend"></i>Add</button>
            </div>
        </div>
          <div class="table-responsive pt-3">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>ID Number</th>
                  <th>Last Name&nbsp;
                    <a wire:click="sortBy('lname', 'asc')" href="#"><i class="ti-arrow-up btn-icon-prepend"></i></a>
                    <a wire:click="sortBy('lname', 'desc')" href="#"><i class="ti-arrow-down btn-icon-prepend"></i></a>
                  </th>
                  <th>First Name&nbsp;
                    <a wire:click="sortBy('fname', 'asc')" href="#"><i class="ti-arrow-up btn-icon-prepend"></i></a>
                    <a wire:click="sortBy('fname', 'desc')" href="#"><i class="ti-arrow-down btn-icon-prepend"></i></a>
                  </th>
                  <th>Birth Year</th>
                  <th>Province</th>
                  <th>Municipality&nbsp;
                    <a wire:click="sortBy('mun', 'asc')" href="#"><i class="ti-arrow-up btn-icon-prepend"></i></a>
                    <a wire:click="sortBy('mun', 'desc')" href="#"><i class="ti-arrow-down btn-icon-prepend"></i></a>
                  </th>
                  <th>Barangay&nbsp;
                    <a wire:click="sortBy('bar', 'asc')" href="#"><i class="ti-arrow-up btn-icon-prepend"></i></a>
                    <a wire:click="sortBy('bar', 'desc')" href="#"><i class="ti-arrow-down btn-icon-prepend"></i></a>
                  </th>
                  <th style="width: 100px;" wrap>FB Account</th>
                  @hasanyrole('admin|superadmin')
                    <th style="width: 100px; text-align: center;" nowrap>Action</th>
                  @endhasanyrole
                </tr>
              </thead>
              <tbody>
                @forelse($users as $user)
                <tr>
                  <td>{{ (($users->currentPage() * $users->perPage()) - $users->perPage()) + $loop->iteration  }}</td>
                  <td>{{ $user->id_number }}</td>
                  <td>{{ strtoupper($user->last_name) }}</td>
                  <td>{{ strtoupper($user->first_name) }}</td>
                  <td>{{ strtoupper($user->birth_year) }}</td>
                  <td>{{ strtoupper($user->province_name) }}</td>
                  <td>{{ strtoupper(App\Http\Controllers\HelperController::getFieldValue('Municipality', 'municipality_name', $user->municipality)) }}</td>
                  <td>{{ strtoupper(App\Http\Controllers\HelperController::getFieldValue('Barangay', 'barangay_name', $user->barangay)) }}</td>
                  <td wrap>{{ strtoupper($user->fb_url) }}</td>
                  @hasanyrole('admin|superadmin')
                  <td>
                    <button wire:click="downloadId({{ $user->id }})" type="button" class="btn btn-sm btn-icon-text" style="background-color: #FE51B5; color: #fff;" data-toggle="modal" data-target="#downloadDigitalID"><i class="ti-download btn-icon-prepend"></i>Download ID</button>
                    <button wire:click="edit({{ $user->id }})" type="button" class="btn btn-sm btn-icon-text" style="background-color: #FE51B5; color: #fff;" data-toggle="modal" data-target="#editRegistrant"><i class="ti-pencil btn-icon-prepend"></i>Edit</button>
                    <button wire:click="deleteThisId({{ $user->id }})" type="button" class="btn btn-sm btn-icon-text" style="background-color: #972969; color: #fff;" data-toggle="modal" data-target="#deleteRegistrant"><i class="ti-trash btn-icon-prepend"></i>Delete</button>
                  </td>
                  @endhasanyrole
                </tr>
                @empty
                    @hasanyrole('admin|superadmin')
                        <tr><td colspan="10">No registrants yet ...</td></tr>
                    @else
                        <tr><td colspan="9">No registrants yet ...</td></tr>
                    @endhasanyrole
                @endforelse
                @hasanyrole('admin|superadmin')
                    <tr><th colspan="10">Total Number: {{ $noOfReg }}</th></tr>
                @else
                    <tr><th colspan="9">Total Number: {{ $noOfReg }}</th></tr>
                @endhasanyrole
              </tbody>
            </table>
            <br/>
            {!! $users->links() !!}
          </div>
          
          <div wire:ignore.self class="modal fade" id="deleteRegistrant" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
	            <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <h5 class="modal-title" id="deleteModalLabel">Delete Confirm</h5>
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                            <span aria-hidden="true close-btn">×</span>
	                        </button>
	                    </div>
	                    <div class="modal-body">
	                        <p>Are you sure want to delete this registrant?</p>
	                    </div>
	                    <div class="modal-footer">
	                        <button wire:click="" type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
	                        <button type="button" wire:click="deleteNow" class="btn btn-danger" data-dismiss="modal">Yes, Delete it</button>
	                    </div>
	                </div>
	            </div>
	        </div>
          
        </div>
      </div>
    </div>
</div>