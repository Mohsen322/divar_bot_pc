    <!-- لودینگ اسپینر -->
    <div id="loading">
        <div class="spinner-border text-primary" role="status">
            <!--<span class="sr-only"></span>-->
        </div>
    </div>      
	  
    <div class="container-fluid bg-dark text-white">
      <div class="row text-center">
        <div class="col-2"><img id="profile_pic" src="https://atarix.ir/img/logo.png" alt=""></div>
        <div class="col-10 align-content-center text-end"> خوش اومدی <span id="first_name"></span></div>
      </div>
		<div id="profile_details">
		    <p id="my_ads"><a href="index.php"><i class="bi bi-house"></i> صفحه اول</a></p>
			<p id="my_ads"><a href="my-ads.php"><i class="bi bi-controller"></i> آگهیهای من</a></p>
			<div class="triangle"></div>
		</div>
    </div>
	  
    <div class="container-fluid row">
      <div class="col-11" style="padding:0">
		  <input type="text" id="search" class="col-12" placeholder="جستجو در آگهیها" style="position: relative">
      <i class="bi bi-search search-icon"></i></div>
	  <div class="col-1" style="padding:0"><i class="bi bi-filter-square" id="show_filters" style="font-size:42px; position:relative; left:-5px; color:gray"></i></div>
    </div>
    
    <div id="filter_options" class="container-fluid" style="display: none">
        <small><i class="bi bi-exclamation-triangle"></i> گزینه های فیلتر آگهیهای منتشر شده.</small>
        <select id="filter_platform" class="col-12">
          <option value="" selected>انتخاب پلتفرم (همه)</option>
          <option value="PS5">PS5</option>
          <option value="PS4">PS4</option>
        </select>

        <select id="filter_capacity" class="col-12">
          <option value="" selected>انتخاب ظرفیت (همه)</option>
          <option value="1">ظرفیت 1</option>
          <option value="2">ظرفیت 2</option>
          <option value="3">ظرفیت 3</option>
          <option value="full">ظرفیت کامل</option>
        </select>

        <select id="filter_region" class="col-12">
          <option value="" selected>انتخاب ریجن (همه)</option>
          <option value="turkey">ترکیه</option>
          <option value="usa">آمریکا</option>
          <option value="japan">ژاپن</option>
          <option value="ukrain">اوکراین</option>
          <option value="all">ریجن آل</option>
        </select> 
		<button class="col-12 btn btn-warning" id="apply_filters"><i class="bi bi-funnel"></i> اعمال فیلتر</button>		
    </div>