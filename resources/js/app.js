// Entry point for application scripts.
// Livewire handles most interactivity via its own script injected by Blade directives.

import 'trix';
import 'trix/dist/trix.css';

// Sync Trix editor content back to its hidden input so Livewire (wire:model) picks it up.
document.addEventListener('trix-change', (event) => {
    const input = event.target.inputElement;
    if (input) {
        input.dispatchEvent(new Event('input', { bubbles: true }));
    }
});
