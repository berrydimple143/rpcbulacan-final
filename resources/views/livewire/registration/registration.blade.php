<div>
    <img src="{{ asset('images/logo.jpg') }}" width="100%">
    <div class="register">
        <div class="row">
            <div class="col-md-3 register-left">
                <img src="{{ asset('images/logo.png') }}" alt=""/>
                <h3>Welcome!</h3>
                <p>Please enter your exact personal details as required in the form.</p>
            </div>
            <div class="col-md-9 register-right">
                <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                    <li class="nav-item" style="color: #fff;">{{ $datenow }}</li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <h3 class="register-heading">Register Here</h3>
                        <form>
                            <div class="row register-form">
                                <div class="col-md-6">
                                    <input wire:model.lazy="id_number" type="hidden" name="id_number" id="id_number">
                                    <div class="form-group">
                                        <label>First Name:</label>
                                        <input wire:model.lazy="first_name" type="text" class="form-control @if($errors->has('first_name')) is-invalid @endif" {!! $uppercase !!} required>
                                        @error('first_name') <span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name:</label>
                                        <input wire:model.lazy="last_name" type="text" class="form-control @if($errors->has('last_name')) is-invalid @endif" {!! $uppercase !!} required>
                                        @error('last_name') <span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Birth Year: (ex. 1999)(Optional)</label>
                                        <input wire:model.lazy="birth_year" type="text" maxlength="4" class="form-control" {!! $uppercase !!}>
                                    </div>
                                    <div class="form-group">
                                        <label>Gender:</label>
                                        <select wire:model="gender" class="form-control" {!! $uppercase !!}>
                                            <option>Select a gender here...</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>FB Account (Optional):</label>
                                        <input wire:model.lazy="fb_url" type="url" class="form-control @if($errors->has('fb_url')) is-invalid @endif" {!! $uppercase !!} required>
                                        @error('fb_url') <span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Province:</label>
                                        <input wire:model.lazy="province" type="text" class="form-control" {!! $uppercase !!} readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Municipality:</label>
                                        <select wire:model="municipality" class="form-control @if($errors->has('municipality')) is-invalid @endif" {!! $uppercase !!} required>
                                            <option>Select a Municipality here...</option>
                                            @if(!empty($listOfMunicipality))
                                                @foreach($listOfMunicipality as $mun)
                                                    <option value="{{ $mun->municipality_code_number }}">{{ $mun->municipality_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('municipality') <span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Barangay:</label>
                                        <select wire:model="barangay" wire:change="getCode($event.target.value)" class="form-control @if($errors->has('barangay')) is-invalid @endif" {!! $uppercase !!} required>
                                            <option>Select a Barangay here...</option>
                                            @if(!empty($listOfBarangay))
                                                @foreach($listOfBarangay as $barangay)
                                                    <option value="{{ $barangay->id  }}">{{ $barangay->barangay_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('barangay') <span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="form-group">
                                        <label>District No.:</label>
                                        <input wire:model.lazy="district_no" type="text" class="form-control" {!! $uppercase !!} readonly>
                                    </div>
                                    <button wire:click.prevent="store" wire:loading.attr="disabled" type="button" class="btnRegister"><div wire:loading wire:target="store"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>Register</button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-1">&nbsp;</div>
                            <div class="col-md-11">
                                <div class="disclaimer" style="border: 1px dashed #aaa; margin: -50px 10px 10px 10px; padding: 10px;">
                                    <p>Disclaimer:</p>
                                    <p>Nauunawaan ko ang sinagutan kong online registration na ito; na ako ay pumapayag na maging kabalikat sa kampanya nina Leni Robredo at kaniyang mga kasama; na ako ay sang-ayon na gamitin ang aking mga personal na impormasyon, kung kinakailangan at naaayon sa batas pang-eleksyon.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
</div>