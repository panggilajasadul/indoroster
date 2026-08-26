document.addEventListener('DOMContentLoaded', () => {
    initTrixAlignment();
});

document.addEventListener('trix-before-initialize', (event) => {
    if (typeof Trix !== 'undefined' && Trix.config && Trix.config.blockAttributes) {
        Trix.config.blockAttributes.alignLeft = {
            tagName: 'div',
            className: 'text-left',
            terminal: true,
            breakOnReturn: true,
            group: false
        };
        Trix.config.blockAttributes.alignCenter = {
            tagName: 'div',
            className: 'text-center',
            terminal: true,
            breakOnReturn: true,
            group: false
        };
        Trix.config.blockAttributes.alignRight = {
            tagName: 'div',
            className: 'text-right',
            terminal: true,
            breakOnReturn: true,
            group: false
        };
        Trix.config.blockAttributes.alignJustify = {
            tagName: 'div',
            className: 'text-justify',
            terminal: true,
            breakOnReturn: true,
            group: false
        };
    }
});

function initTrixAlignment() {
    if (typeof Trix !== 'undefined' && Trix.config && Trix.config.blockAttributes) {
        Trix.config.blockAttributes.alignLeft = {
            tagName: 'div',
            className: 'text-left',
            terminal: true,
            breakOnReturn: true,
            group: false
        };
        Trix.config.blockAttributes.alignCenter = {
            tagName: 'div',
            className: 'text-center',
            terminal: true,
            breakOnReturn: true,
            group: false
        };
        Trix.config.blockAttributes.alignRight = {
            tagName: 'div',
            className: 'text-right',
            terminal: true,
            breakOnReturn: true,
            group: false
        };
        Trix.config.blockAttributes.alignJustify = {
            tagName: 'div',
            className: 'text-justify',
            terminal: true,
            breakOnReturn: true,
            group: false
        };
    }

    const observer = new MutationObserver((mutations) => {
        document.querySelectorAll('trix-toolbar').forEach(addAlignButtons);
    });

    observer.observe(document.body, { childList: true, subtree: true });
    document.querySelectorAll('trix-toolbar').forEach(addAlignButtons);
}

function addAlignButtons(toolbar) {
    if (toolbar.querySelector('[data-trix-custom-align]')) return;

    const buttonRow = toolbar.querySelector('.flex') || toolbar;
    if (!buttonRow) return;

    const alignGroup = document.createElement('div');
    alignGroup.setAttribute('data-trix-custom-align', 'true');
    alignGroup.className = 'flex items-center gap-x-1 border-r border-gray-200 dark:border-white/10 pr-2 mr-2';

    alignGroup.innerHTML = `
        <button type="button" class="fi-fo-rich-editor-toolbar-btn rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5 transition" data-trix-attribute="alignLeft" title="Rata Kiri (Align Left)" tabindex="-1">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h10M4 18h14"/></svg>
        </button>
        <button type="button" class="fi-fo-rich-editor-toolbar-btn rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5 transition" data-trix-attribute="alignCenter" title="Rata Tengah (Align Center)" tabindex="-1">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M7 12h10M5 18h14"/></svg>
        </button>
        <button type="button" class="fi-fo-rich-editor-toolbar-btn rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5 transition" data-trix-attribute="alignRight" title="Rata Kanan (Align Right)" tabindex="-1">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M10 12h10M6 18h14"/></svg>
        </button>
        <button type="button" class="fi-fo-rich-editor-toolbar-btn rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5 transition" data-trix-attribute="alignJustify" title="Rata Kiri Kanan (Justify)" tabindex="-1">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    `;

    // Insert before text tools or at beginning of button row
    const firstGroup = buttonRow.querySelector('[data-trix-button-group]');
    if (firstGroup) {
        buttonRow.insertBefore(alignGroup, firstGroup);
    } else {
        buttonRow.appendChild(alignGroup);
    }
}
