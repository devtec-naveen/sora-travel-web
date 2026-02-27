<div>
    <div>
        <textarea id="{{ $id }}" data-model="{{ $model }}" class="form-control summernote-editor">{!! $value !!}</textarea>
    </div>
    @push('scripts')
        <script>
            function initSummernotes() {
                $('.summernote-editor').each(function() {
                    let editor = $(this);
                    if (editor.next('.note-editor').length) return;
                    let modelName = editor.data('model');
                    editor.summernote({
                        height: 300,
                        callbacks: {
                            onChange: function(contents) {

                                let componentEl = editor.closest('[wire\\:id]');
                                if (!componentEl) return;

                                let componentId = componentEl.getAttribute('wire:id');

                                Livewire.find(componentId).set(modelName, contents);
                            }
                        }
                    });
                });
            }
            document.addEventListener('livewire:init', initSummernotes);
            document.addEventListener('livewire:navigated', initSummernotes);
        </script>
    @endpush
</div>
