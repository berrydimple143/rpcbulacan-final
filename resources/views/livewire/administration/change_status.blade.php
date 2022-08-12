<div wire:ignore.self class="modal fade" id="changeStatus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Change Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <p>{{ $statusMsg }}</p>
      </div>
      <div class="modal-footer">
        <button wire:click="resetInputs" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button wire:click="changeStatusNow" wire:loading.attr="disabled" type="button" class="btn btn-{{ $statusClass }}"><div wire:loading wire:target="changeStatusNow"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>{{ $statusBtn }}</button>
      </div>
    </div>
  </div>
</div>