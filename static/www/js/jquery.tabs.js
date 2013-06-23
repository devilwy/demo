(function($){
	$.fn.extend({
		Tabs:function(options){
			options = $.extend({
				event : 'mouseover',
				timeout : 0,
				auto : 0,
				callback : null
			}, options);
			 
			var self = $(this),
				tabBox = self.children( '.tab-box' ).children( 'ul' ),
				menu = self.children( '.tab-menu' ),
				items = menu.find( 'li' ),
				timer;
				
			var tabHandle = function( elem ){
					elem.siblings( 'li' )
						.removeClass( 'on' )
						.end()
						.addClass( 'on' );
						
					tabBox.siblings( 'ul' )
						.addClass( 'hide' )
						.end()
						.eq( elem.index() )
						.removeClass( 'hide' );
				},
					
				delay = function( elem, time ){
					time ? setTimeout(function(){ tabHandle( elem ); }, time) : tabHandle( elem );
				},
				
				start = function(){
					if( !options.auto ) return;
					timer = setInterval( autoRun, options.auto );
				},
				
				autoRun = function(){
					var on = menu.find( 'li.on' ),
						firstItem = items.eq(0),
						len = items.length,
						index = on.index() + 1,
						item = index === len ? firstItem : on.next( 'li' ),
						i = index === len ? 0 : index;
					
					on.removeClass( 'on' );
					item.addClass( 'on' );
					
					tabBox.siblings( 'ul' )
						.addClass( 'hide' )
						.end()
						.eq(i)
						.removeClass( 'hide' );
				};
							
			items.bind( options.event, function(){
				delay( $(this), options.timeout );
				if( options.callback ){
					options.callback( self );
				}
			});
			
			if( options.auto ){
				start();
				self.hover(function(){
					clearInterval( timer );
					timer = undefined;
				},function(){
					start();
				});
			}
			
			return this;
		}
	});
})(jQuery);