function catalogViews(offset, limit = 81) {
    offset -= 1;
    limit = 81;
    $("#pagination").html("");
    $.ajax({
        url: host + "controller/CatalogAjaxController.php",
        type: 'POST',
        data: {
            action: "catalog",
            limit: limit,
            offset: offset,
        },
        dataType: 'html',
        success: function (response) {
            $('#catalog').html(response);
        },
        error: function (xhr, status, error) {
            console.error('Une erreur s\'est produite lors du chargement du contenu.');
        }
    });
}

function catalogFiltre(filtre, offset = 1, limit = 81){
    
    offset -= 1;    
    offset *= 81;

    $("#pagination").html("");
    $.ajax({
        url: host + "controller/CatalogAjaxController.php", 
        type: 'POST',
        data: {
            action: "filtre",
            filtre: filtre,
            limit: limit,
            offset: offset,
        },
        dataType: 'html',
        success: function(response) {
            $('#catalog').html(response);
            ftrSize();
        },
        error: function(xhr, status, error) {
            console.error('Une erreur s\'est produite lors du chargement du contenu.');
        }
    });
}

function pagination(nbElement) {
    var urlParams = new URLSearchParams(window.location.search);
    var filtre = urlParams.get("titre");
    var page = urlParams.get("page");
    $.ajax({
        url: host + "controller/CatalogAjaxController.php", 
        type: 'POST',
        data: {
            action: "pagination",
            nbElement: nbElement,
            page: page,
            filtre: filtre,
        },
        dataType: 'html',
        success: function(response) {
            $('#pagination').html(response);
            ftrSize();
        },
        error: function(xhr, status, error) {
            console.error('Une erreur s\'est produite lors du chargement du contenu.');
        }
    });
}

$(document).ready(function () {
    ftrSize();
    var urlParams = new URLSearchParams(window.location.search);
    var titre = urlParams.get("titre");
    var page = urlParams.get("page");
    if (titre) {
        if(page == null){
            page = 1;
        }
        catalogFiltre(titre, page);
    }

    $("#rechercherCategorie").on("input", function (event) {
        var searchTerm = $(this).val();
        $("#pagination").html("");
        $("#catalog").html("");
        if (searchTerm === "") {
            catalogViews();
            removeGetParameter("titre");
            removeGetParameter("page");
        } else {
            page = 1;
            removeGetParameter("page");
            catalogFiltre(searchTerm, page);
            updateURL(searchTerm);
        }
        ftrSize();
    });

    function updateURL(searchTerm) {
        var urlParams = new URLSearchParams(window.location.search);
        urlParams.set("titre", searchTerm);
        var newURL = window.location.pathname + "?titre=" + searchTerm;
        urlParams.forEach(function (value, key) {
            if (key !== "titre") {
                newURL += "&" + key + "=" + value;
            }
        });
        window.history.replaceState(null, "", newURL);
    }


    function removeGetParameter(get) {
        var urlParams = new URLSearchParams(window.location.search);
        urlParams.delete(get);
        if (urlParams.toString() === "") {
            window.history.replaceState(null, "", window.location.pathname);
        } else {
            var newURL = window.location.pathname + "?" + urlParams.toString();
            window.history.replaceState(null, "", newURL);
        }
    }
});


