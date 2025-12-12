<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if(isset($_POST['do'])){
    require_once 'classes/ads_class.php';
    require_once 'classes/public_functions.php';
    $ads_ptr = new ADS();
    
    $ignore = ['™', '®'];
    
    if(isset($_POST['do']) && $_POST['do'] == 'check_duplicate'){
        $search_term = $_POST['gameName'];
        $search_term = str_replace($ignore, '', $search_term);
        $res = $ads_ptr->searchForDuplicateGames($search_term);
       
        if(count($res) > 0){
            echo 'duplicate';
        }
    }
    elseif(isset($_POST['do']) && $_POST['do'] == 'add_new_game'){
            $game_name = $_POST['gameName'];
            $game_name = str_replace($ignore, '', $game_name);
            $photo_url = $_POST['photoUrl'];
            $photo_local_name = uniqid().'.jpg';
           $ads_ptr->addNewGameToList($game_name, $photo_url, $photo_local_name);
           echo 'success';
           
        // مسیر ذخیره‌سازی عکس در سرور
        $savePath = 'img/games_thumb_img/'.$photo_local_name;
        
        // دریافت محتوای عکس از URL
        $imageData = file_get_contents(str_replace('620', '360', $photo_url));
        
        if ($imageData !== false) {
            // ذخیره‌سازی عکس در مسیر مشخص
            if (file_put_contents($savePath, $imageData) !== false) {
                echo 'عکس با موفقیت ذخیره شد.';
            } else {
                echo 'خطا در ذخیره‌سازی عکس.';
            }
        } else {
            echo 'خطا در دریافت عکس از URL.';
        }
    }
    elseif(isset($_POST['do']) && $_POST['do'] == 'game_name_search'){
        $search_term = $_POST['term'];
        $res = $ads_ptr->searchInGamesList($search_term);
        
        if(count($res) > 0){
            for($i=0; $i<count($res); $i++){
                echo '<p class="shown_game_name" data-game_id="'.$res[$i]['auto_id'].'"><i class="bi bi-controller"></i> ' . $res[$i]['game_en_title'] . '</p>';
            }
        }
        else{
            echo '<p class="alert alert-danger">بازی با این نام پیدا نشد</p>';
        }
    }
    elseif(isset($_POST['do']) && $_POST['do'] == 'add_new_ads'){
            $data['tel_user_id'] = $_POST['tel_user_id'];
            $data['tel_username'] = $_POST['tel_username'];
            $data['game_id'] = $_POST['gameId'];
            $data['price'] = str_replace(',', '', $_POST['gamePrice']);
            $data['platform'] = $_POST['platform'];
            $data['capacity'] = $_POST['capacity'];
            $data['region'] = $_POST['region'];
            $data['description'] = $_POST['description'];
            
            if($ads_ptr->addNewAds($data)) echo 'success';
    }
    elseif(isset($_POST['do']) && $_POST['do'] == 'save_in_session'){
        $_SESSION['user_id'] = $_POST['user_id'];
    }    
    elseif(isset($_POST['do']) && $_POST['do'] == 'ads_search'){
        
        $search_term = $_POST['term'];
        $limit = 5;
        
        if(empty($search_term))
            $confirmed_ads = $ads_ptr->getConfirmedAds($limit);
        else    
            $confirmed_ads = $ads_ptr->searchInAds($limit, $search_term);
        
        if (count($confirmed_ads) > 0) {
            $console_icon = 'ps5_icon.png';
            $output = ''; // متغیر جدید برای ذخیره خروجی
            for ($i = 0; $i < count($confirmed_ads); $i++) {
			    if($confirmed_ads[$i]['platform'] == 'PS4') $console_icon = 'ps4_icon.png';                
                $output .= ' <div class="ads_main_div container-fluid">
                    <h4 class="game_title">' . htmlspecialchars($confirmed_ads[$i]['game_en_title']) . '</h4>
                    <div class="row container">
                        <div class="col-7">
                            <h5><i class="bi bi-coin"></i> قیمت: <br> ' . number_format($confirmed_ads[$i]['price']) . ' تومان</h5>
                            <h5><i class="bi bi-controller"></i> ظرفیت: ' . htmlspecialchars($confirmed_ads[$i]['capacity']) . '</h5>
                            <h5><img src="img/' . htmlspecialchars($console_icon) . '" alt="" class="console_icon"> کنسول: ' . htmlspecialchars($confirmed_ads[$i]['platform']) . '</h5>
                            <h5>ریجن: ' . htmlspecialchars(getRegionAlias($confirmed_ads[$i]['region'])) . '</h5>
                        </div>
                        <div class="col-5"><img class="game_photo" src="img/games_thumb_img/' . htmlspecialchars($confirmed_ads[$i]['photo_local_name']) . '" alt=""></div>
                    </div>';
        
                if (!empty($confirmed_ads[$i]['description'])) {
                    $output .= '
                        <small class="col-12"><span class="fw-bold"><i class="bi bi-chat-square-quote"></i> توضیحات:</span> <br> <span class="pe-2">' . htmlspecialchars($confirmed_ads[$i]['description']) . '</span></small>';
                }
        
                $output .= '
                    <div class="row container ads_info">
                        <div class="col-7"><i class="bi bi-calendar"></i> ' . human_readable_time_difference($confirmed_ads[$i]['reg_date'] . ' ' . $confirmed_ads[$i]['reg_time'], 'd h i') . '</div>
                        <div class="col-5"><i class="bi bi-eye"></i> ' . number_format($confirmed_ads[$i]['view']) . ' بازدید</div>
                    </div>
                    <a class="contact_btn" href="https://t.me/' . htmlspecialchars($confirmed_ads[$i]['tel_username']) . '"><button class="col-12 btn btn-primary"><i class="bi bi-telegram"></i> تماس با فروشنده</button></a></div>';
            }
            echo $output; // نمایش خروجی نهایی
        }
        else{
             echo '<p class="alert alert-danger">بازی با این نام پیدا نشد</p>';
        }
    }
    elseif(isset($_POST['do']) && $_POST['do'] == 'load_more_ads'){
        $filter_platform = $_POST['filter_platform'];
        $filter_region = $_POST['filter_region'];
        $filter_capacity = $_POST['filter_capacity'];
		$counter = $_POST['counter'];
        $limit = 5;
		
		
        if(!empty($filter_platform) || !empty($filter_region) || !empty($filter_capacity)){
            // $limit = 20;
        }
    
		$data = [
			'counter' => $counter, 
			'limit' => $limit, 
			'filter_platform' => $filter_platform, 
			'filter_region' => $filter_region, 
			'filter_capacity' => $filter_capacity
		];
        $confirmed_ads = $ads_ptr->loadMoreConfirmedAds($data);
        
        if (count($confirmed_ads) > 0) {
            $console_icon = 'ps5_icon.png';
            $output = ''; // متغیر جدید برای ذخیره خروجی
            $capacity = '';
            
            for ($i = 0; $i < count($confirmed_ads); $i++) {
                $capacity = ($confirmed_ads[$i]['capacity'] == 'full') ? 'کامل' : $confirmed_ads[$i]['capacity'];
			    if($confirmed_ads[$i]['platform'] == 'PS4') $console_icon = 'ps4_icon.png';                
                $output .= ' <div class="ads_main_div container-fluid">
                    <h4 class="game_title">' . htmlspecialchars($confirmed_ads[$i]['game_en_title']) . '</h4>
                    <div class="row container">
                        <div class="col-7">
                            <h5><i class="bi bi-coin"></i> قیمت: <br> ' . number_format($confirmed_ads[$i]['price']) . ' تومان</h5>
                            <h5><i class="bi bi-controller"></i> ظرفیت: ' . $capacity . '</h5>
                            <h5><img src="img/' . htmlspecialchars($console_icon) . '" alt="" class="console_icon"> کنسول: ' . $confirmed_ads[$i]['platform'] . '</h5>
                            <h5>ریجن: ' .getRegionAlias($confirmed_ads[$i]['region']) . '</h5>
                        </div>
                        <div class="col-5"><img class="game_photo" src="img/games_thumb_img/' . htmlspecialchars($confirmed_ads[$i]['photo_local_name']) . '" alt=""></div>
                    </div>';
        
                if (!empty($confirmed_ads[$i]['description'])) {
                    $output .= '
                        <small class="col-12"><span class="fw-bold"><i class="bi bi-chat-square-quote"></i> توضیحات:</span> <br> <span class="pe-2">' . htmlspecialchars($confirmed_ads[$i]['description']) . '</span></small>';
                }
        
                $output .= '
                    <div class="row container ads_info">
                        <div class="col-7"><i class="bi bi-calendar"></i> ' . human_readable_time_difference($confirmed_ads[$i]['reg_date'] . ' ' . $confirmed_ads[$i]['reg_time'], 'd h i') . '</div>
                        <div class="col-5"><i class="bi bi-eye"></i> ' . number_format($confirmed_ads[$i]['view']) . ' بازدید</div>
                    </div>
                    <a class="contact_btn" href="https://t.me/' . htmlspecialchars($confirmed_ads[$i]['tel_username']) . '"><button class="col-12 btn btn-primary"><i class="bi bi-telegram"></i> تماس با فروشنده</button></a></div>';
            }
            echo $output; // نمایش خروجی نهایی
        }
        else{
            echo 'no_more_ads';
        }
    }
}