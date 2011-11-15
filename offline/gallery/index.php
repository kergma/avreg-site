		<div id="page"> 
		<div id="matrix_load" style="display: none;"><img src="gallery/img/loading.gif"></div>
		
			<div id="sidebar">

				
				<!-- sidebar inner block -->
				<div class="block">
					<div id="type_event">
					
						<?php 
							if (isset($cookies['type_event'])) {
								$type = explode(',', trim($cookies['type_event'], ','));
							}
						?>
						<span class="niceCheck"><input type="checkbox" id="image_type" name="type_event"  value="image" <?php if (empty($type) || in_array('i', $type)) :?>checked="checked" <?php endif;?> /></span>
						<label for="image_type" ><?php print $strimagetype; ?></label><br/>
						<div class="borderBot"></div>
						<span class="niceCheck"><input type="checkbox" id="video_type" name="type_event" value="video" <?php if (empty($type) || in_array('v', $type)) :?>checked="checked" <?php endif;?>/></span>
						<label for="video_type" ><?php print $strvideotype; ?></label><br/>
						<div class="borderBot"></div>
						<span class="niceCheck"><input type="checkbox" id="audio_type" name="type_event" value="audio" <?php if (empty($type) || in_array('a', $type)) :?>checked="checked" <?php endif;?>/></span>
						<label for="audio_type" ><?php print $straudiotype; ?></label>						
						
					</div>
				
				<!--	<div id="favorite">
						<input type="checkbox" id="input_favorite" name="favorite" value="favorite">
						<label for="input_favorite">Отмеченные</label>
		 			</div>-->
					<div id="tree">
						<div id="tree_new">
						</div>
					</div>
					
					<div id="statistics">
					</div>
				</div>
				<!-- end sidebar inner block -->
				
				<div class="handler" id="handler_vertical" style=""></div>
			</div>
			
			<div id="content">
				<div class="window">
					<div id="win_top" >
						
						<div id="cameras_selector" class="field checkboxes">
							<div class="options">
							
							<?php 
								if (isset($cookies['cameras'])) {
									$cameras = explode(',', trim($cookies['cameras'], ','));
								}
							?>
							<?php foreach ($GCP_cams_params as $CAM_NR => $PARAM) :?>
								<span style="width: 200px; white-space: nowrap; text-align: center;">
								<span class="niceCheck">
									<input type="checkbox" id="camera_<?php print $CAM_NR;?>" name="cameras" value="<?php print $CAM_NR;?>" <?php if (empty($cameras) || in_array($CAM_NR, $cameras)) :?>checked="checked" <?php endif;?>></span>
									
									<label  style="float: none !important;" for="camera_<?php print $CAM_NR;?>"><a href="#<?php print $CAM_NR;?>" class="set_camera_color<?php if (isset($cookies['camera_'.$CAM_NR.'_color']) && !empty($cookies['camera_'.$CAM_NR.'_color']) ): print ' '.$cookies['camera_'.$CAM_NR.'_color'] . '_font'; endif;?>"><?php
									$name = $PARAM['text_left'];
									if($CAM_NR==2)
										$name = 'длинный текст'.$name; 
									if(mb_strlen($name)>18) {
										$name = mb_substr($name, 0, 15);
										$name .= '...';
									} 
									print $name; 
									?></a></label>
								</span>
								
							<?php endforeach;?>
							
														<?php foreach ($GCP_cams_params as $CAM_NR => $PARAM) :?>
								<span style="width: 200px; white-space: nowrap; text-align: center;">
								<span class="niceCheck">
									<input type="checkbox" id="camera_<?php print $CAM_NR;?>" name="cameras" value="<?php print $CAM_NR;?>" <?php if (empty($cameras) || in_array($CAM_NR, $cameras)) :?>checked="checked" <?php endif;?>></span>
									
									<label  style="float: none !important;" for="camera_<?php print $CAM_NR;?>"><a href="#<?php print $CAM_NR;?>" class="set_camera_color<?php if (isset($cookies['camera_'.$CAM_NR.'_color']) && !empty($cookies['camera_'.$CAM_NR.'_color']) ): print ' '.$cookies['camera_'.$CAM_NR.'_color'] . '_font'; endif;?>"><?php
									$name = $PARAM['text_left'];
									if($CAM_NR==2)
										$name = 'длинный текст'.$name; 
									if(mb_strlen($name)>18) {
										$name = mb_substr($name, 0, 15);
										$name .= '...';
									} 
									print $name; 
									?></a></label>
								</span>
								
							<?php endforeach;?>
							
														<?php foreach ($GCP_cams_params as $CAM_NR => $PARAM) :?>
								<span style="width: 200px; white-space: nowrap; text-align: center;">
								<span class="niceCheck">
									<input type="checkbox" id="camera_<?php print $CAM_NR;?>" name="cameras" value="<?php print $CAM_NR;?>" <?php if (empty($cameras) || in_array($CAM_NR, $cameras)) :?>checked="checked" <?php endif;?>></span>
									
									<label  style="float: none !important;" for="camera_<?php print $CAM_NR;?>"><a href="#<?php print $CAM_NR;?>" class="set_camera_color<?php if (isset($cookies['camera_'.$CAM_NR.'_color']) && !empty($cookies['camera_'.$CAM_NR.'_color']) ): print ' '.$cookies['camera_'.$CAM_NR.'_color'] . '_font'; endif;?>"><?php
									$name = $PARAM['text_left'];
									if($CAM_NR==2)
										$name = 'длинный текст'.$name; 
									if(mb_strlen($name)>18) {
										$name = mb_substr($name, 0, 15);
										$name .= '...';
									} 
									print $name; 
									?></a></label>
								</span>
								
							<?php endforeach;?>
														<?php foreach ($GCP_cams_params as $CAM_NR => $PARAM) :?>
								<span style="width: 200px; white-space: nowrap; text-align: center;">
								<span class="niceCheck">
									<input type="checkbox" id="camera_<?php print $CAM_NR;?>" name="cameras" value="<?php print $CAM_NR;?>" <?php if (empty($cameras) || in_array($CAM_NR, $cameras)) :?>checked="checked" <?php endif;?>></span>
									
									<label  style="float: none !important;" for="camera_<?php print $CAM_NR;?>"><a href="#<?php print $CAM_NR;?>" class="set_camera_color<?php if (isset($cookies['camera_'.$CAM_NR.'_color']) && !empty($cookies['camera_'.$CAM_NR.'_color']) ): print ' '.$cookies['camera_'.$CAM_NR.'_color'] . '_font'; endif;?>"><?php
									$name = $PARAM['text_left'];
									if($CAM_NR==2)
										$name = 'длинный текст'.$name; 
									if(mb_strlen($name)>18) {
										$name = mb_substr($name, 0, 15);
										$name .= '...';
									} 
									print $name; 
									?></a></label>
								</span>
								
							<?php endforeach;?>
														<?php foreach ($GCP_cams_params as $CAM_NR => $PARAM) :?>
								<span style="width: 200px; white-space: nowrap; text-align: center;">
								<span class="niceCheck">
									<input type="checkbox" id="camera_<?php print $CAM_NR;?>" name="cameras" value="<?php print $CAM_NR;?>" <?php if (empty($cameras) || in_array($CAM_NR, $cameras)) :?>checked="checked" <?php endif;?>></span>
									
									<label  style="float: none !important;" for="camera_<?php print $CAM_NR;?>"><a href="#<?php print $CAM_NR;?>" class="set_camera_color<?php if (isset($cookies['camera_'.$CAM_NR.'_color']) && !empty($cookies['camera_'.$CAM_NR.'_color']) ): print ' '.$cookies['camera_'.$CAM_NR.'_color'] . '_font'; endif;?>"><?php
									$name = $PARAM['text_left'];
									if($CAM_NR==2)
										$name = 'длинный текст'.$name; 
									if(mb_strlen($name)>18) {
										$name = mb_substr($name, 0, 15);
										$name .= '...';
									} 
									print $name; 
									?></a></label>
								</span>
								
							<?php endforeach;?>
							<?php foreach ($GCP_cams_params as $CAM_NR => $PARAM) :?>
								<span style="width: 200px; white-space: nowrap; text-align: center;">
								<span class="niceCheck">
									<input type="checkbox" id="camera_<?php print $CAM_NR;?>" name="cameras" value="<?php print $CAM_NR;?>" <?php if (empty($cameras) || in_array($CAM_NR, $cameras)) :?>checked="checked" <?php endif;?>></span>
									
									<label  style="float: none !important;" for="camera_<?php print $CAM_NR;?>"><a href="#<?php print $CAM_NR;?>" class="set_camera_color<?php if (isset($cookies['camera_'.$CAM_NR.'_color']) && !empty($cookies['camera_'.$CAM_NR.'_color']) ): print ' '.$cookies['camera_'.$CAM_NR.'_color'] . '_font'; endif;?>"><?php
									$name = $PARAM['text_left'];
									if($CAM_NR==2)
										$name = 'длинный текст'.$name; 
									if(mb_strlen($name)>18) {
										$name = mb_substr($name, 0, 15);
										$name .= '...';
									} 
									print $name; 
									?></a></label>
								</span>
								
							<?php endforeach;?>
							<?php foreach ($GCP_cams_params as $CAM_NR => $PARAM) :?>
								<span style="width: 200px; white-space: nowrap; text-align: center;">
								<span class="niceCheck">
									<input type="checkbox" id="camera_<?php print $CAM_NR;?>" name="cameras" value="<?php print $CAM_NR;?>" <?php if (empty($cameras) || in_array($CAM_NR, $cameras)) :?>checked="checked" <?php endif;?>></span>
									
									<label  style="float: none !important;" for="camera_<?php print $CAM_NR;?>"><a href="#<?php print $CAM_NR;?>" class="set_camera_color<?php if (isset($cookies['camera_'.$CAM_NR.'_color']) && !empty($cookies['camera_'.$CAM_NR.'_color']) ): print ' '.$cookies['camera_'.$CAM_NR.'_color'] . '_font'; endif;?>"><?php
									$name = $PARAM['text_left'];
									if($CAM_NR==2)
										$name = 'длинный текст'.$name; 
									if(mb_strlen($name)>18) {
										$name = mb_substr($name, 0, 15);
										$name .= '...';
									} 
									print $name; 
									?></a></label>
								</span>
								
							<?php endforeach;?>
							<?php foreach ($GCP_cams_params as $CAM_NR => $PARAM) :?>
								<span style="width: 200px; white-space: nowrap; text-align: center;">
								<span class="niceCheck">
									<input type="checkbox" id="camera_<?php print $CAM_NR;?>" name="cameras" value="<?php print $CAM_NR;?>" <?php if (empty($cameras) || in_array($CAM_NR, $cameras)) :?>checked="checked" <?php endif;?>></span>
									
									<label  style="float: none !important;" for="camera_<?php print $CAM_NR;?>"><a href="#<?php print $CAM_NR;?>" class="set_camera_color<?php if (isset($cookies['camera_'.$CAM_NR.'_color']) && !empty($cookies['camera_'.$CAM_NR.'_color']) ): print ' '.$cookies['camera_'.$CAM_NR.'_color'] . '_font'; endif;?>"><?php
									$name = $PARAM['text_left'];
									if($CAM_NR==2)
										$name = 'длинный текст'.$name; 
									if(mb_strlen($name)>18) {
										$name = mb_substr($name, 0, 15);
										$name .= '...';
									} 
									print $name; 
									?></a></label>
								</span>
								
							<?php endforeach;?>
							
							<?php /* foreach ($GCP_cams_params as $CAM_NR => $PARAM) :?>
							
								<span style="width:250px !important;  ">
									<label style="width:225px !important; overflow: hidden; text-overflow: ellipsis;white-space: nowrap;text-align: center;" for="camera_<?php print $CAM_NR;?>"><a href="#<?php print $CAM_NR;?>" class="set_camera_color<?php if (isset($cookies['camera_'.$CAM_NR.'_color']) && !empty($cookies['camera_'.$CAM_NR.'_color']) ): print ' '.$cookies['camera_'.$CAM_NR.'_color'] . '_font'; endif;?>"><?php print $PARAM['text_left'].$PARAM['text_left']; ?></a></label>
									<input type="checkbox" id="camera_<?php print $CAM_NR;?>" name="cameras" value="<?php print $CAM_NR;?>" <?php if (empty($cameras) || in_array($CAM_NR, $cameras)) :?>checked="checked" <?php endif;?>>
								</span>
							<?php endforeach;*/ ?>
							</div>
							
						</div>
						<div id="more_cam">...</div>																				
					</div>
					<div id="win_bot" class="matrix_mode">
						<div id="list_panel">
							<div id="scroll_content"></div>
						</div>	
						<div id="scroll_v">
							<div class="scroll_top_v"></div>
							<div class="scroll_body_v">
								<div class="scroll_polz_v"></div>
							</div>
							<div class="scroll_bot_v"></div>
						</div>
											</div>
				
					<div id="win_bot_detail" class="matrix_mode">
							<a href="#preview">
								<img id="image_detail" src=""/>
							</a>
					</div>
				
					<div id="toolbar" >
						<div id="toolbar_left">
						<div class="propotion controls">
						
								<span class="niceCheck"><input type="checkbox" id="proportion" name="proportion" value="1" <?php if (isset($cookies['proportion']) && $cookies['proportion'] == 'checked') :?>checked="checked" <?php endif;?>></span>
								<label for="proportion"><?php print $strproportion; ?></label>
							
						</div>
						<div class="event_info preview controls">
								<span class="niceCheck"><input type="checkbox" id="info" name="info" value="1" <?php if (!isset($cookies['info']) || $cookies['info'] == 'checked') :?>checked="checked" <?php endif;?>></span>
								<label for="info"><?php print $strinfo; ?></label>
						</div>
						</div>
						<div id="toolbar_right">
						<div  id="scale" class="preview controls">
								<div class="scale_min"></div>
								<div class="scale_body">
								<div class="scale_polz"></div>
								</div>
								<div class="scale_max"></div>
						</div>

						<div class="controls prevnext">
							<a class="prew" href="#"><img src="/avreg/offline/gallery/img/arrow_left.png" /></a>
							<a class="next" href="#"><img src="/avreg/offline/gallery/img/arrow_right.png" /></a>
						</div>						
						
						<div  id="scale2" class="detail controls">
								<div class="scale_min"></div>
								<div class="scale_body">
								<div class="scale_polz"></div>
								</div>
								<div class="scale_max"></div>
						</div>
						<img id="scale_image" src="/avreg/offline/gallery/img/lupa.png" />

						</div>
					</div>		
				</div>
			</div> 
		</div>


