@livewireScripts
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('admin/plugins/bootstrap/js/popper.min.js')}}"></script>
<script src="{{asset('admin/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('admin/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('admin/plugins/sidemenu/sidemenu.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/datepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/jquery.maskedinput.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/spectrum.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/form-elements.js')}}"></script>
<script src="{{asset('admin/plugins/sidebar/sidebar.js')}}"></script>
<script src="{{asset('admin/js/sticky.js')}}"></script>
<script src="{{asset('admin/js/custom.js')}}"></script>
<script src="{{asset('admin/js/data-table.js')}}"></script>
<script src="{{asset('admin/js/function.js')}}"></script>
<script src="https://unpkg.com/nextjs-toast-notify@1.47.0/dist/nextjs-toast-notify.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dropify/dist/js/dropify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@include('include.flash-message')
@stack('scripts')
<script>
    // document.addEventListener('livewire:init', () => {
    //     Livewire.hook('request', ({ fail, respond }) => {
    //         fail(({ status, preventDefault }) => {
    //             if (status === 500) {
    //                 preventDefault(); // Stop Livewire's default 419 behavior

    //                 // Use your toast library here to show a message
    //                 // Example with a generic alert, replace with your toast code:
    //                 alert('Your session has expired. Please refresh the page.');

    //                 // Optional: automatically refresh the page for the user
    //                 // window.location.reload(); 
    //             }
    //         });
    //     });
    // });



</script>
</body>
</html>