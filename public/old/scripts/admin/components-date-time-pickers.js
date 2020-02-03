var ComponentsDateTimePickers = function () {

    var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
                format: "yyyy-mm-dd"
            });
        }
    }

    var handleTimePickers = function () {

        if (jQuery().timepicker) {
            $('.timepicker-default').timepicker({
                autoclose: true,
                showSeconds: true,
                minuteStep: 1
            });

            $('.timepicker-no-seconds').timepicker({
                autoclose: true,
                minuteStep: 5,
                defaultTime: false
            });

            $('.timepicker-24').timepicker({
                autoclose: true,
                minuteStep: 5,
                secondStep: 1,
                showSeconds: false,
                showMeridian: false
            });
            
            $('.timepicker-24-seconds').timepicker({
                autoclose: true,
                minuteStep: 1,
                secondStep: 1,
                showSeconds: true,
                showMeridian: false
            });

            // handle input group button click
            $('.timepicker').parent('.input-group').on('click', '.input-group-btn', function(e){
                e.preventDefault();
                $(this).parent('.input-group').find('.timepicker').timepicker('showWidget');
            });

            // Workaround to fix timepicker position on window scroll
            $( document ).scroll(function(){
                $('#form_modal4 .timepicker-default, #form_modal4 .timepicker-no-seconds, #form_modal4 .timepicker-24').timepicker('place'); //#modal is the id of the modal
            });
        }
    }

    var handleDatetimePicker = function () {

        if (!jQuery().datetimepicker) {
            return;
        }
        

        $(".form_datetime").datetimepicker({
            autoclose: true,
            isRTL: App.isRTL(),
//            format: "dd MM yyyy - hh:ii",
            format: 'yyyy-mm-dd hh:ii',
            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
        });

        $(".form_advance_datetime").datetimepicker({
            isRTL: App.isRTL(),
            format: "dd MM yyyy - hh:ii",
            autoclose: true,
            todayBtn: true,
            startDate: "2013-02-14 10:00",
            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
            minuteStep: 10
        });

        $(".form_meridian_datetime").datetimepicker({
            isRTL: App.isRTL(),
            format: "dd MM yyyy - HH:ii P",
            showMeridian: true,
            autoclose: true,
            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
            todayBtn: true
        });
        
        // handle input group button click
        $('.datetimepicker').parent('.input-group').on('click', '.input-group-btn', function(e){
            e.preventDefault();
            $(this).parent('.input-group').find('.datetimepicker').datetimepicker('showWidget');
        });
        
                $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal

        // Workaround to fix datetimepicker position on window scroll
        $( document ).scroll(function(){
            $('#form_modal1 .form_datetime, #form_modal1 .form_advance_datetime, #form_modal1 .form_meridian_datetime').datetimepicker('place'); //#modal is the id of the modal
        });
    }

    return {
        //main function to initiate the module
        init: function () {
            handleDatePickers();
            handleTimePickers();
            handleDatetimePicker();
        }
    };

}();

jQuery(document).ready(function() {    
    ComponentsDateTimePickers.init(); 
});