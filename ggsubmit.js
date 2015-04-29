jQuery(document).ready(function() {
  var googlepublic = ggAjax.googlePublic;
  var googletheme = ggAjax.googleTheme;
  var infInputText = jQuery('.infusion-submit input').attr('value');
  var infButtonText = jQuery('.infusion-submit button').text();
  var recaptcha = "<!-- captcha --><div class='g-recaptcha' data-theme='"+googletheme+"' data-sitekey='"+googlepublic+"'></div><!-- end captcha --><div style='clear:both'></div><input type='hidden' name='inf_inputText' id='inf_inputText' value='"+infInputText+"' /><input type='hidden' name='inf_buttonText' id='inf_buttonText' value='"+infButtonText+"' />";
  var errorDiv ="<div id='errorDiv' style='display:none'></div>";
  jQuery('.infusion-submit').before(recaptcha);
  jQuery('.infusion-submit').before(errorDiv);
  jQuery('.infusion-form').addClass('validateForm');
  jQuery('form.infusion-form label:contains("*")').each(function () {
    jQuery(this).nextAll('input,textarea,select').prop("required","required"); // html only       
    jQuery(this).closest("td").nextAll().find("input,textarea,select").prop("required","required"); // html with css
  });
  jQuery('form.infusion-form label:contains("Email")').each(function () {
    jQuery(this).nextAll('input').attr('type','email');       
  });
  function ggAddStyle(style) {
    var div = jQuery("<div />", {
      html: '&shy;<style>' + style + '</style>'
    }).appendTo("body");    
  }
  jQuery(".infusion-form").on("submit",function(){
    if (!console) console = {log:function(){}}; // supress IE errors
    jQuery('#errorDiv').fadeOut(); 
    if(!jQuery('.infusion-submit').attr('validated')){
      jQuery(".infusion-submit input").val("Submitting...");
      jQuery(".infusion-submit input").prop("disabled",true);
      jQuery(".infusion-submit button").text("Submitting...");
      jQuery(".infusion-submit button").prop("disabled",true);
      var data = jQuery('.infusion-form').serialize();
      data = data + "&action=gg_infucaptcha_results";
      dataType = 'json';
      console.log(data);
      jQuery.post(ggAjax.ajaxurl, data,function(response){
        jQuery(".infusion-submit button").prop("disabled",false);
        console.log(response);
        if(response && response.success==true){
           jQuery('.infusion-submit').attr('validated',true);
           console.log(response.success);
           jQuery(".infusion-form").unbind().submit();
        } else {
          var errorMsg;
          if(response){
            errorMsg = response.errorMsg;
          } else {
            errorMsg = 'there is a server configuration error';
          }
          jQuery(".infusion-submit input").prop("disabled",false);
          jQuery(".infusion-submit input").val(response.inf_inputText);
          jQuery(".infusion-submit button").prop("disabled",false);
          jQuery(".infusion-submit button").text(response.inf_buttonText);
          jQuery('#errorDiv').html(response.errorMsg);
          jQuery('#errorDiv').fadeIn();
        }   
      },dataType);
    return false;
    }
   return true;
  });
  ggAddStyle('.invalid input:required:invalid { border:2px solid red; }');
  function hasHtml5Validation () {
    return typeof document.createElement('input').checkValidity === 'function';
    return typeof document.createElement('textarea').checkValidity === 'function';
  }
  if (hasHtml5Validation()) {
    jQuery('.validateForm').on("submit",(function (e) {
      if (!this.checkValidity()) {
        e.preventDefault();
        jQuery(this).addClass('invalid');
      } else {
        jQuery(this).addClass('valid');
      }
    }));
  }
});
