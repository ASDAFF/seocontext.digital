$(document).ready(function(){
		$('.contacts form').validate({
			highlight: function( element ){
				$(element).parent().addClass('error');
			},
			unhighlight: function( element ){
				$(element).parent().removeClass('error');
			},
			submitHandler: function( form ){
				if( $('.contacts form').valid() ){
					$(form).find('button[type="submit"]').attr("disabled", "disabled");
					form.submit();
				}
			},
			errorPlacement: function( error, element ){
				error.insertBefore(element);
			},
			messages:{
		      licenses: {
		        required : BX.message('JS_REQUIRED_LICENSES')
		      }
			}
		});

		if(arDigitalOptions['THEME']['PHONE_MASK'].length){
			var base_mask = arDigitalOptions['THEME']['PHONE_MASK'].replace( /(\d)/g, '_' );
			$('.contacts form input.phone').inputmask("mask", { "mask": arDigitalOptions['THEME']['PHONE_MASK'], 'showMaskOnHover': false });
			$('.contacts form input.phone').blur(function(){
				if( $(this).val() == base_mask || $(this).val() == '' ){
					if( $(this).hasClass('required') ){
						$(this).parent().find('div.error').html(BX.message("JS_REQUIRED"));
					}
				}
			});
		}

		if(arDigitalOptions['THEME']['DATE_MASK'].length)
			$('.contacts form input.date').inputmask(arDigitalOptions['THEME']['DATE_MASK'], { 'placeholder': arDigitalOptions['THEME']['DATE_PLACEHOLDER'], 'showMaskOnHover': false });

		$("input[type=file]").uniform({ fileButtonHtml: BX.message("JS_FILE_BUTTON_NAME"), fileDefaultHtml: BX.message("JS_FILE_DEFAULT") });
	});