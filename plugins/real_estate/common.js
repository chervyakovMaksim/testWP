document.addEventListener('DOMContentLoaded', function () {
    var filterForm = document.getElementById('real-estate-filter-form');

    if (filterForm) {
        filterForm.addEventListener('submit', handleFilterSubmit);
    }

    function handleFilterSubmit(e) {
        e.preventDefault();
        sendAjaxRequest();
    }

    function sendAjaxRequest(page = 1) {
        var formData = new FormData(filterForm);
        formData.append('action', 'real_estate_filter');
        formData.append('nonce', realEstateFilter.nonce);
        formData.append('page', page);

        fetch(realEstateFilter.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            var resultsElement = document.getElementById('real-estate-filter-results');
            if (resultsElement) {
                resultsElement.innerHTML = data;
                var paginationLinks = document.querySelectorAll('#real-estate-filter-results a.page-numbers');
                paginationLinks.forEach(function (link) {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        // var page = this.getAttribute('href').match(/\/page\/(\d+)/)[1];
                        // sendAjaxRequest(page);
                    });
                });
            }
        })
        .catch(error => console.error('Ошибка:', error));
    }

    document.body.addEventListener('click', function(e) {
        if (e.target.matches('#real-estate-filter-results a.page-numbers')) {
            e.preventDefault();
            var pageMatch = e.target.getAttribute('href').match(/\/page\/(\d+)/);
            var page = pageMatch ? pageMatch[1] : 1;
            sendAjaxRequest(page);
        }
    });
    
});



