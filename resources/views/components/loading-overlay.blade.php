{{-- Loading Overlay Component --}}
{{-- Usage: Include in form pages, then call showLoading() on form submit --}}

<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center" role="alert" aria-live="assertive">
    <div class="bg-white rounded-lg p-8 shadow-xl text-center max-w-sm mx-4">
        <div class="animate-spin rounded-full h-16 w-16 border-4 border-blue-200 border-t-blue-600 mx-auto mb-4"></div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Memproses...</h3>
        <p class="text-gray-600 text-sm" id="loading-message">Mohon tunggu, permintaan Anda sedang diproses.</p>
    </div>
</div>

<script>
    function showLoading(message = null) {
        const overlay = document.getElementById('loading-overlay');
        const messageEl = document.getElementById('loading-message');
        
        if (message) {
            messageEl.textContent = message;
        }
        
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        
        // Prevent scrolling
        document.body.style.overflow = 'hidden';
    }
    
    function hideLoading() {
        const overlay = document.getElementById('loading-overlay');
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
        
        // Restore scrolling
        document.body.style.overflow = '';
    }
    
    // Auto-show loading on form submit
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form[data-loading]');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const message = this.dataset.loadingMessage || null;
                showLoading(message);
            });
        });
    });
</script>
