<?php
require_once 'connection.php';

class USER_ADS extends Connection {

public function getUserdAds($limit = 20, $user_id){
    $conn = $this->connect();
    $stmt = $conn->prepare("SELECT *, ads.auto_id as ads_id FROM ads 
        INNER JOIN games_list ON ads.game_id = games_list.auto_id 
        WHERE tel_user_id = ? AND logic_remove = 0
        ORDER BY ads.auto_id DESC LIMIT ?");
    if ($stmt === false){
        die("خطای SQL: " . $conn->error);
    }
    $stmt->bind_param("ii", $user_id, $limit);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $ads = $result->fetch_all(MYSQLI_ASSOC);
        return $ads;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();		
}

public function editAds($data) {
    $conn = $this->connect();
    
    $stmt = $conn->prepare("UPDATE ads SET 
        price = ?, 
        platform = ?, 
        capacity = ?, 
        region = ?, 
        description = ? 
        WHERE auto_id = ? AND tel_user_id = ?");
    
    if ($stmt === false) {
        die("خطای SQL: " . $conn->error);
    }
    
    $stmt->bind_param("issssii", 
        $data['price'],
        $data['platform'],
        $data['capacity'],
        $data['region'],
        $data['description'],
        $data['ads_id'],
        $data['user_id']
    );
    
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}

// public function deleteAds($data) {
//     $conn = $this->connect();
    
//     $stmt = $conn->prepare("DELETE FROM ads WHERE auto_id = ? AND tel_user_id = ?");
    
//     if ($stmt === false) {
//         die("خطای SQL: " . $conn->error);
//     }
    
//     $stmt->bind_param("ii", 
//         $data['ads_id'],
//         $data['user_id']
//     );
    
//     if ($stmt->execute()) {
//         echo "Record deleted successfully";
//     } else {
//         echo "Error: " . $stmt->error;
//     }
    
//     $stmt->close();
//     $conn->close();
// }

public function deleteAds($data) {
    $conn = $this->connect();
    
    // تغییر دستور DELETE به UPDATE برای حذف منطقی
    $stmt = $conn->prepare("UPDATE ads SET logic_remove = 1 WHERE auto_id = ? AND tel_user_id = ?");
    
    if ($stmt === false) {
        die("خطای SQL: " . $conn->error);
    }
    
    $stmt->bind_param("ii", 
        $data['ads_id'],
        $data['user_id']
    );
    
    if ($stmt->execute()) {
        echo "Record soft deleted successfully (marked as removed)";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}

public function searchInUserAds($limit = 20, $term, $user_id) {
    $conn = $this->connect();

    // اضافه کردن % به دو طرف عبارت جستجو
    $searchTerm = "%$term%";

    // آماده‌سازی دستور SQL
    $stmt = $conn->prepare("SELECT *, ads.auto_id as ads_id FROM ads INNER JOIN games_list ON ads.game_id = games_list.auto_id
        WHERE game_en_title LIKE CONCAT('%', ?, '%') AND tel_user_id = ? ORDER BY ads.auto_id DESC LIMIT ?");
    if ($stmt === false) {
        die("خطای SQL در آماده‌سازی: " . $conn->error);
    }

    // بایند کردن پارامترها
    $stmt->bind_param("sii", $searchTerm, $user_id, $limit);

    // اجرای دستور SQL
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $ads = $result->fetch_all(MYSQLI_ASSOC);

        return $ads;
    } else {
        echo "خطا در اجرای جستجو: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

public function loadMoreMyConfirmedAds($data) {
    $filter_platform = $data['filter_platform'] ?? null;
    $filter_region = $data['filter_region'] ?? null;
    $filter_capacity = $data['filter_capacity'] ?? null;
    $counter = $data['counter'] ?? 0;
    $limit = $data['limit'] ?? 20;

    $query = "SELECT *, ads.auto_id as ads_id FROM ads INNER JOIN games_list ON ads.game_id = games_list.auto_id WHERE tel_user_id = ?";
    $params = [$data['user_id']];
    $types = 'i';

    $conditions = [];
    if (!empty($filter_platform)) {
        $conditions[] = "platform = ?";
        $params[] = $filter_platform;
        $types .= 's';
    }
    if (!empty($filter_region)) {
        $conditions[] = "region = ?";
        $params[] = $filter_region;
        $types .= 's';
    }
    if (!empty($filter_capacity)) {
        $conditions[] = "capacity = ?";
        $params[] = $filter_capacity;
        $types .= 's';
    }

    if (!empty($conditions)) {
        $query .= " AND " . implode(" AND ", $conditions);
    }

    $query .= " ORDER BY ads.auto_id DESC LIMIT ?, ?";
    $types .= 'ii';

    $offset = $counter * $limit;
    $params[] = $offset;
    $params[] = $limit;

    $conn = $this->connect();
    if ($conn === false) {
        die("Database connection failed.");
    }

    try {
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            throw new Exception("SQL Error: " . $conn->error);
        }

        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $ads = $result->fetch_all(MYSQLI_ASSOC);

            // افزایش مقدار view برای تمام آگهی‌ها به صورت جمعی
            if (!empty($ads)) {
                $adIds = array_column($ads, 'ads_id');
                $placeholders = implode(',', array_fill(0, count($adIds), '?'));
                $updateQuery = "UPDATE ads SET view = view + 1 WHERE auto_id IN ($placeholders)";
                $updateStmt = $conn->prepare($updateQuery);
                if ($updateStmt === false) {
                    throw new Exception("SQL Error: " . $conn->error);
                }
                $updateStmt->bind_param(str_repeat('i', count($adIds)), ...$adIds);
                if (!$updateStmt->execute()) {
                    throw new Exception("Error updating view: " . $updateStmt->error);
                }
                $updateStmt->close();
            }

            return $ads;
        } else {
            throw new Exception("Error: " . $stmt->error);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        $conn->close();
    }
}
	
}
?>