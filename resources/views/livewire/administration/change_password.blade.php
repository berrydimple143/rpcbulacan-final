<div wire:ignore.self class="modal fade" id="changePassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form>
             <div class="form-row">
                <div class="col-md-12 mb-3">
                    <label>New Password</label>
                    <input wire:model.lazy="new_password"  type="password" class="form-control @if($errors->has('new_password')) is-invalid @endif" required>
                    @error('new_password') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button wire:click="resetInputs" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button wire:click="changeNow" wire:loading.attr="disabled" type="button" class="btn btn-success"><div wire:loading wire:target="changeNow"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>SAVE CHANGES</button>
      </div>
    </div>
  </div>
</div>