<?php
require_once 'connection.php';

class ADS extends Connection {
	
public function addNewAds($data) {
    $conn = $this->connect();
    $stmt = $conn->prepare("INSERT INTO ads (tel_user_id, tel_username, game_id, price, platform, capacity, region, description, reg_date, reg_time, reg_time_stamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), CURTIME(), UNIX_TIMESTAMP())");
    if ($stmt === false) {
        die("خطای SQL: " . $conn->error);
    }
    $stmt->bind_param("isiissss", 
        $data['tel_user_id'],
        $data['tel_username'],
        $data['game_id'],
        $data['price'],
        $data['platform'],
        $data['capacity'],
        $data['region'],
        $data['description']
    );
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
	
public function searchInGamesList($term){
	$conn = $this->connect();
    $stmt = $conn->prepare("SELECT * FROM games_list WHERE game_en_title LIKE CONCAT('%', ?, '%') LIMIT 7");
	if ($stmt === false){
		die("خطای SQL: " . $conn->error);
	}
    $stmt->bind_param("s", $term);
    if ($stmt->execute()) {
		$result = $stmt->get_result();
		$result = $result->fetch_all(MYSQLI_ASSOC);
		return $result;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();		
}

public function searchForDuplicateGames($term){
	$conn = $this->connect();
    $stmt = $conn->prepare("SELECT * FROM games_list WHERE game_en_title = ?");
	if ($stmt === false){
		die("خطای SQL: " . $conn->error);
	}
    $stmt->bind_param("s", $term);
    if ($stmt->execute()) {
		$result = $stmt->get_result();
		$result = $result->fetch_all(MYSQLI_ASSOC);
		return $result;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();		
}

public function getBuyerInformationById($customer_id){
	$conn = $this->connect();
    $stmt = $conn->prepare("SELECT * FROM customers WHERE auto_id = ?");
	if ($stmt === false){
		die("خطای SQL: " . $conn->error);
	}
    $stmt->bind_param("i", $customer_id);
    if ($stmt->execute()) {
		$result = $stmt->get_result();
		$buyers = $result->fetch_all(MYSQLI_ASSOC);
		return $buyers;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();		
}

public function getConfirmedAds($limit = 20){
    $conn = $this->connect();
    $stmt = $conn->prepare("SELECT *, ads.auto_id as ads_id FROM ads INNER JOIN games_list ON ads.game_id = games_list.auto_id WHERE logic_remove = 0 ORDER BY ads.auto_id DESC LIMIT ?");
    if ($stmt === false){
        die("خطای SQL: " . $conn->error);
    }
    $stmt->bind_param("i", $limit);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $ads = $result->fetch_all(MYSQLI_ASSOC);

        // افزایش مقدار view برای هر آگهی
        foreach ($ads as $ad) {
            $adId = $ad['ads_id'];
            $updateStmt = $conn->prepare("UPDATE ads SET view = view + 1 WHERE auto_id = ?");
            if ($updateStmt === false) {
                die("خطای SQL: " . $conn->error);
            }
            $updateStmt->bind_param("i", $adId);
            if (!$updateStmt->execute()) {
                echo "Error updating view: " . $updateStmt->error;
            }
            $updateStmt->close();
        }

        return $ads;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();		
}

public function searchInAds($limit = 20, $term) {
    $conn = $this->connect();

    // اضافه کردن % به دو طرف عبارت جستجو
    $searchTerm = "%$term%";

    // آماده‌سازی دستور SQL
    $stmt = $conn->prepare("SELECT *, ads.auto_id as ads_id FROM ads INNER JOIN games_list ON ads.game_id = games_list.auto_id
        WHERE game_en_title LIKE CONCAT('%', ?, '%') AND logic_remove = 0 ORDER BY ads.auto_id DESC LIMIT ?");
    if ($stmt === false) {
        die("خطای SQL در آماده‌سازی: " . $conn->error);
    }

    // بایند کردن پارامترها
    $stmt->bind_param("si", $searchTerm, $limit);

    // اجرای دستور SQL
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $ads = $result->fetch_all(MYSQLI_ASSOC);

        // افزایش مقدار view برای هر آگهی
        foreach ($ads as $ad) {
            $adId = $ad['ads_id'];
            $updateStmt = $conn->prepare("UPDATE ads SET view = view + 1 WHERE auto_id = ?");
            if ($updateStmt === false) {
                die("خطای SQL در آماده‌سازی UPDATE: " . $conn->error);
            }
            $updateStmt->bind_param("i", $adId);
            if (!$updateStmt->execute()) {
                echo "خطا در به‌روزرسانی view برای آگهی ID $adId: " . $updateStmt->error;
            }
            $updateStmt->close();
        }

        return $ads;
    } else {
        echo "خطا در اجرای جستجو: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

public function addNewGameToList($game_name, $photo_url, $photo_local_name) {
    $conn = $this->connect();
    $stmt = $conn->prepare("INSERT INTO games_list (game_en_title, photo_url, photo_local_name) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("خطای SQL: " . $conn->error);
    }
    $stmt->bind_param("sss", 
        $game_name,
        $photo_url,
        $photo_local_name
    );
    if ($stmt->execute()) {
        // echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}

public function loadMoreConfirmedAds($data){
    
    $filter_platform = $data['filter_platform'];
    $filter_region = $data['filter_region'];
    $filter_capacity = $data['filter_capacity'];
    $counter = $data['counter'];
    $limit = $data['limit'];
    
    $query = "SELECT *, ads.auto_id as ads_id FROM ads INNER JOIN games_list ON ads.game_id = games_list.auto_id WHERE logic_remove = 0";
    
    $params = [];
    $types = '';
    
    if(!empty($filter_platform) || !empty($filter_region) || !empty($filter_capacity)){
        // $query .= " WHERE ";
        // $counter = 0;
        // $limit = 20;
    }
    
    if(!empty($filter_platform)){
        $query .= " AND platform = ?";
        $params[] = $filter_platform;
        $types .= 's';
    }
    
    if(!empty($filter_region)){
        if(!empty($filter_platform)){
            $query .= " AND ";
        }
        $query .= " region = ?";
        $params[] = $filter_region;
        $types .= 's';
    }
    
    if(!empty($filter_capacity)){
        if(!empty($filter_platform) || !empty($filter_region)){
            $query .= " AND ";
        }
        $query .= " capacity = ?";
        $params[] = $filter_capacity;
        $types .= 's';
    }
    
    $query .= " ORDER BY ads.auto_id DESC LIMIT ?, ?";
    $types .= 'ii';
    
    $offset = $counter * $limit;
    $params[] = $offset;
    $params[] = $limit;
    
    // جایگزینی پارامترها در کوئری
    // $fullQuery = $query;
    // foreach ($params as $index => $param) {
    //     // اگر پارامتر مربوط به LIMIT باشد، بدون نقل‌قول جایگزین می‌شود
    //     if (strpos($fullQuery, 'LIMIT ?') !== false && $index >= count($params) - 2) {
    //         $fullQuery = preg_replace('/\?/', $param, $fullQuery, 1);
    //     } else {
    //         $fullQuery = preg_replace('/\?/', "'" . $param . "'", $fullQuery, 1);
    //     }
    // }
    
    // // چاپ کوئری کامل
    // echo "Full Query: " . $fullQuery . "\n";  
    
    $conn = $this->connect();    
    $stmt = $conn->prepare($query);
    if ($stmt === false){
        die("SQL Error: " . $conn->error);
    }    
    
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $ads = $result->fetch_all(MYSQLI_ASSOC);

        // افزایش مقدار view برای هر آگهی
        foreach ($ads as $ad) {
            $adId = $ad['ads_id'];
            $updateStmt = $conn->prepare("UPDATE ads SET view = view + 1 WHERE auto_id = ?");
            if ($updateStmt === false) {
                die("SQL Error: " . $conn->error);
            }
            $updateStmt->bind_param("i", $adId);
            if (!$updateStmt->execute()) {
                echo "Error updating view: " . $updateStmt->error;
            }
            $updateStmt->close();
        }

        return $ads;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();        
}
	
}
?>