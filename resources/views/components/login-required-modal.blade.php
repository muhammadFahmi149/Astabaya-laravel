<!-- Login Required Modal Component -->
<div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginRequiredModalLabel">Login Diperlukan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p id="login-required-message">
                    @if(isset($message))
                        {{ $message }}
                    @else
                        <span id="login-item-name"></span>
                    @endif
                </p>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Global function to show login required modal
    function showLoginRequiredModal(itemName, customMessage) {
        const modal = document.getElementById('loginRequiredModal');
        if (!modal) {
            console.error('Login required modal not found');
            // Fallback: redirect to login page
            window.location.href = '{{ route("login") }}';
            return;
        }

        const messageElement = document.getElementById('login-required-message');
        const itemNameElement = document.getElementById('login-item-name');
        
        if (customMessage) {
            messageElement.textContent = customMessage;
        } else if (itemNameElement) {
            messageElement.innerHTML = 'Ingin mengakses <span id="login-item-name">' + itemName + '</span>? Silakan login terlebih dahulu.';
        } else {
            messageElement.textContent = 'Silakan login terlebih dahulu untuk mengakses fitur ini.';
        }

        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    }

    // Make function globally available
    window.showLoginRequiredModal = showLoginRequiredModal;
</script>

