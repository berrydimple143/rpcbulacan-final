<div wire:ignore.self class="modal fade" id="editRegistrant" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Registration</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form>
             <div class="form-row">
                <div class="col-md-12 mb-3">
                    <label>First Name:</label>
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
                    <label>FB Account (Optional):</label>
                    <input wire:model.lazy="fb_url"  type="text" class="form-control @if($errors->has('fb_url')) is-invalid @endif" required>
                    @error('fb_url') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 mb-3">
                    <label>Birth Year:</label>
                    <input wire:model.lazy="birth_year_edit" id="birth_year_edit" type="text" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 mb-3">
                    <label>Gender:</label>
                    <select wire:model="gender" class="form-control">
                        <option>Select a gender here...</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button wire:click="resetInputs" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button wire:click="update" type="button" class="btn btn-success"><div wire:loading wire:target="update"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>SAVE CHANGES</button>
      </div>
    </div>
  </div>
</div>