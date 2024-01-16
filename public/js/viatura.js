// JavaScript Document
$(document).ready(function(){

	$(document).on('click', '.mais', function(){

		elemento = $(".vtr").eq(0).clone();
		elemento.append('<div class="form-group col-md-2"><strong>&nbsp;</strong><input type="button" class="menos form-control" style="text-align:center; background:red; color:white" value="-"></div>');

		$(elemento).prependTo("#novos");

	});

	$(document).on('click', '.menos', function() {
			$(this).closest(".vtr").remove();
	});

});