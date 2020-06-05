// Class definition
var AutoComplete = function() {


    // Private functions
    var autocomp_f = function() {
        var substringMatcher = function(strs) {
            return function findMatches(q, cb) {
                var matches, substringRegex;

                // an array that will be populated with substring matches
                matches = [];

                // regex used to determine if a string contains the substring `q`
                substrRegex = new RegExp(q, 'i');

                // iterate through the pool of strings and for any string that
                // contains the substring `q`, add it to the `matches` array
                $.each(strs, function(i, str) {
                    if (substrRegex.test(str)) {
                        matches.push(str);
                    }
                });

                cb(matches);
            };
        };

        $('#auto_field').typeahead({
            hint: true,
            highlight: true,
            minLength: 2
        }, {
            name: 'auto_field',
            source: substringMatcher(auto_data)
        });
    }


    return {
        // public functions
        init: function() {
            autocomp_f();
        }
    };
}();

jQuery(document).ready(function() {
    AutoComplete.init();
});
