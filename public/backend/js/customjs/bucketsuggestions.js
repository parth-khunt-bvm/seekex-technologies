var Bucketsuggestions = function(){
    var capacityAvailable = true;
    function ballBucketCalculation(){
        var bucketVol = 0.00;
        var count = false;
        $('.bucket-vol').each(function () {
            count = true;
            bucketVol = parseFloat(bucketVol) + parseFloat($(this).val());
        });
        $("#buckets-volume").text(bucketVol.toFixed(2));

        var ballVol = 0.00;
        $('.ball-vol').each(function () {
            count = true;
            ballVol = parseFloat(ballVol) + parseFloat($(this).val());
        });

        $("#ball-volume").text(ballVol.toFixed(2));

        var remainingSpace = parseFloat(bucketVol) - parseFloat(ballVol) ;
        var remainingSpaceHtml = '';

        if(count){
            if(remainingSpace == 0){
                capacityAvailable = false;
                remainingSpaceHtml = '<div class="alert alert-custom alert-notice alert-light-success fade show mb-5" role="alert">'+
                            '<div class="alert-icon">'+
                                '<i class="flaticon-success"></i>'+
                            '</div>'+
                            '<div class="alert-text">There are no more slots in the bucket.!</div>'+
                        '</div>';
            }else if(remainingSpace < 0){
                capacityAvailable = false;
                remainingSpaceHtml = '<div class="alert alert-custom alert-notice alert-light-danger fade show mb-5" role="alert">'+
                            '<div class="alert-icon">'+
                                '<i class="flaticon-danger"></i>'+
                            '</div>'+
                            '<div class="alert-text">A bucket has reached its capacity</div>'+
                        '</div>';
            }else{
                capacityAvailable = true;
                remainingSpaceHtml = '<div class="alert alert-custom alert-notice alert-light-warning fade show mb-5" role="alert">'+
                    '<div class="alert-icon">'+
                        '<i class="flaticon-warning"></i>'+
                    '</div>'+
                    '<div class="alert-text">There are slots available in the bucket.!</div>'+
                '</div>';
            }
        }
        $("#remaining-space").html(remainingSpaceHtml);
    }

    $('body').on("click", ".remove-items", function() {
        $(this).closest('.removediv').remove();
        ballBucketCalculation();
    });

    var bucketsuggestionsList = function(){
        var dataArr = {};
        var columnWidth = { "width": "5%", "targets": 0 };
        var arrList = {
            'tableID': '#bucket-suggestions-list',
            'ajaxURL': baseurl + "bucket-suggestions/ajaxcall",
            'ajaxAction': 'getdatatable',
            'postData': dataArr,
            'hideColumnList': [],
            'noSortingApply': [0, 4],
            'noSearchApply': [0, 4],
            'defaultSortColumn': [0],
            'defaultSortOrder': 'DESC',
            'setColumnWidth': columnWidth
        };
        getDataTable(arrList);
    }
    var addBucketsuggestions = function(){
        $('.select2').select2();

        var validateTrip = true;
        var customValid = true;

        $('#add-bucket-suggestions').validate({
            debug: true,
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class

            rules : {
                bucketSuggestions : {required: true},
            },

            messages : {
                bucketSuggestions : {required: "Please enter bucket suggestions name"},
            },

            invalidHandler: function (event, validator) {
                validateTrip = false;
                customValid = customerInfoValid();

            },

            submitHandler: function (form) {
                $(".submitbtn:visible").attr("disabled", "disabled");
                $("#loader").show();
                customValid = customerInfoValid();
                if (customValid)
                {
                    var options = {
                        resetForm: false, // reset the form after successful submit
                        success: function (output) {
                            handleAjaxResponse(output);
                        }
                    };
                    $(form).ajaxSubmit(options);
                }else{
                    $(".submitbtn:visible").prop("disabled",false);
                    $("#loader").hide();
                }
            },

            errorPlacement: function(error, element) {
                customValid = customerInfoValid();
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    element = $("#select2-" + elem.attr("id") + "-container").parent();
                    error.insertAfter(element);
                }else {
                    if (elem.hasClass("radio-btn")) {
                        element = elem.parent().parent();
                        error.insertAfter(element);
                    }else{
                        error.insertAfter(element);
                    }
                }
            },
        });

        function customerInfoValid() {
            var customValid = true;
            ballBucketCalculation();
            if(capacityAvailable){
                customValid = false;
            }
            return customValid;
        }

        $('body').on('click', '.add-bucket', function(){
            var bucketValue = $("#bucket").select2().find(":selected").val();
            if(bucketValue == null || bucketValue == ''){
                $('.bucket-error').text('Please select bucket');
            }else{
                $('.bucket-error').text('');
                capacityAvailable = true;
                var bucketVolume = $("#bucket").select2().find(":selected").data('volume');
                var buckets = $("#bucket").select2().find(":selected").data('buckets');
                var html ='<div class="col-md-6 removediv">'+
                    '<div class="row">'+
                        '<div class="col-md-5">'+
                            '<div class="form-group">'+
                                '<label>Buckets name  </label>'+
                                '<input type="text" name="bucket[]" class="form-control" placeholder="Enter bucket name" value="'+buckets+'" readonly>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-5">'+
                            '<div class="form-group">'+
                                '<label>Buckets volume </label>'+
                                '<input type="text" name="bucket_volume[]" class="form-control bucket-vol" placeholder="Enter bucket volume" value="'+ bucketVolume +'" readonly>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2">'+
                            '<div class="form-group">'+
                            '<label>&nbsp;</label><br>'+
                            '<a href="javascript:;" class="my-btn btn btn-danger remove-items"><i class="my-btn fa fa-minus"></i></a>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>';
                $('#buckets-details').append(html);
                ballBucketCalculation();
            }
        });

        $('body').on('click', '.add-ball', function(){
            var ballValue = $('#ball').val();
            if(ballValue == null || ballValue == ''){
                $('.ball-error').text('Please select ball');
            }else{
                ballBucketCalculation();
                if(capacityAvailable){
                    $('.bucket-error').text('');
                    var ballVolume = $("#ball").select2().find(":selected").data('volume');
                    var balls = $("#ball").select2().find(":selected").data('ball');
                    var html ='<div class="col-md-6 removediv">'+
                        '<div class="row">'+
                            '<div class="col-md-5">'+
                                '<div class="form-group">'+
                                    '<label>Buckets name  </label>'+
                                    '<input type="text" name="ball[]" class="form-control" placeholder="Enter ball name" value="'+balls+'" readonly>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-5">'+
                                '<div class="form-group">'+
                                    '<label>Buckets volume </label>'+
                                    '<input type="text" name="ball_volume[]" class="form-control ball-vol" placeholder="Enter ball volume" value="'+ ballVolume +'" readonly>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-2">'+
                                '<div class="form-group">'+
                                '<label>&nbsp;</label><br>'+
                                '<a href="javascript:;" class="my-btn btn btn-danger remove-items"><i class="my-btn fa fa-minus"></i></a>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>';
                    $('#ball-details').append(html);
                    ballBucketCalculation();
                }
            }
        });

    }
    var editBucketsuggestions = function(){
        alert('editBucketsuggestions');
    }

    return {
        init:function(){
            bucketsuggestionsList();
        },
        add:function(){
            addBucketsuggestions();
        },
        edit:function(){
            editBucketsuggestions();
        },
    }
}();
