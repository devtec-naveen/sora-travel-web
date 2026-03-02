<div wire:ignore>
    <input type="file" id="drop-{{ $previewId }}" wire:model="image" class="dropify" data-height="200" data-max-file-size="1M"
        @if($currentImage && $folderPath)
            data-default-file="{{ asset('uploads/' . $folderPath . '/' . $currentImage) }}"
        @endif/>
</div>
@push('scripts')
    <script>
        function initimageUploader(){
            let drElement = $('#drop-{{ $previewId }}');
            drElement.dropify({
                messages: {
                    'default': 'Drag & Drop Image Here or Click to Upload',
                    'replace': 'Drag & Drop or Click to Replace',
                    'remove': 'Remove',
                    'error': 'Ooops, something wrong.'
                },
                error: {
                    'fileSize': 'The file size is too big (max).'
                }
            });
    
            let drInstance = drElement.data('dropify');
            Livewire.hook('message.processed', (message, component) => {
                drInstance.destroy();
                drElement.dropify();
            });
            drElement.on('change', function() {});
            drElement.on('dropify.afterClear', function() {});
        }
        document.addEventListener('livewire:init', initimageUploader);
        document.addEventListener('livewire:navigated', initimageUploader);
    </script>
@endpush