<div id="overlay"></div>

<div id="cameras_color" class="mod_window">
	<div class="window_title">
		<h2><?php print $strcolorcameras; ?></h2>
	</div>
	<div class="window_body">
		<ul>
			<li class="camera_1_color"></li>
			<li class="camera_2_color"></li>
			<li class="camera_3_color"></li>
			<li class="camera_4_color"></li>
			<li class="camera_5_color"></li>
			<li class="camera_6_color"></li>
			<li class="camera_7_color"></li>
			<li class="camera_8_color"></li>
			<li class="camera_9_color"></li>
			<li class="camera_10_color"></li>
			<li class="camera_11_color"></li>
			<li class="camera_12_color"></li>
			<li class="camera_13_color"></li>
			<li class="camera_14_color"></li>
			<li class="camera_15_color"></li>
			<li class="camera_16_color"></li>
			
		
		</ul>
	</div>
	<div class="window_button">
		<button class="close"><?php print $strclose; ?></button>
	</div>
</div>

<div id="nextwindow" class="mod_window next_window">
	<div class="window_title">
		<h2><?php print $strnextwindow; ?></h2>
	</div>
	<div class="window_body">

		<span class="niceCheck"><input type="checkbox" id="checknextwindow" name="checknextwindow" value="1"></span>
		<label for="checknextwindow"><?php print $strchecknextwindow; ?></label>

	</div>
	<div class="window_button">
		<button class="no"><?php print $strno; ?></button>
		<button class="yes"><?php print $stryes; ?></button>
	</div>
