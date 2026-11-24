@php
    $editorId = $editorId ?? 'articleEditor';
    $inputId = $inputId ?? 'articleContent';
    $contentValue = old('content', $content ?? '');
@endphp

<style>
.article-editor-toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: .35rem;
    padding: .6rem;
    border: 1px solid #dee2e6;
    border-bottom: 0;
    border-radius: .375rem .375rem 0 0;
    background: #f8f9fa;
}
.article-editor-toolbar .btn {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}
.article-editor {
    min-height: 360px;
    max-height: 680px;
    overflow-y: auto;
    padding: 1rem;
    border: 1px solid #dee2e6;
    border-radius: 0 0 .375rem .375rem;
    background: #fff;
    line-height: 1.8;
}
.article-editor:focus {
    border-color: #198754;
    box-shadow: 0 0 0 .2rem rgba(25, 135, 84, .15);
    outline: 0;
}
.article-editor blockquote {
    border-left: 4px solid #198754;
    margin: 1rem 0;
    padding: .5rem 1rem;
    background: #f6fbf8;
}
.article-editor img {
    max-width: 100%;
    height: auto;
    border-radius: .375rem;
}
</style>

<div class="article-editor-toolbar" data-editor-toolbar="{{ $editorId }}" aria-label="Article editor toolbar">
    <button type="button" class="btn btn-sm btn-outline-secondary" data-command="formatBlock" data-value="h2" title="Heading">
        <i class="fas fa-heading"></i>
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary" data-command="formatBlock" data-value="p" title="Paragraph">
        <i class="fas fa-paragraph"></i>
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary" data-command="bold" title="Bold">
        <i class="fas fa-bold"></i>
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary" data-command="italic" title="Italic">
        <i class="fas fa-italic"></i>
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary" data-command="underline" title="Underline">
        <i class="fas fa-underline"></i>
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary" data-command="insertUnorderedList" title="Bullet list">
        <i class="fas fa-list-ul"></i>
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary" data-command="insertOrderedList" title="Numbered list">
        <i class="fas fa-list-ol"></i>
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary" data-command="formatBlock" data-value="blockquote" title="Quote">
        <i class="fas fa-quote-right"></i>
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary" data-action="link" title="Insert link">
        <i class="fas fa-link"></i>
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary" data-action="image" title="Insert image URL">
        <i class="fas fa-image"></i>
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary" data-command="removeFormat" title="Clear formatting">
        <i class="fas fa-eraser"></i>
    </button>
</div>
<div id="{{ $editorId }}" class="article-editor" contenteditable="true">{!! $contentValue !!}</div>
<textarea id="{{ $inputId }}" name="content" class="d-none @error('content') is-invalid @enderror" required>{{ $contentValue }}</textarea>
@error('content') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
<div class="form-text">Use the toolbar for headings, lists, quotes, links, and images. Formatting is saved as article HTML.</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const editor = document.getElementById(@json($editorId));
    const input = document.getElementById(@json($inputId));
    const toolbar = document.querySelector('[data-editor-toolbar="' + editor.id + '"]');
    if (!editor || !input || !toolbar) return;

    const syncContent = () => {
        input.value = editor.innerHTML.trim();
    };

    toolbar.addEventListener('click', (event) => {
        const button = event.target.closest('button');
        if (!button) return;
        event.preventDefault();
        editor.focus();

        if (button.dataset.action === 'link') {
            const url = prompt('Enter link URL');
            if (url) document.execCommand('createLink', false, url);
        } else if (button.dataset.action === 'image') {
            const url = prompt('Enter image URL');
            if (url) document.execCommand('insertImage', false, url);
        } else if (button.dataset.command) {
            document.execCommand(button.dataset.command, false, button.dataset.value || null);
        }

        syncContent();
    });

    editor.addEventListener('input', syncContent);
    editor.closest('form')?.addEventListener('submit', syncContent);
});
</script>
