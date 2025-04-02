<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch user details from the database
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Check if form is submitted
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    
    // Process the profile picture if uploaded
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $profilePic = $_FILES['profile_pic'];
        $profilePicName = basename($profilePic['name']);
        $targetDir = 'uploads/';
        $targetFile = $targetDir . $profilePicName;

        // Validate image file (optional, for security)
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            // Move the uploaded image to the 'uploads' folder
            if (move_uploaded_file($profilePic['tmp_name'], $targetFile)) {
                // Update the profile picture in the database
                $stmt = $pdo->prepare("UPDATE users SET name = ?, profile_pic = ? WHERE id = ?");
                $stmt->execute([$name, $profilePicName, $userId]);
                $_SESSION['profile_pic'] = $profilePicName; // Store the new profile picture in session
                echo "Profile updated successfully.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        // If no profile picture is uploaded, only update the name
        $stmt = $pdo->prepare("UPDATE users SET name = ? WHERE id = ?");
        $stmt->execute([$name, $userId]);
        echo "Profile updated successfully.";
    }

    // Redirect back to the dashboard after the update
    header('Location: donor_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
   <style>
    /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

/* Profile Update Container */
.update-profile-container {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;
}

h2 {
    margin-bottom: 20px;
    color: #333;
}

/* Input Fields */
input[type="text"],
input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

/* Profile Picture Preview */
.profile-pic-preview {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    display: block;
    margin: 10px auto;
    border: 2px solid #ccc;
}

/* Button */
button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    width: 100%;
    border-radius: 5px;
    font-size: 16px;
}

button:hover {
    background-color: #0056b3;
}

   </style>
</head>
<body>
    <div class="update-profile-container">
        <h2>Update Your Profile</h2>
        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <label for="name">New Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <!-- Profile Picture Upload Field -->
<label for="profile_pic">Profile Picture:</label>
<input type="file" id="profile_pic" name="profile_pic" accept="image/*" onchange="previewImage(event)">

<!-- Profile Picture Preview -->
<img id="profilePicPreview" class="profile-pic-preview" src="" alt="Profile Picture Preview">

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('profilePicPreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

            <button type="submit" name="update">Update Profile</button>
        </form>
    </div>
</body>
</html>
