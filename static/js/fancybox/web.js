jQuery(document).ready(function() {

	/*
	*   Examples - images
	*/
	
	$("a#example1").fancybox({
		'titleShow'     : false
	});
		
	$("a#example2").fancybox({
		'titleShow'     : false,
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic',
		'easingIn'      : 'easeOutBack',
		'easingOut'     : 'easeInBack'
	});
	
	$("a#example3").fancybox({
	    'titleShow'     : false,
		'transitionIn'	: 'none',
		'transitionOut'	: 'none'
	});
	
	$("a#example4").fancybox();
	
	$("a#example5").fancybox({
		'titlePosition'  : 'inside'
	});
	
	$("a#example6").fancybox({
		'titlePosition'  : 'over'
	});
	
	$("a[rel=example_group]").fancybox({
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'titlePosition' 	: 'over',
		'titleFormat'       : function(title, currentArray, currentIndex, currentOpts) {
		    return '<span id="fancybox-title-over">Image ' +  (currentIndex + 1) + ' / ' + currentArray.length + '</span>';
		}
	});
	
	/*
	*   Examples - various
	*/
	
	$("#various1").fancybox({
		'titlePosition'		: 'inside',
		'transitionIn'		: 'none',
		'transitionOut'		: 'none'
	});
	
	$("#various2").fancybox({
		'modal' : true
	});
	
	$("#various3").fancybox({
		ajax : {
		    type	: "POST",
		    data	: 'mydata=test'
		}
	});

	$("#various4").fancybox({
		ajax : {
		    type	: "POST"
		}
	});
			
	$("#various5").fancybox({
		'width'				: '60%',
		'height'			: '60%',
        'autoScale'     	: false,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
	});
	
	$("#various6").fancybox({
	    'padding'           : 0,
        'autoScale'     	: false,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none'
	});
	
	$("#various7").fancybox({
		onStart		:	function() {
			return window.confirm('Continue?');
		},
		onCancel	:	function() {
			alert('Canceled!');
		},
		onComplete	:	function() {
            alert('Completed!');
		},
		onCleanup	:	function() {
            return window.confirm('Close?');
		},
		onClosed	:	function() {
            alert('Closed!');
		}
	});
	
	$("#various8, #various9").fancybox();
	
	/*
	*   Examples - manual call
	*/

	$("#manual1").click(function() {
		$.fancybox({
			//'orig'			: $(this),
			'padding'		: 0,
			'href'			: 'http://farm3.static.flickr.com/2687/4220681515_cc4f42d6b9.jpg',
			'title'   		: 'Lorem ipsum dolor sit amet',
			'transitionIn'	: 'elastic',
			'transitionOut'	: 'elastic'
		});
    });
    
    $("#manual2").click(function() {
		$.fancybox([
			'http://farm5.static.flickr.com/4044/4286199901_33844563eb.jpg',
			'http://farm3.static.flickr.com/2687/4220681515_cc4f42d6b9.jpg',
			{
				'href'	: 'http://farm5.static.flickr.com/4005/4213562882_851e92f326.jpg',
				'title'	: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'
			}
		], {
			'padding'			: 0,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'type'              : 'image',
			'changeFade'        : 0
		});
	});
			
	/*
	*   Tips & Tricks
	*/
	
	$("#tip3").fancybox({
	    'transitionIn'	: 'none',
		'transitionOut'	: 'none',
		'titlePosition'	: 'over',
		'onComplete'	: function() {
			$("#fancybox-wrap").hover(function() {
				$("#fancybox-title").show();
			}, function() {
				$("#fancybox-title").hide();
			});
		}
	});

	$("#tip4").click(function() {
		$.fancybox({
			'padding'		: 0,
			'autoScale'		: false,
			'transitionIn'	: 'none',
			'transitionOut'	: 'none',
			'title'			: this.title,
			'width'			: 680,
			'height'		: 495,
			'href'			: this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'),
			'type'			: 'swf',
			'swf'			: {
			    'wmode'				: 'transparent',
				'allowfullscreen'	: 'true'
			}
		});

		return false;
	});

    $("#tip5").fancybox({
    'scrolling' : 'no',
		'titleShow'	: false,
		'onClosed'	: function() {
		    $("#login_error").hide();
		}
	});
    
	$("#form_outro").bind("submit", function() {
		    
	  $.fancybox.showActivity();

		$.ajax({
			type	: "POST",
			cache	: false,
			url		: "/data/login.php",
			data	: $(this).serializeArray(),
			success: function(data) {
				$.fancybox(data);
			}
    
		});

		return false;
	});
	
	$("#tip6").fancybox({
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'autoScale'     	: false,
		'type'				: 'iframe',
		'width'				: 500,
		'height'			: 500,
		'scrolling'   		: 'no'
	});
	
	function formatTitle(title, currentArray, currentIndex, currentOpts) {
//	    return '<div id="tip7-title"><span><a href="javascript:;" onclick="$.fancybox.close();"><img src="static/js/fancybox/closelabel.gif" /></a></span>' + (title && title.length ? '<b>' + title + '</b>' : '' ) + ' ' + (currentIndex + 1) + ' de ' + currentArray.length + '</div>';
	    return '<div id="tip7-title"><span><a href="javascript:;" onclick="$.fancybox.close();"><img src="static/js/fancybox/closelabel.gif" /></a></span></div>';
	}

	$(".tip7").fancybox({
	    'showCloseButton'   : false,
		'titlePosition' 	: 'inside',
		'titleFormat'		: formatTitle
	});
	
	
	// Next JS snippets are only for fancybox.net
	
	/*
	*   Donate link
	*/
	$("a#donate").bind("click", function() {
		$("#donate_form").submit();
		return false;
	});

	/*
	*   Zebra-stripping table
	*/
	
	$("table.options tr:even").addClass('even');

});