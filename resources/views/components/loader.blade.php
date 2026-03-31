<div 
    wire:loading.block
    @if($targets) wire:target="{{ $targets }}" @endif
>
    <div class="container_loader _newlognsecv2">
        <span class="loader"></span>

        <div class="loadtxtfl">
            {{ $message }}
        </div>
    </div>
</div>