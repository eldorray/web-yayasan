import 'trix';
import 'trix/dist/trix.css';
import './public-motion';

// Sync Trix editor content back to its hidden input so Livewire (wire:model) picks it up.
document.addEventListener('trix-change', (event) => {
    const input = event.target.inputElement;
    if (input) {
        input.dispatchEvent(new Event('input', { bubbles: true }));
    }
});
