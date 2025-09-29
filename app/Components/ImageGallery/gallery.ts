import PhotoSwipeLightbox from 'photoswipe/dist/photoswipe-lightbox.esm.js';
import PhotoSwipe from 'photoswipe/dist/photoswipe.esm.js';
import 'photoswipe/dist/photoswipe.css';

const lightbox = new PhotoSwipeLightbox({
    gallery: '.pswp-gallery',
    children: 'a',
    pswpModule: PhotoSwipe,
    bgOpacity: 1,
});
window.addEventListener('DOMContentLoaded', () => lightbox.init());

window.addEventListener('DOMContentLoaded', () => {
    const galleryTrigger = (dataSource: any[], index: number) => {
        const lightbox = new PhotoSwipeLightbox({
            dataSource,
            pswpModule: PhotoSwipe,
            bgOpacity: 1,
        });
        lightbox.init();
        lightbox.loadAndOpen(index);
    };

    const a = document.querySelectorAll('.gallery-one-line a');
    a.forEach(item => {
        item.addEventListener('click', e => {
            e.preventDefault();
            const index = Number(item.getAttribute('data-index'));
            console.log(item.closest(".gallery-one-line").getAttribute('data-data-source'));
            const data = JSON.parse(item.closest(".gallery-one-line").getAttribute('data-data-source'));
            galleryTrigger(data, index);
        });
    });
});
