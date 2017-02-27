

var suggestUrl = $('#object').attr('data-suggestion');
$('#object').typeahead({
    // suggestions pour une saisie d'au minimum 3 caractères
    minLength: 3,

    // nous configurons ici la source distante de données
    source: function(query, process) {
        // "query" est la chaîne de recherche
        // "process" est une closure qui doit recevoir la liste des suggestions à afficher

        var $this = this;
        $.ajax({
            url: suggestUrl,
            type: 'GET',
            data: {
                search: query
            },
            success: function(data) {

                var reversed = {};

                // ici nous générons simplement la liste des suggestions à afficher
                var suggests = [];
                console.log(suggestUrl);
                $.each(data, function(id, elem) {
                    reversed[elem.suggest] = elem;
                    suggests.push(elem.suggest);
                });

                $this.reversed = reversed;

                // affichage des suggestions
                process(suggests);
            }
        })
    },

    // cette méthode est appelée lorsque qu'une suggestion est sélectionnée depuis la liste
    updater: function(item) {

        // nous retrouvons alors les données associées
        var elem = this.reversed[item];

        // puis nous remplissons les champs "codePostal"...
        $('#category').val(elem.category);
        $('#objectId').val(elem.id);

        // ...et "ville" du formulaire
        return elem.object;
    },

    // cette méthode permet de déterminer lesquelles des suggestions sont valides par rapport
    // à la recherche. Nous effectuons déjà tout cela côté serveur, donc ici il suffit de
    // retourner "true"
    matcher: function() {
        return true;
    }
});
