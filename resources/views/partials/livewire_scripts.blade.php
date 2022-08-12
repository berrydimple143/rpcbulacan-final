<script type="text/javascript">   
    document.addEventListener('livewire:load', function () {
        
        function toastMessage(msg, icn) {
            var toastMixin = Swal.mixin({
                toast: true,
                icon: icn,
                title: 'General Title',
                animation: false,
                position: 'top-right',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });       
            toastMixin.fire({
                animation: true,
                title: msg
            });  
        }
        
        Livewire.on('userCreated', () => {
            toastMessage("User registration successful.", 'success');
        });
        
        Livewire.on('userFailed', errmsg => {
            swal("Oooppsss!", errmsg, "error");           
        });
    });
</script>