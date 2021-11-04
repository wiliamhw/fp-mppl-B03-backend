<div class="form-group">
    <label >{{ $labelName }}</label>
    <div
        x-data="{ value: @entangle($attributes->wire('model')) }"
        x-init="
            tinymce.init({
                target: $refs.tinymce,
                themes: 'modern',
                height: 400,
                menubar: 'file edit view insert format tools table help',
                plugins: [
                    'advlist anchor autolink charmap code codesample directionality emoticons',
                    'fullscreen help hr image importcss insertdatetime link lists media',
                    'nonbreaking noneditable pagebreak paste preview print quickbars searchreplace',
                    'table template textpattern toc visualblocks visualchars wordcount'
                ],
                toolbar: 'undo redo | bold italic underline strikethrough | formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | removeformat | pagebreak | charmap emoticons | fullscreen preview print | image template link anchor codesample',
                setup: function(editor) {
                    editor.on('blur', function(e) {
                        value = editor.getContent()
                    })

                    editor.on('init', function (e) {
                        if (value != null) {
                            editor.setContent(value)
                        }
                    })

                    function putCursorToEnd() {
                        editor.selection.select(editor.getBody(), true);
                        editor.selection.collapse(false);
                    }

                    $watch('value', function (newValue) {
                        if (newValue !== editor.getContent()) {
                            editor.resetContent(newValue || '');
                            putCursorToEnd();
                        }
                    });
                }
            })
        "
        wire:ignore
    >
        <div>
            <input
                x-ref="tinymce"
                type="textarea"
                {{ $attributes->whereDoesntStartWith('wire:model') }}
            >
        </div>
    </div>
</div>
