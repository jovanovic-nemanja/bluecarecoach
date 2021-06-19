$(function() {
    "use strict";

    $("#duration").change(function() {
        var sel_val = $(this).val();

        if (sel_val == 1) {
            // Daily
            $("#Daily_area").show();
            $("#Weekly_area").hide();
            $("#Monthly_area").hide();
        }
        if (sel_val == 2) {
            //Weekly
            $("#Daily_area").hide();
            $("#Weekly_area").show();
            $("#Monthly_area").hide();
        }
        if (sel_val == 3) {
            //Monthly
            $("#Daily_area").hide();
            $("#Weekly_area").hide();
            $("#Monthly_area").show();
        }
    });

    $("#looking_job").change(function() {
        var looking_job = $(this).val();
        var href = $("#env_domain_url").val();
        window.location.href =
            href + "/indexbyfilter?looking_job=" + looking_job;
    });

    $("#search").click(function() {
        var looking_job = $("#looking_job").val();
        var href = $("#env_domain_url").val();
        if (looking_job) {
            window.location.href =
                href + "/indexbyfilter?looking_job=" + looking_job;
        } else {
            alert("Please choose anything in dropdown menu.");
            return;
        }
    });

    $(".type_emailsettings").change(function() {
        switch ($(this).val()) {
            case "1":
                $(".content_name").show();
                $(".content_body").hide();
                $(".pre_footer").hide();
                $(".footer").hide();

                $(".content_name input").prop("required", true);
                $(".content_body input").prop("required", false);
                $(".pre_footer input").prop("required", false);
                $(".footer input").prop("required", false);

                break;
            case "2":
                $(".content_name").show();
                $(".content_body").show();
                $(".pre_footer").show();
                $(".footer").hide();

                $(".content_name input").prop("required", true);
                $(".content_body input").prop("required", true);
                $(".pre_footer input").prop("required", true);
                $(".footer input").prop("required", false);

                break;
            case "3":
                $(".content_name").hide();
                $(".content_body").hide();
                $(".pre_footer").hide();
                $(".footer").hide();

                $(".content_name input").prop("required", false);
                $(".content_body input").prop("required", false);
                $(".pre_footer input").prop("required", false);
                $(".footer input").prop("required", false);

                break;
            case "4":
                $(".content_name").hide();
                $(".content_body").hide();
                $(".pre_footer").hide();
                $(".footer").hide();

                $(".content_name input").prop("required", false);
                $(".content_body input").prop("required", false);
                $(".pre_footer input").prop("required", false);
                $(".footer input").prop("required", false);

                break;
            case "5":
                $(".content_name").hide();
                $(".content_body").hide();
                $(".pre_footer").hide();
                $(".footer").hide();

                $(".content_name input").prop("required", false);
                $(".content_body input").prop("required", false);
                $(".pre_footer input").prop("required", false);
                $(".footer input").prop("required", false);

                break;
            default:
                $(".content_name").hide();
                $(".content_body").hide();
                $(".pre_footer").hide();
                $(".footer").hide();

                $(".content_name input").prop("required", false);
                $(".content_body input").prop("required", false);
                $(".pre_footer input").prop("required", false);
                $(".footer input").prop("required", false);

                break;
        }
    });

    hideSomeElement();

    function hideSomeElement() {
        $(".content_name").hide();
        $(".content_body").hide();
        $(".pre_footer").hide();
        $(".footer").hide();

        $(".content_name input").prop("required", false);
        $(".content_body input").prop("required", false);
        $(".pre_footer input").prop("required", false);
        $(".footer input").prop("required", false);
    }

    $("document").ready(function() {
        const typeValue = $(".type_emailsettings").val();
        switch (typeValue) {
            case "1":
                $(".content_name").show();
                $(".content_body").hide();
                $(".pre_footer").hide();
                $(".footer").hide();

                $(".content_name input").prop("required", true);
                $(".content_body input").prop("required", false);
                $(".pre_footer input").prop("required", false);
                $(".footer input").prop("required", false);

                break;
            case "2":
                $(".content_name").show();
                $(".content_body").show();
                $(".pre_footer").show();
                $(".footer").hide();

                $(".content_name input").prop("required", true);
                $(".content_body input").prop("required", true);
                $(".pre_footer input").prop("required", true);
                $(".footer input").prop("required", false);

                break;
            case "3":
                $(".content_name").hide();
                $(".content_body").hide();
                $(".pre_footer").hide();
                $(".footer").hide();

                $(".content_name input").prop("required", false);
                $(".content_body input").prop("required", false);
                $(".pre_footer input").prop("required", false);
                $(".footer input").prop("required", false);

                break;
            case "4":
                $(".content_name").hide();
                $(".content_body").hide();
                $(".pre_footer").hide();
                $(".footer").hide();

                $(".content_name input").prop("required", false);
                $(".content_body input").prop("required", false);
                $(".pre_footer input").prop("required", false);
                $(".footer input").prop("required", false);

                break;
            case "5":
                $(".content_name").hide();
                $(".content_body").hide();
                $(".pre_footer").hide();
                $(".footer").hide();

                $(".content_name input").prop("required", false);
                $(".content_body input").prop("required", false);
                $(".pre_footer input").prop("required", false);
                $(".footer input").prop("required", false);

                break;
            default:
                $(".content_name").hide();
                $(".content_body").hide();
                $(".pre_footer").hide();
                $(".footer").hide();

                $(".content_name input").prop("required", false);
                $(".content_body input").prop("required", false);
                $(".pre_footer input").prop("required", false);
                $(".footer input").prop("required", false);

                break;
        }
    });
});
