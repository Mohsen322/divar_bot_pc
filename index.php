<?php
  require_once 'classes/ads_class.php';
  $ads_ptr = new ADS();

  $limit = 10;
  $confirmed_ads = $ads_ptr->getConfirmedAds($limit);

  require_once 'classes/public_functions.php';
?>
<!doctype html>
<html lang="en">
 <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ربات دیوار گیمینگ</title>
  <script src="js/telegram-web-app-56.js"></script>
  <script src="js/sweetalert2@11.js"></script>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/main.css?74599800">
   <style>
      /*#profile_details {*/
      /*  background: #034c53;*/
      /*  position: absolute;*/
      /*  width: 293px;*/
      /*  z-index: 1000;*/
      /*  top: 49px;*/
      /*  height: 120px;*/
      /*  right: 14px;*/
      /*  border-top-right-radius: 18px;*/
      /*  padding: 10px;*/
      /*  display: none;*/
      /*}*/
      /*.triangle {*/
      /*  position: absolute;*/
      /*  top: -9px;*/
      /*  left: 89%;*/
      /*  width: 0;*/
      /*  height: 0;*/
      /*  border-left: 10px solid transparent;*/
      /*  border-right: 10px solid transparent;*/
      /*  border-bottom: 10px solid #f0f0f0;*/
      /*}*/
   </style>
 </head>
 
 <body dir="rtl">
  
  <?php require_once ('top_fix_section.php') ?>
  
  <div id="search_result_ads_main_div" class="container-fluid text-center" style="display: none">
    <h5>نتایج جستجو:</h5>
    <div id="search_result_ads_div" class="container-fluid text-center"></div>
  </div>
  
  <div id="ads_main_div" class="container-fluid text-center">
    <h5>جدیدترین آگهیهای ثبت شده</h5>
    <div id="ads_div" class="container-fluid text-center">
    <?php
    for($i=0; $i<count($confirmed_ads); $i++){
      $console_icon = 'ps5_icon.png';
      if($confirmed_ads[$i]['platform'] == 'PS4') $console_icon = 'ps4_icon.png';
    ?>
     <div class="ads_main_div container-fluid">
      <h4 class="game_title"><?php echo $confirmed_ads[$i]['game_en_title'] ?></h4>
       <div class="row container">
         <div class="col-7">
           <h5><i class="bi bi-coin"></i> قیمت: <br> <?php echo number_format($confirmed_ads[$i]['price']) ?> تومان</h5>
           <h5><i class="bi bi-controller"></i> ظرفیت: <?php echo ($confirmed_ads[$i]['capacity'] == 'full') ? 'کامل' : $confirmed_ads[$i]['capacity'] ?></h5>
           <h5><img src="img/<?php echo $console_icon ?>" alt="" class="console_icon"> کنسول: <?php echo $confirmed_ads[$i]['platform'] ?></h5>
           <h5>ریجن: <?php echo getRegionAlias($confirmed_ads[$i]['region']) ?></h5>
        </div>
<!--        <div class="col-5"><img class="game_photo" src="<?php echo $confirmed_ads[$i]['photo_url'] ?>" alt=""></div>         -->
        <div class="col-5"><img class="game_photo" src="img/games_thumb_img/<?php echo $confirmed_ads[$i]['photo_local_name'] ?>" alt=""></div>        
       </div>
       <?php if(!empty($confirmed_ads[$i]['description'])){ ?>
           <small class="col-12"><span class="fw-bold"><i class="bi bi-chat-square-quote"></i> توضیحات:</span> <br> <span class="pe-2"><?php echo $confirmed_ads[$i]['description'] ?></span></small>
        <?php } ?>

        <div class="row container ads_info">
          <div class="col-7"><i class="bi bi-calendar"></i></i> <?php echo human_readable_time_difference($confirmed_ads[$i]['reg_date'].' '.$confirmed_ads[$i]['reg_time'], 'd h i') ?></div>
          <div class="col-5"><i class="bi bi-eye"></i> <?php echo number_format($confirmed_ads[$i]['view']) ?> بازدید</div>
        </div> 
       
      <a class="contact_btn" href="https://t.me/<?php echo $confirmed_ads[$i]['tel_username'] ?>"><button class="col-12 btn btn-primary"><i class="bi bi-telegram"></i> تماس با فروشنده</button></a>
     </div>
    <?php } ?>
  </div>  
