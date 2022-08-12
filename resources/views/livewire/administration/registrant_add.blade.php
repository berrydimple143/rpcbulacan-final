<div wire:ignore.self class="modal fade" id="addRegistrant" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
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
                <div class="col-md-6 mb-3">
                    <label>ID No.:</label>
                    <input wire:model.lazy="id_number"  type="text" class="form-control @if($errors->has('id_number')) is-invalid @endif" readonly>
                    @error('id_number') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Province:</label>
                    <input wire:model.lazy="province"  type="text" class="form-control @if($errors->has('province')) is-invalid @endif" readonly>
                    @error('province') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
             <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label>First Name:</label>
                    <input wire:model.lazy="first_name"  type="text" class="form-control @if($errors->has('first_name')) is-invalid @endif" required>
                    @error('first_name') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Municipality:</label>
                    <select wire:model.lazy="municipality" wire:change="getBarangay($event.target.value)" class="form-control @if($errors->has('municipality')) is-invalid @endif " required>
                        <option value="">Select municipality here ...</option>
                        @if(!empty($listOfMunicipality))
                            @foreach($listOfMunicipality as $mun)
                                <option value="{{ $mun->municipality_code_number }}">{{ $mun->municipality_name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('municipality') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label>Last Name</label>
                    <input wire:model.lazy="last_name"  type="text" class="form-control @if($errors->has('last_name')) is-invalid @endif" required>
                    @error('last_name') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Barangay:</label>
                    <select wire:model.lazy="barangay" wire:change="getCode($event.target.value)" class="form-control @if($errors->has('barangay')) is-invalid @endif " required>
                        <option value="">Select barangay here ...</option>
                        @if(!empty($listOfBarangay))
                            @foreach($listOfBarangay as $barangay)
                                <option value="{{ $barangay->id  }}">{{ $barangay->barangay_name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('barangay') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label>FB Account (Optional):</label>
                    <input wire:model.lazy="fb_url"  type="text" class="form-control @if($errors->has('fb_url')) is-invalid @endif" required>
                    @error('fb_url') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Gender:</label>
                    <select wire:model="gender" class="form-control">
                        <option>Select a gender here...</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label>Birth Year:</label>
                    <input wire:model.lazy="birth_year" id="birth_year" type="text" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label>District No.:</label>
                    <input wire:model.lazy="district_no"  type="text" class="form-control @if($errors->has('district_no')) is-invalid @endif" readonly>
                    @error('district_no') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button wire:click="resetInputs" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button wire:click="resetInputs" type="button" class="btn btn-warning">Clear</button>
        <button wire:click="store" type="button" class="btn btn-success"><div wire:loading wire:target="store"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>CREATE</button>
      </div>
    </div>
  </div>
</div>