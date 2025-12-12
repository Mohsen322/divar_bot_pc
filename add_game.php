<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>اضافه کردن گیم به لیست</title>
	<script src="js/telegram-web-app-56.js"></script>
	<script src="js/sweetalert2@11.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/main.css?808">
  </head>
	
  <body dir="rtl">

    <div class="form-group" id="myForm2">
      <div class="form-container">
        <button class="close-btn" id="closeFormButton">&times;</button>
        <input type="text" id="gameName" placeholder="game name">
        <input type="text" id="photoUrl" placeholder="photo url"> 
        <button type="submit" class="btn" id="submitForm"><i class="bi bi-dpad"></i> درج بازی</button>
      </div>
    </div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
		
      $(document).ready(function() {
          
			$("#gameName").on("blur", function() {
			  if ($(this).val().trim() !== "") {
                  const formData = {
                    do: 'check_duplicate',
                    gameName: $("#gameName").val()
                  };
        
                  $.ajax({
                    url: "ajax.php", // آدرس صفحه مقصد
                    type: "POST", // نوع درخواست
                    data: formData, // داده‌های ارسالی
                    success: function(response) {   
                        if(response.trim() === 'duplicate'){
            				$('#gameName').val('');
            				$('#photoUrl').val('');				
            				// success message toast
            				Swal.fire({
            					toast: true,
            					position: 'center',
            					icon: 'warning',
            					title: 'بازی تکراری!',
            					showConfirmButton: false,
            					timer: 1000
            				});
                        }
                    },
                    error: function(xhr, status, error) {
                      console.error(error); 
                    }
                  });			      
			  } else {
				isValid = false;
			  }
			});          

		  $("#submitForm").click(function(e) {
          e.preventDefault();
			
			$(".error-message").hide();

			let isValid = true;
			
			  if ($("#gameName").val().trim() === "") {
				$("#gameName").css('border', '1px solid red');
				isValid = false;
			  }
			  else if ($("#photoUrl").val().trim() === "") {
				$('#photoUrl').css('border', '1px solid red');	
				isValid = false;
			  }			

			if (!isValid) {
			  return;
			}

          const formData = {
            do: 'add_new_game',
            gameName: $("#gameName").val(),
            photoUrl: $("#photoUrl").val(),
          };

          $.ajax({
            url: "ajax.php", // آدرس صفحه مقصد
            type: "POST", // نوع درخواست
            data: formData, // داده‌های ارسالی
            success: function(response) {             
				$('#gameName').val('');
				$('#photoUrl').val('');				
				// success message toast
				Swal.fire({
					toast: true,
					position: 'center',
					icon: 'success',
					title: 'بازی جدید با موفقیت درج شد!',
					showConfirmButton: false,
					timer: 1000
				});				
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