{{--
Toast Notifications Component
Include at the bottom of layout before </body>
Usage: @include('components.toast')

JavaScript API:
showToast('Success!', 'success');
showToast('Error!', 'error');
showToast('Warning!', 'warning');
showToast('Info message', 'info');
--}}

<div id="toast-container" class="fixed bottom-4 right-4 z-50 flex flex-col gap-2" aria-live="polite" aria-atomic="true"></div>

<script>
function showToast(message, type = 'info', duration = 5000) {
    const container = document.getElementById('toast-container');
    const toastId = 'toast-' + Date.now();
    
    const configs = {
        success: {
            bg: 'bg-green-500',
            icon: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'
        },
        error: {
            bg: 'bg-red-500',
            icon: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>'
        },
        warning: {
            bg: 'bg-yellow-500',
            icon: '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>'
        },
        info: {
            bg: 'bg-blue-500',
            icon: '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>'
        }
    };
    
    const config = configs[type] || configs.info;
    
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `${config.bg} text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 min-w-[300px] max-w-md transform transition-all duration-300 translate-x-full opacity-0`;
    toast.setAttribute('role', 'alert');
    
    toast.innerHTML = `
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">${config.icon}</svg>
        <p class="flex-1 text-sm font-medium">${escapeHtml(message)}</p>
        <button onclick="dismissToast('${toastId}')" class="flex-shrink-0 hover:opacity-75 focus:outline-none" aria-label="Tutup">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Animate in
    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    });
    
    // Auto dismiss
    if (duration > 0) {
        setTimeout(() => dismissToast(toastId), duration);
    }
    
    return toastId;
}

function dismissToast(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Show flash messages from session
document.addEventListener('DOMContentLoaded', function() {
    @if(session('toast_success'))
        showToast(@json(session('toast_success')), 'success');
    @endif
    @if(session('toast_error'))
        showToast(@json(session('toast_error')), 'error');
    @endif
    @if(session('toast_warning'))
        showToast(@json(session('toast_warning')), 'warning');
    @endif
    @if(session('toast_info'))
        showToast(@json(session('toast_info')), 'info');
    @endif
});
</script>
