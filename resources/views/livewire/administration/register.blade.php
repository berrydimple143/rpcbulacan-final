<div class="container-fluid page-body-wrapper full-page-wrapper">
  <div class="content-wrapper d-flex align-items-center auth px-0">
    <div class="row w-100 mx-0">
      <div class="col-lg-4 mx-auto">
        <div class="auth-form-light text-left py-2 px-4 px-sm-5" style="border: 1px dashed #999;">
          <div class="brand-logo" style="text-align: center">
            <img src="{{ asset('images/logo.png') }}" style="width:30%; height:30%;" alt="logo">
          </div>
          <h6 class="font-weight-light">Please fill-up the form completely.</h6>
          <form class="pt-3">
            <div class="form-group">
              <input wire:model.lazy="first_name" wire:keydown.enter="store" type="text" class="form-control form-control-lg @if($errors->has('first_name')) is-invalid @endif" placeholder="First Name here.." required>
              @error('first_name') <span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
              <input wire:model.lazy="last_name" wire:keydown.enter="store" type="text" class="form-control form-control-lg @if($errors->has('last_name')) is-invalid @endif" placeholder="Last Name here.." required>
              @error('last_name') <span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
              <input wire:model.lazy="email" wire:keydown.enter="store" type="email" class="form-control form-control-lg @if($errors->has('email')) is-invalid @endif" placeholder="Email here.." required>
              @error('email') <span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
              <input wire:model.lazy="password" wire:keydown.enter="store" type="password" class="form-control form-control-lg @if($errors->has('password')) is-invalid @endif" placeholder="Password here .." required>
              @error('password') <span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
              <input wire:model.lazy="password_confirmation" wire:keydown.enter="store" type="password" class="form-control form-control-lg @if($errors->has('password_confirmation')) is-invalid @endif" placeholder="Confirm your password here .." required>
              @error('password_confirmation') <span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="mt-3">
              <button wire:click="store" wire:loading.attr="disabled" type="button" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn"><div wire:loading wire:target="store"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>REGISTER</button>
            </div>
            <div class="text-center mt-4 font-weight-light">
              Already have an account? <a href="{{ route('login') }}" class="text-primary">Login</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>