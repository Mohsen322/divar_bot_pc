<?php
if(isset($_POST['do'])){
    require_once 'classes/my_ads_class.php';
    require_once 'classes/public_functions.php';
    $ads_ptr = new USER_ADS();
    
    if(isset($_POST['do']) && $_POST['do'] == 'my_ads_search'){
        
        $search_term = $_POST['term'];
        $user_id = $_POST['user_id'];
        $limit = 5;
        
        if(empty($search_term))
            $confirmed_ads = $ads_ptr->getUserdAds($limit, $user_id);
        else    
            $confirmed_ads = $ads_ptr->searchInUserAds($limit, $search_term, $user_id);
        
        if (count($confirmed_ads) > 0) {
            $console_icon = 'ps5_icon.png';
            $output = ''; // متغیر جدید برای ذخیره خروجی
            for ($i = 0; $i < count($confirmed_ads); $i++) {
			    if($confirmed_ads[$i]['platform'] == 'PS4') $console_icon = 'ps4_icon.png';                
                ?>
		  <div class="ads_main_div container-fluid" id="ads_details<?php echo $confirmed_ads[$i]['ads_id'] ?>">
			<h4 class="game_title"><?php echo $confirmed_ads[$i]['game_en_title'] ?></h4>
			  <div class="row container">
				  <div class="col-7">
					  <h5><i class="bi bi-coin"></i> قیمت: <br> <?php echo number_format($confirmed_ads[$i]['price']) ?> تومان</h5>
					  <h5><i class="bi bi-controller"></i> ظرفیت: <?php echo ($confirmed_ads[$i]['capacity'] == 'full') ? 'کامل' : $confirmed_ads[$i]['capacity'] ?></h5>
					  <h5><img src="img/<?php echo $console_icon ?>" alt="" class="console_icon"> کنسول: <?php echo $confirmed_ads[$i]['platform'] ?></h5>
					  <h5>ریجن: <?php echo getRegionAlias($confirmed_ads[$i]['region']) ?></h5>
				</div>
				<div class="col-5"><img class="game_photo" src="img/games_thumb_img/<?php echo $confirmed_ads[$i]['photo_local_name'] ?>" alt=""></div>				  
			  </div>
			  <?php if(!empty($confirmed_ads[$i]['description'])){ ?>
			  		<small class="col-12"><span class="fw-bold"><i class="bi bi-chat-square-quote"></i> توضیحات:</span> <br> <span class="pe-2"><?php echo $confirmed_ads[$i]['description'] ?></span></small>
				<?php } ?>

			    <div class="row container ads_info">
					<div class="col-7"><i class="bi bi-calendar"></i></i> <?php echo human_readable_time_difference($confirmed_ads[$i]['reg_date'].' '.$confirmed_ads[$i]['reg_time'], 'd h i') ?></div>
					<div class="col-5"><i class="bi bi-eye"></i> <?php echo number_format($confirmed_ads[$i]['view']) ?> بازدید</div>
				</div>	
				
				<div class="container row" style="margin:0">
                    <button class="col-8 btn btn-success edit_ads" data-ads_id="<?php echo $confirmed_ads[$i]['ads_id'] ?>" data-price="<?php echo $confirmed_ads[$i]['price'] ?>" data-platform="<?php echo $confirmed_ads[$i]['platform'] ?>" data-region="<?php echo $confirmed_ads[$i]['region'] ?>" data-capacity="<?php echo $confirmed_ads[$i]['capacity'] ?>" data-description="<?php echo $confirmed_ads[$i]['description'] ?>"><i class="bi bi-pencil-square"></i> ویرایش</button>
                    <button class="col-4 btn btn-danger delete_ads" data-ads_id="<?php echo $confirmed_ads[$i]['ads_id'] ?>"><i class="bi bi-x-square"></i> حذف</button>
</div>
		  </div>                 
                <?php
        }
        }
        else{
             echo '<p class="alert alert-danger">بازی با این نام پیدا نشد</p>';
        }
    }
    elseif(isset($_POST['do']) && $_POST['do'] == 'edit_ads'){
            $data['ads_id'] = $_POST['ads_id'];
            $data['price'] = str_replace(',', '', $_POST['gamePrice']);
            $data['platform'] = $_POST['platform'];
            $data['capacity'] = $_POST['capacity'];
            $data['region'] = $_POST['region'];
            $data['description'] = $_POST['description'];
            $data['user_id'] = $_POST['user_id'];
            
            if($ads_ptr->editAds($data)) echo 'success';
    }    
    elseif(isset($_POST['do']) && $_POST['do'] == 'delete_ads'){
            $data['ads_id'] = $_POST['ads_id'];
            $data['user_id'] = $_POST['user_id'];
            
            if($ads_ptr->deleteAds($data)) echo 'success';
    }      
    elseif(isset($_POST['do']) && $_POST['do'] == 'load_more_ads'){
        $filter_platform = $_POST['filter_platform'];
        $filter_region = $_POST['filter_region'];
        $filter_capacity = $_POST['filter_capacity'];
        $user_id = $_POST['user_id'];
		$counter = $_POST['counter'];
        $limit = 3;
		
        if(!empty($filter_platform) || !empty($filter_region) || !empty($filter_capacity)){
            // $limit = 20;
        }
    
		$data = [
			'counter' => $counter, 
			'limit' => $limit, 
			'filter_platform' => $filter_platform, 
			'filter_region' => $filter_region, 
			'filter_capacity' => $filter_capacity,
			'user_id' => $user_id
		];
        $confirmed_ads = $ads_ptr->loadMoreMyConfirmedAds($data);
        
        if (count($confirmed_ads) > 0) {
            $console_icon = 'ps5_icon.png';
            $output = ''; // متغیر جدید برای ذخیره خروجی
            $capacity = '';
            
            for ($i = 0; $i < count($confirmed_ads); $i++) {
                ?>
		  <div class="ads_main_div container-fluid" id="ads_details<?php echo $confirmed_ads[$i]['ads_id'] ?>">
			<h4 class="game_title"><?php echo $confirmed_ads[$i]['game_en_title'] ?></h4>
			  <div class="row container">
				  <div class="col-7">
					  <h5><i class="bi bi-coin"></i> قیمت: <br> <?php echo number_format($confirmed_ads[$i]['price']) ?> تومان</h5>
					  <h5><i class="bi bi-controller"></i> ظرفیت: <?php echo ($confirmed_ads[$i]['capacity'] == 'full') ? 'کامل' : $confirmed_ads[$i]['capacity'] ?></h5>
					  <h5><img src="img/<?php echo $console_icon ?>" alt="" class="console_icon"> کنسول: <?php echo $confirmed_ads[$i]['platform'] ?></h5>
					  <h5>ریجن: <?php echo getRegionAlias($confirmed_ads[$i]['region']) ?></h5>
				</div>
				<div class="col-5"><img class="game_photo" src="img/games_thumb_img/<?php echo $confirmed_ads[$i]['photo_local_name'] ?>" alt=""></div>				  
			  </div>
			  <?php if(!empty($confirmed_ads[$i]['description'])){ ?>
			  		<small class="col-12"><span class="fw-bold"><i class="bi bi-chat-square-quote"></i> توضیحات:</span> <br> <span class="pe-2"><?php echo $confirmed_ads[$i]['description'] ?></span></small>
				<?php } ?>

			    <div class="row container ads_info">
					<div class="col-7"><i class="bi bi-calendar"></i></i> <?php echo human_readable_time_difference($confirmed_ads[$i]['reg_date'].' '.$confirmed_ads[$i]['reg_time'], 'd h i') ?></div>
					<div class="col-5"><i class="bi bi-eye"></i> <?php echo number_format($confirmed_ads[$i]['view']) ?> بازدید</div>
				</div>	
				
				<div class="container row" style="margin:0">
                    <button class="col-8 btn btn-success edit_ads" data-ads_id="<?php echo $confirmed_ads[$i]['ads_id'] ?>" data-price="<?php echo $confirmed_ads[$i]['price'] ?>" data-platform="<?php echo $confirmed_ads[$i]['platform'] ?>" data-region="<?php echo $confirmed_ads[$i]['region'] ?>" data-capacity="<?php echo $confirmed_ads[$i]['capacity'] ?>" data-description="<?php echo $confirmed_ads[$i]['description'] ?>"><i class="bi bi-pencil-square"></i> ویرایش</button>
                    <button class="col-4 btn btn-danger delete_ads" data-ads_id="<?php echo $confirmed_ads[$i]['ads_id'] ?>"><i class="bi bi-x-square"></i> حذف</button>
</div>
		  </div>                
                <?php
            }
        }
        else{
            echo 'no_more_ads';
        }
    }
}