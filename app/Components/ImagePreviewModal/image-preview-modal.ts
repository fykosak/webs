import * as bootstrap from 'bootstrap';

document.addEventListener("DOMContentLoaded", function () {
    const imageModal = new bootstrap.Modal('#imagePreviewModal');

    if (!imageModal) {
        return;
    }

    let figures = document.querySelectorAll("figure img");
    figures.forEach(function (img: HTMLImageElement) {
        img.style.cursor = "pointer";
        img.addEventListener("click", function () {
            let modalImageElement = document.getElementById("modalImage");
            let modalCaptionElement = document.getElementById("modalCaption");

            modalImageElement.setAttribute('src', img.src);
            modalCaptionElement.innerHTML = img.alt;

            imageModal.show();
        });
    });
});