</div>  
  <!-- Floating Button -->
  <button class="floating-button" id="openFormButton"><i class="bi bi-plus"></i></button>
  <!-- Form Popup -->
 
  <div id="go_to_top"><i class="bi bi-rocket"></i></div>
 
  <div class="form-popup" id="myForm">
   <div class="form-container">
    <button class="close-btn" id="closeFormButton">&times;</button>
    <input type="text" id="gameName" placeholder="نام بازی (سرچ کنید و اسم بازی را انتخاب کنید)" required data-game_id="0">
    <div id="gameNameError" class="error-message">انتخاب نام بازی از لیست ضروری است.</div>
    <div class="container col-11" id="game_name_search_result_div"></div>   
   <input type="text" id="gamePrice" class="numberFormat" placeholder="قیمت به تومان" required pattern="\d*" inputmode="numeric"> <!-- فقط عدد -->
    <div id="gamePriceError" class="error-message">تعیین قیمت ضروری است.</div>
    <select id="platform" required>
     <option value="" disabled selected>پلتفرم را انتخاب کنید</option>
     <option value="PS5">PS5</option>
     <option value="PS4">PS4</option>
    </select>
    <div id="platformError" class="error-message">انتخاب پلتفرم ضروری است.</div>
    <select id="capacity" required>
     <option value="" disabled selected>ظرفیت را انتخاب کنید</option>
     <option value="1">ظرفیت 1</option>
     <option value="2">ظرفیت 2</option>
     <option value="3">ظرفیت 3</option>
     <option value="full">ظرفیت کامل</option>
    </select>
    <div id="capacityError" class="error-message">انتخاب ظرفیت ضروری است.</div>
    <select id="region" required>
     <option value="" disabled selected>ریجن بازی را انتخاب کنید</option>
     <option value="turkey">ترکیه</option>
     <option value="usa">آمریکا</option>
     <option value="japan">ژاپن</option>
     <option value="ukrain">اوکراین</option>
     <option value="all">ریجن آل</option>
    </select>
    <div id="regionError" class="error-message">انتخاب ریجن ضروری است.</div>    
    <textarea id="description" placeholder="توضیحات"></textarea> <!-- فیلد توضیحات با ارتفاع بیشتر -->
    <button type="submit" class="btn" id="submitForm"><i class="bi bi-dpad"></i> درج آگهی</button>
   </div>
  </div>
 
  <button class="col-12 btn btn-warning" id="show_more_ads" data-counter="1" data-filter_capacity="" data-filter_region="" data-filter_platform=""><i class="bi bi-download"></i> مشاهده آگهیهای بیشتر</button>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/public.js?45"></script>
  <script>
   
   $(document).ready(function() {
     var webapp = window.Telegram.WebApp;
    
     webapp.BackButton.hide();
    
     const formData = {
      do: 'save_in_session',
      user_id: user_id
     };

     $.ajax({
      url: "ajax.php", // آدرس صفحه مقصد
      type: "POST", // نوع درخواست
      data: formData, // داده‌های ارسالی
      success: function(response) {
      // alert("فرم با موفقیت ارسال شد!");
       },
      error: function(xhr, status, error) {
      // alert("خطا در ارسال فرم!"); // پیام خطا
      // console.error(error); // نمایش خطا در کنسول
      }
     });    
    
//     webapp.showAlert(getUserInfo(user.first_name));
    $("#first_name").text(first_name);
    $("#profile_pic").attr('src', photo_url);
    
    $("#openFormButton").click(function() {
     $("#myForm").css("display", "flex");
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
     $("#myForm").css("display", "none");
    });  

    $("#submitForm").click(function(e) {
     e.preventDefault();
     
      $(".error-message").hide();

      let isValid = true;
     
       if ($("#gameName").val().trim() === "" || $("#gameName").data('game_id') === 0) {
        $("#gameNameError").show();
        isValid = false;
       }
       else if ($("#gamePrice").val().trim() === "") {
        $("#gamePriceError").show();
        isValid = false;
       }
       else if ($("#platform").val() === null || $("#platform").val() === "") {
        $("#platformError").show();
        isValid = false;
       }
       else if ($("#capacity").val() === null || $("#capacity").val() === "") {
        $("#capacityError").show();
        isValid = false;
       }
       else if ($("#region").val() === null || $("#region").val() === "") {
        $("#regionError").show();
        isValid = false;
       }     

      $("#gameName").on("change, input", function() {
       if ($(this).val().trim() !== "") {
        $("#gameNameError").hide();
       } else {
        $("#gameNameError").show();
        isValid = false;
       }
      });

      $("#gamePrice").on("change, input", function() {
       if ($(this).val().trim() !== "") {
        $("#gamePriceError").hide();
       } else {
        $("#gamePriceError").show();
        isValid = false;
       }
      });

      $("#platform").on("change", function() {
       if ($("#platform").val() !== null && $("#platform").val() !== "") {
        $("#platformError").hide();
       } else {
        $("#platformError").show();
        isValid = false;
       }
      });

      $("#capacity").on("change", function() {
       if ($("#capacity").val() !== null && $("#capacity").val() !== "") {
        $("#capacityError").hide();
       } else {
        $("#capacityError").show();
        isValid = false;
       }
      });
     
      $("#region").on("change", function() {
       if ($("#region").val() !== null && $("#region").val() !== "") {
        $("#regionError").hide();
       } else {
        $("#regionError").show();
        isValid = false;
       }
      });     

      if (!isValid) {
       return;
      }

     const formData = {
      do: 'add_new_ads',
      gameName: $("#gameName").val(),
      gameId: $("#gameName").data('game_id'),
      gamePrice: $("#gamePrice").val(),
      platform: $("#platform").val(),
      capacity: $("#capacity").val(),
      region: $("#region").val(),
      description: $("#description").val(),
      tel_user_id: user_id,
      tel_username: username,
     };

     $.ajax({
      url: "ajax.php", // آدرس صفحه مقصد
      type: "POST", // نوع درخواست
      data: formData, // داده‌های ارسالی
      success: function(response) {      
        $('#gameName').val('');
        $('#gamePrice').val('');
        $('#description').val('');
        $("#myForm").css("display", "none");
       
        // success message toast
        Swal.fire({
          toast: true,
          position: 'center',
          icon: 'success',
          title: 'آگهی شما با موفقیت درج شد و پس از تایید نمایش داده می شود!',
          showConfirmButton: false,
          timer: 5000
        });       
      },
      error: function(xhr, status, error) {
       console.error(error);
      }
     });
    });
    
    $("#gameName").keyup(function(e) {
      if ($(this).val() != "" && $(this).val() != null) {
       $("#game_name_search_result_div").fadeIn();
       const formData = {
        do: 'game_name_search',
        term: $(this).val(),
       };
 
       $.ajax({
        url: "ajax.php", // آدرس صفحه مقصد
        type: "POST", // نوع درخواست
        data: formData, // داده‌های ارسالی
        success: function(response) {
        // alert("فرم با موفقیت ارسال شد!"); // پیام موفقیت
         $("#game_name_search_result_div").html(response); // بستن فرم پس از ارسال
        // console.log(response); // نمایش پاسخ سرور در کنسول
        },
        error: function(xhr, status, error) {
         alert("خطا در ارسال فرم!"); // پیام خطا
        // console.error(error); // نمایش خطا در کنسول
        }
       });
      }
    });
   
    $("#search").keyup(function(e) {
     
       if ($(this).val() === "" || $(this).val() === null) {
         $("#search_result_ads_main_div").fadeOut();
         return false;
       }
     
       const formData = {
        do: 'ads_search',
        term: $(this).val(),
       };
 
       $.ajax({
        url: "ajax.php", // آدرس صفحه مقصد
        type: "POST", // نوع درخواست
        data: formData, // داده‌های ارسالی
        success: function(response) {
          $("#search_result_ads_main_div").css('display', 'block');
        // alert("فرم با موفقیت ارسال شد!"); // پیام موفقیت
         $("#search_result_ads_div").html(response); // بستن فرم پس از ارسال
//         $("#search_result_ads_div").fadeIn(); // بستن فرم پس از ارسال
        },
        error: function(xhr, status, error) {
         alert("خطا در ارسال فرم!"); // پیام خطا
        // console.error(error); // نمایش خطا در کنسول
        }
       });
//     }
    });   
    
    $("#game_name_search_result_div").on('click', 'p.shown_game_name', function() {
      $('#gameName').val($(this).text());
      var selected_game_id = $(this).attr('data-game_id');
      $('#gameName').data('game_id', selected_game_id);
      $("#game_name_search_result_div").html('');
      $("#game_name_search_result_div").fadeOut();
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
   
    // counter = 0;
    // $("#show_more_ads").data('counter', 0); // reset counter if click on filters
  }

  const formData = {
    filter_platform: filter_platform,
    filter_region: filter_region,
    filter_capacity: filter_capacity,     
    counter: counter,
    do: 'load_more_ads',      
  };

  $.ajax({
    url: "ajax.php",
    type: "POST",
    data: formData,
    success: function(response) {
      if(response.trim() != 'no_more_ads'){
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
      else {
        if((filter_platform !== "") || (filter_region !== "") || (filter_capacity !== "")){
          Swal.fire({
            title: 'آگهی وجود ندارد!',
            text: 'برای فیلترهای اعمال شده، آگهی بیشتری وجود ندارد.',
            icon: 'info',
            confirmButtonText: 'OK'
          });         
        }
        else {
          Swal.fire({
            title: 'چیزی پیدا نشد!', // اینجا کاما قرار داده شد
            text: 'در حال حاضر، آگهی بیشتری برای نمایش وجود ندارد',
            icon: 'info',
            confirmButtonText: 'OK'
          }); 
          // $("#show_more_ads").fadeOut();
        }
      }
    },
    error: function(xhr, status, error) {
      console.error(error);
    }
  });
});
    
   });
  </script>
 </body>
</html>