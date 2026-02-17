{{-- Session Toast --}}
@if (session()->has('toast'))
    <script>
        if (typeof showToast !== "undefined") {
            showToast["{{ session('toast.type') }}"](
                "{{ session('toast.message') }}", {
                    duration: 5000,
                    position: "top-right",
                    transition: "topBounce",
                    icon: "",
                    sound: true,
                }
            );
        }
    </script>
@endif


{{-- Livewire Toast --}}
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('notify', (event) => {
            if (typeof showToast !== "undefined" && showToast[event.type]) {
                showToast[event.type](event.message, {
                    className: "my-toast",
                    duration: 5000,
                    position: "top-right",
                    transition: "topBounce",
                    icon: "",
                    sound: true,
                });
            }
        });
    });
</script>