</div>


<script type="text/javascript">
var MediaUrlPref = WwwPrefix + MediaAlias + '\/';

// формирование глобального объекта перевода
var lang = {
		
		all : '<?php print $strall; ?>',
		count_files: '<?php print $strcount_files; ?>',	
		size_files: '<?php print $strsize_files; ?>',	
		date_from: '<?php print $strdate_from; ?>',	
		date_to: '<?php print $strdate_to; ?>',	
		camera: '<?php print $strcamera; ?>',	
		size: '<?php print $strsize; ?>',	
		WH: '<?php print $strWH; ?>',	
		date: '<?php print $strdate; ?>',	
		empty_cameras: '<?php print $strempty_cameras; ?>',
		empty_event: '<?php print $strempty_event; ?>',
		ajax_timeout : '<?php print $strajax_timeout; ?>'	
	};
	// обработка размера файлов
var units = ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
function readableFileSize(size) {
    var i = 0;
    while(size >= 1024) {
        size /= 1024;
        ++i;
    }
    return size.toFixed(1) + ' ' + units[i];
}

$(function(){
	// переопределение настроек
	var conf = {
			matrix : {
				limit : <?php print $conf['gallery-limit'];?>,
				event_limit : <?php print isset($conf['gallery-cache_event_limit']) ? $conf['gallery-cache_event_limit'] : 20000;?>,
				min_cell_width : <?php print $conf['gallery-min_cell_width'];?> ,
				min_cell_height : <?php print $conf['gallery-min_cell_height'];?> 
			},
			show_timeout : <?php print isset($conf['gallery-show_timeout']) ? $conf['gallery-show_timeout'] : 1 ;?>
	};

	// инициализация галереи	
	gallery.init(conf);
});
</script>
		
