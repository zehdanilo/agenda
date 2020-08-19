
$(document).ajaxStart(function() {
	isDataLoading(true);
});

$(document).ajaxStop(function() {
	isDataLoading(false);
});

$(document).ready(function(){
    
    var modal         = "#ModalView";
    var modal_label   = "#ModalViewLabel";
    var modal_content = "#ModalViewContent";
    var modal_dialog  = "#ModalViewDialog";
	
	// Text Loading pressed
	$(document).on('click', 'button[data-loading-text]', function(e){
        $(this).button('loading');
    });

	// Redirect a page by jquery
	$(document).on('click', '[data-redirect="true"]', function(e){
		var url = $(this).attr("data-url");
		goToURL(url);
    });
	
	// Add tooltip in element
	$('body').tooltip({
        selector: '[data-toggle="tooltip"],[data-tooltip="true"]',
        container: 'body'
    });
	
	// Add popover in element
	$('[data-toggle="popover"]').popover();
	
	// Add Caledar for element
	$(document).on('focus.datepicker.data-api click.datepicker.data-api', '[data-toggle="datepicker"]', function(e){
        var $dt_begin = $(this).attr("data-datepicker-begin");
        var $dt_end   = $(this).attr("data-datepicker-end");
         
        $(this).datepicker({
            format: "dd/mm/yyyy",
            language: 'pt-BR',
            autoclose: true,
            todayHighlight: true,
            daysOfWeekHighlighted: [0, 6],
            startDate: $dt_begin,
            endDate: $dt_end
        });
	});
	
    $(document).on('focus','[data-money="true"]', function(e){
        var $id = "#" + $(this).attr('id');
        $($id).maskMoney();        
    });

	// Add Auto-Resize for textarea
	$(document).on('focus.autosize.data-api click.autosize.data-api', '[data-toggle="autosize"]', function(e){
        $(this).autosize();
	});
	
	// Focus input element
	$(document).on('click', '[data-focus="true"]', function(e){
        var target = $(this).attr("data-focus-target")
        $(target).focus();
    });
	
	// alert in element onClick
	$('[data-alert="true"]').on('click', function(e){
		var message = $(this).attr('data-alert-message');
		alert(message);
    });
	
	// input button force submit form
	$(document).on('click', 'button[type="button"][data-submit="true"]', function(e){
        var target = $(this).attr("data-submit-form");
        $(target).submit();
    });
	
//    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
//    	$('.selectpicker').selectpicker('mobile');
//    }
	
	//Form default to submit
    $(document).on('submit', 'form[data-form="default"]', function(e) {
        e.preventDefault();
        var $form      = $(this);
        var dataFields = $form.serializeArray();
        var url        = $form.attr('action');
        var method     = $form.attr('method');
        var fnCallback = $form.attr('data-action-callback') || "";
        var type       =($form.attr('data-type') === undefined) ? "html" : $form.attr('data-type');
        var fieldset   = 'fieldset[data-target="'+$form.attr('id')+'"]';
        
        var result = $form.attr('data-result')
        if (typeof result === 'undefined' || result === false || result == '') {
            result = 'div[data-target="'+$form.attr('id')+'-result"]';
        }
        
        var resultJSON = $form.attr('data-result-json');
        if (typeof resultJSON === 'undefined' || resultJSON === false || resultJSON == '') {
            resultJSON = 'div[data-target="'+$form.attr('id')+'-result-json"]';
        }
        
        $.ajax({
            method: method,
            url: url,
            data: dataFields,
            dataType: type,
            async: false,
            error: function(data){
            	var content = stripHTML(data.responseText);
                $(resultJSON).mensagem("danger", null, content, true, 10);
            },
            beforeSend: function(){
                $(fieldset).attr('disabled', true);
                $(result).empty();
                $(resultJSON).empty();
            },
            success: function(data) {
            	try {
	            	if(isJSON(data)){
	                    var obj = (type == 'json') ? data : JSON.parse(data);
	
	                    if ($.type(obj.message) === "object") {
	                	    $(resultJSON).mensagem(
	                	    	obj.message.status,
	                	    	obj.message.title,
	                	    	obj.message.message,
	                	    	obj.message.dismissable,
	                	    	obj.message.timeout
	                        );
	                    }
	                    $.globalEval(obj.callback);
	                }else{
	                    $(result).html(data);
	                    $('html, body').animate({ scrollTop: $(result).offset().top-10 }, 'slow');
	                }
                } catch (e) {
                	$(resultJSON).mensagem("danger", null, e, true, 10);
                }
            },
            complete: function(){
                $.globalEval(fnCallback);
                $(fieldset).removeAttr('disabled');
            }
        });
    });
    
    //Form default to upload
    $(document).on('submit', 'form[data-form="upload"]', function(e) {
        e.preventDefault();
        var $form      = $(this);
        var dataFields = new FormData($(this)[0]);
        var url        = $form.attr('action');
        var method     = $form.attr('method');
        var fnCallback = $form.attr('data-action-callback') || "";
        var type       =($form.attr('data-type') === undefined) ? "html" : $form.attr('data-type');
        var fieldset   = 'fieldset[data-target="'+$form.attr('id')+'"]';
        
        var result = $form.attr('data-result')
        if (typeof result === 'undefined' || result === false || result == '') {
            result = 'div[data-target="'+$form.attr('id')+'-result"]';
        }
        
        var resultJSON = $form.attr('data-result-json');
        if (typeof resultJSON === 'undefined' || resultJSON === false || resultJSON == '') {
            resultJSON = 'div[data-target="'+$form.attr('id')+'-result-json"]';
        }
        
        $.ajax({
            method: method,
            url: url,
            data: dataFields,
            dataType: type,
            cache: false,
            contentType: false,
            processData: false,
            async: false,
            error: function(data){
            	var content = stripHTML(data.responseText);
                $(resultJSON).mensagem("danger", null, content, true, 10);
            },
            beforeSend: function(){
                $(fieldset).attr('disabled', true);
                $(result).empty();
                $(resultJSON).empty();
            },
            success: function(data) {
            	try {
	            	if(isJSON(data)){
	                    var obj = (type == 'json') ? data : JSON.parse(data);
	
	                    if ($.type(obj.message) === "object") {
	                	    $(resultJSON).mensagem(
	                	    	obj.message.status,
	                	    	obj.message.title,
	                	    	obj.message.message,
	                	    	obj.message.dismissable,
	                	    	obj.message.timeout
	                        );
	                    }
	                    $.globalEval(obj.callback);
	                }else{
	                    $(result).html(data);
	                    $('html, body').animate({ scrollTop: $(result).offset().top-10 }, 'slow');
	                }
                } catch (e) {
                	$(resultJSON).mensagem("danger", null, e, true, 10);
                }
            },
            complete: function(){
                $.globalEval(fnCallback);
                $(fieldset).removeAttr('disabled');
            }
        });
    });

    //Form default to view
    $(document).on('click', '[data-form="view"]', function(e) {
        e.preventDefault();
        var $selector      = $(this);
        var title          = $selector.attr("title") || $selector.attr("data-original-title");
        var url            = $selector.attr('data-url');
        var method         = $selector.attr('data-method') || "GET";
        var data           = $selector.attr('data-values');
        var fnCallBack     = $selector.attr('data-action-callback') || "";
        var type           =($selector.attr('data-type') === undefined) ? "html" : $selector.attr('data-type');
        var dialog         = $selector.attr('data-dialog') || "";
        
        $.ajax({
            method: method,
            url: url,
            data: data,
            dataType: type,
            async: false,
            error: function(data){
            	var content = stripHTML(data.responseText);
            	$(modal_content).mensagem("danger", null, content, true, 10);
            },
            beforeSend: function(){
                $(modal_label).html(title);
                $(modal_content).empty();
                $(modal_dialog).removeClass("modal-lg modal-sm");
            },
            success: function(data) {
                try {
	            	if(isJSON(data)){
	                    var obj = (type == 'json') ? data : JSON.parse(data);
	
	                    if ($.type(obj.message) === "object") {
	                	    $(modal_content).mensagem(
	                	    	obj.message.status,
	                	    	obj.message.title,
	                	    	obj.message.message,
	                	    	obj.message.dismissable,
	                	    	obj.message.timeout
	                        );
	                    }
	                    $.globalEval(obj.callback);
	                }else{
	                    $(modal_content).html(data);
	                }
                } catch (e) {
                	$(resultJSON).mensagem("danger", null, e, true, 10);
                }
            },
            complete: function(){
                $.globalEval(fnCallBack);
                $(modal_dialog).addClass(dialog);
                $(modal).modal('handleUpdate');
            }
        });

    });
    
    $(modal).on('hidden.bs.modal', function() {
    	$(modal_label).html('');
        $(modal_content).empty();
        $(modal_dialog).removeClass("modal-lg modal-sm");
	});
    
    isDataLoading(false);
    
});

function isJSON(something) {
    if (typeof something != 'string'){
        something = JSON.stringify(something);
    }
    try {
        JSON.parse(something);
        return true;
    } catch (e) {
        return false;
    }
}

function SelectorPrepare( mySelector ) {
    return mySelector.replace( /(:|\.|\[|\]|,)/g, "\\$1" );
}

function stripHTML( content ) {
    return content.replace(/(<\?[a-z]*(\s[^>]*)?\?(>|$)|<!\[[a-z]*\[|\]\]>|<!DOCTYPE[^>]*?(>|$)|<!--[\s\S]*?(-->|$)|<[a-z?!\/]([a-z0-9_:.])*(\s[^>]*)?(>|$))/gi, '');
}

function goToURL(url){
	isDataLoading(true);
    $(window.location).prop("href", url);
}

function isDataLoading(state){
	var $body = $(document.body);
	var load  = $("#data-loading");
	
	if (state) {
		$body.addClass('data-loading-open');
		load.fadeIn('fast');
	}else{
		$body.removeClass('data-loading-open');
		load.fadeOut('slow');
	}
}


'use strict';
$(function() {
	$('[data-submenu]').submenupicker();
});
