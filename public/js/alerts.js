function showLoading() {
    Swal.fire({
        didOpen: () => {
            Swal.showLoading();
        },
        background: "transparent",
        backdrop: 'rgba(0,0,0,0.7)',
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log("I was closed by the timer");
        }
    });
}

function hideLoading() {
    Swal.close();
}

function showToast(icon = 'success', message) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: icon,
        title: message,
    });
}