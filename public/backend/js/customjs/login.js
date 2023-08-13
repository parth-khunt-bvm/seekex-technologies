var Login = function(){
    var validation = function(){
        var form = $('#login-form');
        var rules = {
            email: {required: true, email: true},
            password: {required: true},
        };
        var message = {
            email :{
                required : "Please enter your register email address",
                email : "Please enter vaild email address"
            },
            password : {
                required : "Please enter password"
            }
        }
        handleFormValidateWithMsg(form, rules,message, function(form) {
            handleAjaxFormSubmit(form,true);
        });
    }

    return {
        init:function(){
            validation();
        }
    }
}();
