
$(Document).ready(function(){
	
	$('.mode_item')
	.bind('mouseover', function(e){
		m_over.m_in(e);
	})
	.bind('mouseout', function(e){
		m_over.m_out(e);
	});

		

	
	
	
});


var m_over = {
		
		image_size : {
				h_norm : 165,
				w_norm : 251,
				h_max : 198,
				w_max: 301
		},	
		
		m_in: function(e){
			$(e.currentTarget)
			.css({
				'z-index': 10,
				'width': m_over.image_size.w_max,
				'height': m_over.image_size.h_max,
				'left': (m_over.image_size.w_norm - m_over.image_size.w_max )/2,
				'top': (m_over.image_size.h_norm - m_over.image_size.h_max )/2,
			})
			.find('img')
			.css({
				'width': m_over.image_size.w_max,
				'height': m_over.image_size.h_max,
			});
			
		},
		
		m_out: function(e){
			$(e.currentTarget)
			.css({
				'z-index': 0,
				'width': m_over.image_size.w_norm,
				'height': m_over.image_size.h_norm,
				'left': 0,
				'top': 0
			})
			.find('img')
			.css({
				'width': m_over.image_size.w_norm,
				'height': m_over.image_size.h_norm,
			});
			
			
		},
		
};

