<div wire:ignore.self class="modal fade" id="editUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">User Editor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form>
             <input type="hidden" id="municipality-value" value="">
             <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label>First Name</label>
                    <input wire:model.lazy="first_name"  type="text" class="form-control @if($errors->has('first_name')) is-invalid @endif" required>
                    @error('first_name') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Last Name</label>
                    <input wire:model.lazy="last_name"  type="text" class="form-control @if($errors->has('last_name')) is-invalid @endif" required>
                    @error('last_name') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input wire:model.lazy="email"  type="email" class="form-control @if($errors->has('email')) is-invalid @endif" required>
                    @error('email') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Role</label>
                    <select wire:model.lazy="role" wire:change="changeMun2($event.target.value)" class="form-control @if($errors->has('role')) is-invalid @endif " required>
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
            @if($showMunicipality2)
                <div class="form-row">   
                    <div class="col-md-12 mb-3">Municipality</div>
                </div>
                <div class="form-row" style="padding-left: 20px;">
                    @if(!empty($listOfMunicipality))
                        @foreach($listOfMunicipality as $mun)
                            <?php
                                $check = "";
                                if(in_array($mun->municipality_code_number, $selectedMunicipalities)) {
                                    $check = "checked";
                                }
                            ?>
                            <div class="col-md-3 mb-3">   
                                <div class="form-check form-check-primary">
                                  <input wire.model="selectedMunicipalities" wire:click="$emit('municipalitiesClicked', {{ $mun->municipality_code_number }})" class="form-check-input" type="checkbox" name="muns{{ $mun->id }}" id="muns{{ $mun->municipality_code_number }}" value="{{ $mun->municipality_code_number }}" {{ $check }}>
                                  <label class="form-check-label" for="muns{{ $mun->municipality_code_number }}">
                                    {{ $mun->municipality_name }}
                                  </label>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endif
          </form>
      </div>
      <div class="modal-footer">
        <button wire:click="resetInputs" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button wire:click="update" wire:loading.attr="disabled" type="button" class="btn btn-success"><div wire:loading wire:target="update"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>SAVE CHANGES</button>
      </div>
    </div>
  </div>
</div>