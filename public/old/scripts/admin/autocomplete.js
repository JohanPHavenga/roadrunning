var ComponentsTypeahead = function () {

    var handleTwitterTypeahead = function () {

        var base_url = window.location.origin;

        var towns = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: base_url + '/admin/town/json/%QUERY',
                wildcard: '%QUERY'
            }
        });

        towns.initialize();

        $('#town_name').typeahead(null, {
            source: towns.ttAdapter(),
            name: 'town_name',
            displayKey: 'value',
            templates: {
            empty: [
              '<div class="empty-message">',
                'Unable to find town',
              '</div>'
            ].join('\n'),
            suggestion: Handlebars.compile('<div><strong>{{value}}</strong> – {{id}}</div>')
          }
            
        }).bind('typeahead:select', function (ev, suggestion) {
            $('#town_id').val(suggestion.id);
        });
    }



    return {
        //main function to initiate the module
        init: function () {
            handleTwitterTypeahead();
        }
    };

}();

jQuery(document).ready(function () {
    ComponentsTypeahead.init();
});
