<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);    
    
	require_once 'classes/my_ads_class.php';
    $ads_ptr = new USER_ADS();

	$limit = 5;
	$user_id = $_SESSION['user_id'];
// 	$user_id = 98569171;
	$confirmed_ads = $ads_ptr->getUserdAds($limit, $user_id);

    require_once 'classes/public_functions.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ربات دیوار گیمینگ | مدیریت آگهی‌ها</title>
	<script src="js/telegram-web-app-56.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/main.css?745998">
  </head>
	
  <body dir="rtl">
	  <?php require_once ('top_fix_section.php') ?>
	  
	<div id="search_result_ads_main_div" class="container-fluid text-center" style="display: none">
		<h5>نتایج جستجو:</h5>
		<div id="search_result_ads_div" class="container-fluid text-center"></div>
	</div>
	 
    <div id="ads_main_div" class="container-fluid text-center">
		<h5>جدیدترین آگهیهای ثبت شده شما</h5>
		<div id="ads_div" class="container-fluid text-center">
		<?php 
		for($i=0; $i<count($confirmed_ads); $i++){
			$console_icon = 'ps5_icon.png';
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
		<?php } ?>
    </div>	  
 </div>	  
    <!-- Floating Button -->
<!--    <button class="floating-button" id="openAddFormButton"><i class="bi bi-plus"></i></button>-->
    <!-- Form Popup -->
    
    <div id="go_to_top"><i class="bi bi-rocket"></i></div>
    
    <div class="form-popup" id="editAdsForm">
      <div class="form-container">
        <button class="close-btn" id="closeEditFormButton">&times;</button>
        
       <input type="text" id="editGamePrice" class="numberFormat" placeholder="قیمت به تومان" required pattern="\d*" inputmode="numeric"> <!-- فقط عدد -->
		  <div id="editGamePriceError" class="error-message">درج قیمت ضروری است.</div>
		  
        <select id="editPlatform" required>
          <option value="PS5">PS5</option>
          <option value="PS4">PS4</option>
        </select>
        
        <select id="editCapacity" required>
          <option value="1">ظرفیت 1</option>
          <option value="2">ظرفیت 2</option>
          <option value="3">ظرفیت 3</option>
          <option value="full">ظرفیت کامل</option>
        </select>
        
        <select id="editRegion" required>
          <option value="turkey">ترکیه</option>
          <option value="usa">آمریکا</option>
          <option value="japan">ژاپن</option>
          <option value="ukrain">اوکراین</option>
          <option value="all">ریجن آل</option>
        </select>
         
        <textarea id="editDescription" placeholder="توضیحات"></textarea> <!-- فیلد توضیحات با ارتفاع بیشتر -->
        <button type="submit" class="btn" id="editAdsBtn" data-ads_id="0"><i class="bi bi-pencil-square"></i> ویرایش آگهی</button>
      </div>
    </div>
	
	<button class="col-12 btn btn-warning" id="show_more_ads" data-counter="1" data-filter_capacity="" data-filter_region="" data-filter_platform=""><i class="bi bi-download"></i> مشاهده آگهیهای بیشتر از شما</button>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="js/sweetalert2@11.js"></script>
    <script src="js/public.js"></script>
    <script>
		
      $(document).ready(function() {
		  var webapp = window.Telegram.WebApp;
		  
		  webapp.BackButton.show();
		  
        webapp.BackButton.onClick(() => {
            window.location.href = '/WebAppBot/index.php';
        });		  
		  
		$("#first_name").text(first_name);
		$("#profile_pic").attr('src', photo_url);
		  
        $("#openAddFormButton").click(function() {
          $("#addAdsForm").css("display", "flex");
			if(username === "" || username == null){
				Swal.fire({
					title: 'داشتن راه ارتباطی الزامیست!',
					text: 'از نام کاربری تلگرامی شما به عنوان راه ارتباطی بین خریدار و فروشنده استفاده می شود. لطفا ابتدا یک نام کاربری برای اکانت تلگرامی خود انتخاب کنید و بعد از باز کردن مجدد ربات، دوباره اقدام به درج آگهی نمائید.',
					icon: 'info',
					confirmButtonText: 'OK'
				});
				$('#submitForm').attr('disabled', '');
			}			
        });

        $("#closeFormButton").click(function() {
//          $("#addAdsForm").css("display", "none");
          $("#addAdsForm").fadeOut();
        });
        
        $("#closeEditFormButton").click(function() {
//          $("#editAdsForm").css("display", "none");
          $("#editAdsForm").fadeOut();
        });
        
        // $(".edit_ads").click(function() {
        $(document).on('click', '.edit_ads', function() {
          var ads_id = $(this).data('ads_id');
          var platform = $(this).data('platform');
          var region = $(this).data('region');
          var capacity = $(this).data('capacity');
          var description = $(this).data('description');
          var price = $(this).data('price');
			
		  $("#editAdsBtn").data('ads_id', ads_id); // set ads id fot edit button
			
            $("#editPlatform option").each(function() {
                if ($(this).val() === platform) {
                    $(this).prop("selected", true);
                    return false;
                }
            });
			
            $("#editCapacity option").each(function() {
                console.log("Option value: ", $(this).val());
                if ($(this).val() == capacity) {
                    console.log("Selected value: ", $(this).val());
                    $(this).prop("selected", true);
                    return false;
                }
            });
			
            $("#editRegion option").each(function() {
                if ($(this).val() === region) {
                    $(this).prop("selected", true);
                    return false;
                }
            });			
			
          $("#editGamePrice").val(price);
          $("#editDescription").text(description);
          $("#editAdsForm").css("display", "flex");
        }); 
        
        $(document).on('click', '.delete_ads', function() {
          var ads_id = $(this).data('ads_id');
          var user_id = <?php echo $user_id ?>;
          
            Swal.fire({
                title: 'آیا مطمئن هستید؟',
                text: "این عمل قابل بازگشت نیست!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'بله، حذف کن!',
                cancelButtonText: 'لغو'
            }).then((result) => {
                if (result.isConfirmed) {
                  const formData = {
                    do: 'delete_ads',
                    ads_id: ads_id,
                    user_id: user_id,
                  };
        
                  $.ajax({
                    url: "my-ads-ajax.php", // آدرس صفحه مقصد
                    type: "POST", // نوع درخواست
                    data: formData, // داده‌های ارسالی
                    success: function(response) {  
        				$("#editAdsForm").fadeOut();
        				// success message toast
        				Swal.fire({
        					toast: true,
        					position: 'center',
        					icon: 'success',
        					title: 'آگهی شما با موفقیت حذف شد!',
        					showConfirmButton: false,
        					timer: 5000
        				});	
						
						$("#ads_details"+ads_id).fadeOut();
                    },
                    error: function(xhr, status, error) {
                      console.error(error); 
                    }
                  });                    
                }
            });          
          
        });        

        $("#editAdsBtn").click(function(e) {
			
		  var ads_id = $("#editAdsBtn").data('ads_id')
			
          e.preventDefault();
			
			$(".error-message").hide();

			let isValid = true;
			
			 if ($("#editGamePrice").val().trim() === "") {
				$("#editgamePriceError").css('display', 'block');
				isValid = false;
			  }		

			$("#editGamePrice").on("change, input", function() {
			  if ($(this).val().trim() !== "") {
				$("#editGamePriceError").hide();
			  } else {
				$("#editgamePriceError").css('display', 'block');
				isValid = false;
			  }
			});	

			if (!isValid) {
			  return;
			}

          const formData = {
            do: 'edit_ads',
            ads_id: ads_id,
            gamePrice: $("#editGamePrice").val(),
            platform: $("#editPlatform").val(),
            capacity: $("#editCapacity").val(),
            region: $("#editRegion").val(),
            description: $("#editDescription").val(),
            user_id: <?php echo $user_id ?>
          };

          $.ajax({
            url: "my-ads-ajax.php", // آدرس صفحه مقصد
            type: "POST", // نوع درخواست
            data: formData, // داده‌های ارسالی
            success: function(response) {  
				$("#editAdsForm").fadeOut();
				// success message toast
				Swal.fire({
					toast: true,
					position: 'center',
					icon: 'success',
					title: 'آگهی شما با موفقیت ویرایش شد و پس از تایید مجدد نمایش داده می شود!',
					showConfirmButton: false,
					timer: 5000
				});				
            },
            error: function(xhr, status, error) {
              console.error(error); 
            }
          });
        });
        
		$("#search").keyup(function(e) {
		    
			  if ($(this).val() === "" || $(this).val() === null) {
				  $("#search_result_ads_main_div").fadeOut();
				  return false;
			  }
			
              const formData = {
                do: 'my_ads_search',
                user_id: <?php echo $user_id ?>,
                term: $(this).val(),
              };
    
              $.ajax({
                url: "my-ads-ajax.php", // آدرس صفحه مقصد
                type: "POST", // نوع درخواست
                data: formData, // داده‌های ارسالی
                success: function(response) {
					$("#search_result_ads_main_div").css('display', 'block');
                //   alert("فرم با موفقیت ارسال شد!"); // پیام موفقیت
                  $("#search_result_ads_div").html(response); // بستن فرم پس از ارسال
//                  $("#search_result_ads_div").fadeIn(); // بستن فرم پس از ارسال
                },
                error: function(xhr, status, error) {
                  alert("خطا در ارسال فرم!"); // پیام خطا
                //   console.error(error); // نمایش خطا در کنسول
                }
              });
//		  }
        });
		  
		$("#show_more_ads, #apply_filters").on('click', function() {
			
			var filter_platform = $('#filter_platform').val();
			var filter_region = $('#filter_region').val();
			var filter_capacity = $('#filter_capacity').val();			
			var counter = $("#show_more_ads").data('counter');
			
			var event_id = event.target.id;
			
			if (event_id === "apply_filters") {
			    
    			if((filter_platform === "") && (filter_region === "") && (filter_capacity === "")){
    				Swal.fire({
    					title: 'فیلتری انتخاب نشده!',
    					text: 'گزینه ای برای فیلتر شدن آگهیها انتخاب نشده.',
    					icon: 'warning',
    					confirmButtonText: 'OK'
    				});	
    				return false;
    			}
			
				counter = 0;
				$("#show_more_ads").data('counter', 0); // reset counter if click on filters
			}

          const formData = {
            filter_platform: filter_platform,
            filter_region: filter_region, 
            filter_capacity: filter_capacity, 
            user_id: <?php echo $user_id ?>,
            counter: counter,
			do: 'load_more_ads',			  
          };

          $.ajax({
            url: "my-ads-ajax.php",
            type: "POST",
            data: formData,
            success: function(response) {
				if(response.trim() != 'no_more_ads'){
				    // alert(event_id);
                    if (event_id === "apply_filters") {
                     $("#ads_div").html(response);
                     return false;   
                    }		    
				    
					if((filter_platform !== "") || (filter_region !== "") || (filter_capacity !== "")){ // if has filter replace results
					    if (event_id === "apply_filters")
						    $("#ads_div").html(response);
						else
						    $("#ads_div").append(response);	
					}
					else
						$("#ads_div").append(response);
				}
				else{
//					$("#show_more_ads").fadeOut();
                    if((filter_platform !== "") || (filter_region !== "") || (filter_capacity !== "")){
            				Swal.fire({
            					title: 'آگهی وجود ندارد!',
            					text: 'برای فیلترهای اعمال شده، آگهی بیشتری وجود ندارد.',
            					icon: 'info',
            					confirmButtonText: 'OK'
            				});					
    				    }
    				else{
        				Swal.fire({
        					title: 'چیزی پیدا نشد!',
        					text: 'در حال حاضر، آگهی بیشتری برای نمایش وجود ندارد',
        					icon: 'info',
        					confirmButtonText: 'OK'
        				});					    
    				}
				}
            },
            error: function(xhr, status, error) {
              console.error(error); 
            }
          });
          
            var counter = $("#show_more_ads").data('counter');
            $("#show_more_ads").data('counter', counter + 1);            

        });	
		  
      });
    </script>
  </body>
</html>