<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 2000;">
    @if(session('success'))
        <div class="toast align-items-center text-bg-success border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3500">
            <div class="d-flex">
                <div class="toast-body">{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="toast align-items-center text-bg-danger border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3500">
            <div class="d-flex">
                <div class="toast-body">{{ session('error') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="toast align-items-center text-bg-warning border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body">
                    {{ $errors->first() }}
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toast').forEach(function (toastEl) {
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        });
    });
</script>

