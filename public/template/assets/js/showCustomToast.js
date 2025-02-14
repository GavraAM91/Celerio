function showToast(message, type) {
    var bgColor = type === "success" ? "bg-success" : "bg-danger";
    var toastHTML = `
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
            <div class="toast align-items-center text-white ${bgColor} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML("beforeend", toastHTML);
    var toastEl = document.querySelector(".toast:last-child");
    var toast = new bootstrap.Toast(toastEl);
    toast.show();

    setTimeout(() => {
        toastEl.remove();
    }, 4000);
}