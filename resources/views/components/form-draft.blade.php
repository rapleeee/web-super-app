@props(['formId', 'formName' => 'Form'])

{{-- 
    Form Draft Component
    Usage: 
    <x-form-draft formId="create-unit" formName="Unit Komputer">
        <form id="create-unit" ...>
            ...
        </form>
    </x-form-draft>
--}}

<div x-data="formDraft('{{ $formId }}', '{{ $formName }}')" x-init="init()">
    {{-- Draft Restore Banner --}}
    <div x-show="hasDraft" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="mb-4 bg-amber-50 border border-amber-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-medium text-amber-800">Draft Tersimpan Ditemukan</h4>
                <p class="text-sm text-amber-700 mt-1">
                    Ada draft {{ $formName }} yang tersimpan dari <span x-text="draftTime" class="font-medium"></span>. 
                    Apakah Anda ingin melanjutkan pengisian?
                </p>
                <div class="flex gap-2 mt-3">
                    <button type="button" @click="restoreDraft()" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Pulihkan Draft
                    </button>
                    <button type="button" @click="discardDraft()" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Buang Draft
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Auto-save indicator --}}
    <div x-show="showSaveIndicator" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-4 right-4 bg-gray-800 text-white text-sm px-3 py-2 rounded-lg shadow-lg flex items-center gap-2 z-50">
        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span>Draft tersimpan</span>
    </div>

    {{ $slot }}
</div>

@once
@push('scripts')
<script>
function formDraft(formId, formName) {
    return {
        formId: formId,
        formName: formName,
        hasDraft: false,
        draftTime: '',
        draftData: null,
        showSaveIndicator: false,
        saveTimeout: null,
        form: null,

        init() {
            this.form = document.getElementById(this.formId);
            if (!this.form) {
                console.warn('Form with id "' + this.formId + '" not found');
                return;
            }

            // Check for existing draft
            this.checkForDraft();

            // Setup auto-save on input changes
            this.setupAutoSave();

            // Clear draft on successful form submission
            this.form.addEventListener('submit', () => {
                this.clearDraft();
            });
        },

        getStorageKey() {
            return 'form_draft_' + this.formId;
        },

        checkForDraft() {
            const stored = localStorage.getItem(this.getStorageKey());
            if (stored) {
                try {
                    const data = JSON.parse(stored);
                    // Check if draft is not too old (24 hours)
                    const hoursDiff = (Date.now() - data.timestamp) / (1000 * 60 * 60);
                    if (hoursDiff < 24) {
                        this.hasDraft = true;
                        this.draftData = data.fields;
                        this.draftTime = this.formatTime(data.timestamp);
                    } else {
                        // Draft too old, remove it
                        this.clearDraft();
                    }
                } catch (e) {
                    this.clearDraft();
                }
            }
        },

        formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = now - date;
            
            if (diff < 60000) return 'beberapa detik yang lalu';
            if (diff < 3600000) return Math.floor(diff / 60000) + ' menit yang lalu';
            if (diff < 86400000) return Math.floor(diff / 3600000) + ' jam yang lalu';
            
            return date.toLocaleDateString('id-ID', { 
                day: 'numeric', 
                month: 'short', 
                hour: '2-digit', 
                minute: '2-digit' 
            });
        },

        setupAutoSave() {
            const inputs = this.form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('input', () => this.debouncedSave());
                input.addEventListener('change', () => this.debouncedSave());
            });
        },

        debouncedSave() {
            if (this.saveTimeout) {
                clearTimeout(this.saveTimeout);
            }
            this.saveTimeout = setTimeout(() => {
                this.saveDraft();
            }, 1000); // Save after 1 second of inactivity
        },

        saveDraft() {
            const formData = new FormData(this.form);
            const fields = {};
            let hasData = false;

            formData.forEach((value, key) => {
                // Skip CSRF token and method spoofing
                if (key === '_token' || key === '_method') return;
                if (value && value.toString().trim()) {
                    hasData = true;
                }
                fields[key] = value;
            });

            // Only save if there's actual data
            if (hasData) {
                const data = {
                    timestamp: Date.now(),
                    fields: fields
                };
                localStorage.setItem(this.getStorageKey(), JSON.stringify(data));
                
                // Show save indicator
                this.showSaveIndicator = true;
                setTimeout(() => {
                    this.showSaveIndicator = false;
                }, 2000);
            }
        },

        restoreDraft() {
            if (!this.draftData) return;

            Object.entries(this.draftData).forEach(([key, value]) => {
                const input = this.form.querySelector(`[name="${key}"]`);
                if (input) {
                    if (input.type === 'checkbox') {
                        input.checked = value === 'on' || value === '1' || value === true;
                    } else if (input.type === 'radio') {
                        const radio = this.form.querySelector(`[name="${key}"][value="${value}"]`);
                        if (radio) radio.checked = true;
                    } else {
                        input.value = value;
                        // Trigger change event for dependent fields (like Alpine.js watchers)
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                }
            });

            this.hasDraft = false;
        },

        discardDraft() {
            this.clearDraft();
            this.hasDraft = false;
            this.draftData = null;
        },

        clearDraft() {
            localStorage.removeItem(this.getStorageKey());
        }
    }
}
</script>
@endpush
@endonce
