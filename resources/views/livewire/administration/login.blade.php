<div>
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5" style="border: 1px dashed #999;">
              <div class="brand-logo" style="text-align: center">
                <img src="{{ asset('images/logo.png') }}" style="width:30%; height:30%;" alt="logo">
              </div>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <form class="pt-3">
                <div class="form-group">
                  <input wire:model="email" wire:keydown.enter="authenticate" type="email" class="form-control form-control-lg @if($errors->has('email')) is-invalid @endif" placeholder="Email" required>
                  @error('email') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                  <input wire:model="password" wire:keydown.enter="authenticate" type="password" class="form-control form-control-lg @if($errors->has('password')) is-invalid @endif" placeholder="Password" required>
                  @error('password') <span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="mt-3">
                  <button wire:click.prevent="authenticate" wire:loading.attr="disabled" type="button" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn"><div wire:loading wire:target="authenticate"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>SIGN IN</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>