<div wire:ignore.self class="modal fade" id="addUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">User Creator</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form>
             <div class="form-row">
                <div class="col-md-12 mb-3">
                    <label>First Name</label>
                    <input wire:model.lazy="first_name"  type="text" class="form-control @if($errors->has('first_name')) is-invalid @endif" required>
                    @error('first_name') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 mb-3">
                    <label>Last Name</label>
                    <input wire:model.lazy="last_name"  type="text" class="form-control @if($errors->has('last_name')) is-invalid @endif" required>
                    @error('last_name') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 mb-3">
                    <label>Email</label>
                    <input wire:model.lazy="email"  type="email" class="form-control @if($errors->has('email')) is-invalid @endif" required>
                    @error('email') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 mb-3">
                    <label>Password</label>
                    <input wire:model.lazy="password"  type="password" class="form-control @if($errors->has('password')) is-invalid @endif" required>
                    @error('password') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 mb-3">
                    <label>Role</label>
                    <select wire:model.lazy="role" wire:change="changeMun($event.target.value)" class="form-control @if($errors->has('role')) is-invalid @endif " required>
                        <option value="">Select a role here ...</option>
                        @if(!empty($listOfRoles))
                            @foreach($listOfRoles as $role) 
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('role') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
            @if($showMunicipality)
                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <label>Municipality</label>
                        <select id="multi-municipality" class="selectpicker form-control" multiple data-live-search="true">
                            <option value="">Select a municipality here ...</option>
                            @if(!empty($listOfMunicipality))
                                @foreach($listOfMunicipality as $mun) 
                                    <option value="{{ $mun->municipality_code_number }}">{{ $mun->municipality_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            @endif
          </form>
      </div>
      <div class="modal-footer">
        <button wire:click="resetInputs" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button wire:click="store" wire:loading.attr="disabled" type="button" class="btn btn-success"><div wire:loading wire:target="store"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>CREATE</button>
      </div>
    </div>
  </div>
</div>