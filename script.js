document.addEventListener('DOMContentLoaded', function() {
    var serviceModal = document.getElementById('serviceModal');
    serviceModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var title = button.getAttribute('data-service-title');
        var description = button.getAttribute('data-service-description');

        var modalTitle = serviceModal.querySelector('#modal-service-title');
        var modalBodyDescription = serviceModal.querySelector('#modal-service-description');

        modalTitle.textContent = title;
        modalBodyDescription.textContent = description;
    });
});