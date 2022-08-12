<form wire:submit.prevent="submit">
    <div class="form-row">
        <div class="col-md-4 mb-3">
            <label>Old Password</label>
            <input wire:model.lazy="old_password" wire:keydown.enter="update" type="password" class="form-control @if($errors->has('old_password')) is-invalid @endif" required>
            @error('old_password') <span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-4 mb-3">
            <label>New Password</label>
            <input wire:model.lazy="password" wire:keydown.enter="update" type="password" class="form-control @if($errors->has('password')) is-invalid @endif" required>
            @error('password') <span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-4 mb-3">
            <label>Confirm New Password</label>
            <input wire:model.lazy="password_confirmation" wire:keydown.enter="update" type="password" class="form-control @if($errors->has('password_confirmation')) is-invalid @endif" required>
            @error('password_confirmation') <span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
    </div>
    <button wire:click.prevent="update" wire:loading.attr="disabled" type="button" class="btn btn-success px-4"><div wire:loading wire:target="update"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div> Change Now</button>
</form>