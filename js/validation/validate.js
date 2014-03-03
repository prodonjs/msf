$(document).ready(function(){
  $('#register').validate({
    rules: {
      
      name: {
        required: true,
        minlength:1
      },
      
      company: {
        required: true,
        minlength:1
      },
      
      email: {
        required: true,
        email: true
      },

      phone: {
        required: true,
        minlength: 1
      },
 

    },
    success: function(label) {label.text('OK!').addClass('valid');}
  });
});
