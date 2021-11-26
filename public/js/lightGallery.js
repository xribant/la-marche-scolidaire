lightGallery(document.getElementById('imageGallery'), {
    plugins: [lgZoom, lgThumbnail],
    speed: 500,
    autoplayFirstVideo: false,
    pager: false,
    galleryId: "nature",
    mobileSettings: {
        controls: false,
        showCloseIcon: false,
        download: false,
        rotate: false
    }
});
