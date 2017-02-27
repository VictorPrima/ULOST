/**
 $(function () {
        jQuery("#form_ville").ready(function () {
            console.log("jQuery est prêt !");
            $("#ville_name").keyup(function(form) {
                console.log("jQuery est prêt !");

                if ($(this).val().length > 1) {
                    console.log("ajax est prêt");
                    $.ajax({

                        type: 'POST',
                        url: 'http://localhost/symfony/web/app_dev.php/ville/search/'+ $(this).val(),
                        cache: false,
                        dataType: 'json',
                        success: function (data) {
                            var suggestions = [];



                            $.each(JSON.parse(data), function (index, item) {

                                suggestions.push({"ville": item});



                                })
                            console.log(suggestions);
                            $('#resultats_recherche').html(JSON.parse(data));


                        }})
                }}
            )
        });
});
**/

var suggestUrlVille = $('#ville').attr('data-suggest');

$('#ville').typeahead({

    // suggestions pour une saisie d'au minimum 3 caractères
    minLength: 3,

    // nous configurons ici la source distante de données
    source: function(query, process) {

        // "query" est la chaîne de recherche
        // "process" est une closure qui doit recevoir la liste des suggestions à afficher

        var $this = this;
        $.ajax({
            url: suggestUrlVille,
            type: 'GET',
            data: {
                search: query
            },

            success: function(data) {


                // les données que nous recevons sont de 3 types:
                //  "suggest" est la chaîne de caractères à afficher dans les suggestions
                //  "ville" et "codePostal" sont les données que nous voulons utiliser pour
                //  remplir nos champs lors de la sélection d'une suggestion

                // ce tableau "reversed" conserve temporairement une relation entre chaque
                // suggestion, et ses données associées
                var reversed = {};

                // ici nous générons simplement la liste des suggestions à afficher
                var suggests = [];

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
        $('#codePostal').val(elem.codePostal);
        $('#villeId').val(elem.id);

        // ...et "ville" du formulaire
        return elem.ville;
    },

    // cette méthode permet de déterminer lesquelles des suggestions sont valides par rapport
    // à la recherche. Nous effectuons déjà tout cela côté serveur, donc ici il suffit de
    // retourner "true"
    matcher: function() {
        return true;
    }
});
