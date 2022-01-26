var ComponentsEditors = function () {

    var handleWysihtml5 = function () {
        if (!jQuery().wysihtml5) {
            return;
        }

        if ($('.wysihtml5').size() > 0) {
            $('.wysihtml5').wysihtml5({
                "stylesheets": ["../plugins/bootstrap-wysihtml5/wysiwyg-color.css"]
            });
        }
    }

    var handleSummernote = function () {
        $('#edition_description').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'codeview']],
            ],
            height: 100});
        $('#edition_entry_detail').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'codeview']],
            ],
            height: 100});
        $('#edition_reg_detail').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'codeview']],
            ],
            height: 100});
        $('#edition_intro_detail').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'codeview']],
            ],
            height: 80});
//        $('#edition_address').summernote({
//            toolbar: [
//                // [groupName, [list of button]]
//                ['style', ['bold', 'italic', 'underline', 'color']],
//                ['para', ['ul', 'ol', 'paragraph']],
//                ['insert', ['link', 'codeview']],
//            ],
//            height: 100});
//        $('#edition_address_end').summernote({
//            toolbar: [
//                // [groupName, [list of button]]
//                ['style', ['bold', 'italic', 'underline', 'color']],
//                ['para', ['ul', 'ol', 'paragraph']],
//                ['insert', ['link', 'codeview']],
//            ],
//            height: 100});
        $('#race_notes').summernote({
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'codeview']],
            ],
            height: 100});
        $('#parkrun_comment').summernote({height: 200});
        $('#quote_quote').summernote({height: 200});
        $('#emailque_body').summernote({height: 400});
        $('#emailtemplate_body').summernote({height: 400});
        $('#emailmerge_body').summernote({height: 250});
        //API:
        //var sHTML = $('#summernote_1').code(); // get code
        //$('#summernote_1').destroy(); // destroy
    }

    return {
        //main function to initiate the module
        init: function () {
            handleWysihtml5();
            handleSummernote();
        }
    };

}();

jQuery(document).ready(function () {
    ComponentsEditors.init();
});