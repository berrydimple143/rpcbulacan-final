<div wire:ignore.self class="modal fade" id="addPermission" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Permission</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form>
             <div class="form-row">
                <div class="col-md-12 mb-3">
                    <label>Permission</label>
                    <select wire:model.lazy="permission" class="form-control @if($errors->has('permission')) is-invalid @endif " required>
                        <option value="">Select a permission here ...</option>
                        @if(!empty($listOfPermissions))
                            @foreach($listOfPermissions as $perm) 
                                <option value="{{ $perm->name }}">{{ $perm->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('permission') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button wire:click="resetInputs" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button wire:click="savePermission" wire:loading.attr="disabled" type="button" class="btn btn-success"><div wire:loading wire:target="savePermission"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>ADD NOW</button>
      </div>
    </div>
  </div>
</div>