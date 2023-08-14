var Bucketsuggestions = function(){
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
                remainingSpaceHtml = '<div class="alert alert-custom alert-notice alert-light-success fade show mb-5" role="alert">'+
                            '<div class="alert-icon">'+
                                '<i class="flaticon-success"></i>'+
                            '</div>'+
                            '<div class="alert-text">There are no more slots in the bucket.!</div>'+
                        '</div>';
            }else if(remainingSpace < 0){
                remainingSpaceHtml = '<div class="alert alert-custom alert-notice alert-light-danger fade show mb-5" role="alert">'+
                            '<div class="alert-icon">'+
                                '<i class="flaticon-danger"></i>'+
                            '</div>'+
                            '<div class="alert-text">A bucket has reached its capacity</div>'+
                        '</div>';
            }else{
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

    }
    var addBucketsuggestions = function(){
        $('.select2').select2();

        $('body').on('click', '.add-bucket', function(){
            var bucketValue = $("#bucket").select2().find(":selected").val();
            if(bucketValue == null || bucketValue == ''){
                $('.bucket-error').text('Please select bucket');
            }else{
                $('.bucket-error').text('');
                var bucketVolume = $("#bucket").select2().find(":selected").data('volume');
                var buckets = $("#bucket").select2().find(":selected").data('buckets');
                var html ='<div class="row removediv">'+
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
                $('.bucket-error').text('');
                var ballVolume = $("#ball").select2().find(":selected").data('volume');
                var balls = $("#ball").select2().find(":selected").data('ball');
                var html ='<div class="row removediv">'+
                    '<div class="col-md-5">'+
                        '<div class="form-group">'+
                            '<label>Balls name  </label>'+
                            '<input type="text" name="ball[]" class="form-control" placeholder="Enter ball name" value="'+balls+'" readonly>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-5">'+
                        '<div class="form-group">'+
                            '<label>balls volume </label>'+
                            '<input type="text" name="ball_volume[]" class="form-control ball-vol" placeholder="Enter ball volume" value="'+ ballVolume +'" readonly>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<div class="form-group">'+
                        '<label>&nbsp;</label><br>'+
                        '<a href="javascript:;" class="my-btn btn btn-danger remove-items"><i class="my-btn fa fa-minus"></i></a>'+
                        '</div>'+
                    '</div>'+
                '</div>';
                $('#ball-details').append(html);
                ballBucketCalculation();
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
