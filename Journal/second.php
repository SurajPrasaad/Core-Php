<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration Form</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>

<div class="container">
    <h2>Patient Registration Form</h2>

    <?php
    $errors = [];
    $formData = [
        'fullName' => '',
        'dob' => '',
        'gender' => '',
        'contactNumber' => '',
        'email' => '',
        'address' => '',
        'emergencyContactName' => '',
        'emergencyContactNumber' => '',
        'bloodGroup' => '',
        'medicalHistory' => '',
        'doctorAssigned' => ''
        ];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate and sanitize inputs
        foreach ($formData as $key => &$value) {
            if (!empty($_POST[$key])) {
                $value = htmlspecialchars(trim($_POST[$key]));
            }
        }

        // Validate specific fields
        if (empty($formData['fullName']) || !preg_match("/^[a-zA-Z\s]+$/", $formData['fullName'])) {
            $errors['fullName'] = "Please enter a valid full name (letters only).";
        }

        if (empty($formData['dob'])) {
            $errors['dob'] = "Please enter your date of birth.";
        }

        if (empty($formData['gender'])) {
            $errors['gender'] = "Please select your gender.";
        }

        if (empty($formData['contactNumber']) || !preg_match("/^\d{10}$/", $formData['contactNumber'])) {
            $errors['contactNumber'] = "Please enter a valid 10-digit contact number.";
        }

        if (empty($formData['email']) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Please enter a valid email address.";
        }

        if (empty($formData['address'])) {
            $errors['address'] = "Please enter your address.";
        }

        if (empty($formData['emergencyContactName']) || !preg_match("/^[a-zA-Z\s]+$/", $formData['emergencyContactName'])) {
            $errors['emergencyContactName'] = "Please enter a valid emergency contact name.";
        }

        if (empty($formData['emergencyContactNumber']) || !preg_match("/^\d{10}$/", $formData['emergencyContactNumber'])) {
            $errors['emergencyContactNumber'] = "Please enter a valid 10-digit emergency contact number.";
        }

        if (empty($formData['bloodGroup'])) {
            $errors['bloodGroup'] = "Please select your blood group.";
        }

        if (empty($formData['doctorAssigned'])) {
            $errors['doctorAssigned'] = "Please select a doctor.";
        }

        // Handle file upload
        if (isset($_FILES['medicalReports']) && $_FILES['medicalReports']['error'] == 0) {
            $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
            $fileType = $_FILES['medicalReports']['type'];

            if (in_array($fileType, $allowedTypes)) {
                $uploadDir = 'uploads/';
                $uploadFile = $uploadDir . basename($_FILES['medicalReports']['name']);
                move_uploaded_file($_FILES['medicalReports']['tmp_name'], $uploadFile);
            } else {
                $errors['medicalReports'] = "Invalid file type. Only PDF, JPG, and PNG files are allowed.";
            }
        }

        // If no errors, process the form (e.g., save to database)
        if (empty($errors)) {
            echo "<p>Form submitted successfully!</p>";
            // Save data to the database or send an email
        }
        }
    ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="fullName">Full Name *</label>
            <input type="text" id="fullName" name="fullName" value="<?php echo $formData['fullName']; ?>" required>
            <?php if (isset($errors['fullName'])) echo '<div class="error">'.$errors['fullName'].'</div>'; ?>
        </div>
        
        <div class="form-group">
            <label for="dob">Date of Birth *</label>
            <input type="date" id="dob" name="dob" value="<?php echo $formData['dob']; ?>" required>
            <?php if (isset($errors['dob'])) echo '<div class="error">'.$errors['dob'].'</div>'; ?>
        </div>

        <div class="form-group">
            <label>Gender *</label>
            <input type="radio" name="gender" value="Male" <?php if ($formData['gender'] == 'Male') echo 'checked'; ?> required> Male
            <input type="radio" name="gender" value="Female" <?php if ($formData['gender'] == 'Female') echo 'checked'; ?>> Female
            <input type="radio" name="gender" value="Other" <?php if ($formData['gender'] == 'Other') echo 'checked'; ?>> Other
            <?php if (isset($errors['gender'])) echo '<div class="error">'.$errors['gender'].'</div>'; ?>
        </div>

        <div class="form-group">
            <label for="contactNumber">Contact Number *</label>
            <input type="tel" id="contactNumber" name="contactNumber" value="<?php echo $formData['contactNumber']; ?>" required>
            <?php if (isset($errors['contactNumber'])) echo '<div class="error">'.$errors['contactNumber'].'</div>'; ?>
        </div>

        <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" value="<?php echo $formData['email']; ?>" required>
            <?php if (isset($errors['email'])) echo '<div class="error">'.$errors['email'].'</div>'; ?>
        </div>

        <div class="form-group">
            <label for="address">Address *</label>
            <textarea id="address" name="address" required><?php echo $formData['address']; ?></textarea>
            <?php if (isset($errors['address'])) echo '<div class="error">'.$errors['address'].'</div>'; ?>
        </div>

        <div class="form-group">
            <label for="emergencyContactName">Emergency Contact Name *</label>
            <input type="text" id="emergencyContactName" name="emergencyContactName" value="<?php echo $formData['emergencyContactName']; ?>" required>
            <?php if (isset($errors['emergencyContactName'])) echo '<div class="error">'.$errors['emergencyContactName'].'</div>'; ?>
        </div>

        <div class="form-group">
            <label for="emergencyContactNumber">Emergency Contact Number *</label>
            <input type="tel" id="emergencyContactNumber" name="emergencyContactNumber" value="<?php echo $formData['emergencyContactNumber']; ?>" required>
            <?php if (isset($errors['emergencyContactNumber'])) echo '<div class="error">'.$errors['emergencyContactNumber'].'</div>'; ?>
        </div>

        <div class="form-group">
            <label for="bloodGroup">Blood Group *</label>
            <select id="bloodGroup" name="bloodGroup" required>
                <option value="" disabled <?php echo ($formData['bloodGroup'] == '') ? 'selected' : ''; ?>>Select your blood group</option>
                <option value="A+" <?php echo ($formData['bloodGroup'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                <option value="A-" <?php echo ($formData['bloodGroup'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                <option value="B+" <?php echo ($formData['bloodGroup'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                <option value="B-" <?php echo ($formData['bloodGroup'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                <option value="AB+" <?php echo ($formData['bloodGroup'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                <option value="AB-" <?php echo ($formData['bloodGroup'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                <option value="O+" <?php echo ($formData['bloodGroup'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                <option value="O-" <?php echo ($formData['bloodGroup'] == 'O-') ? 'selected' : ''; ?>>O-</option>
            </select>
            <?php if (isset($errors['bloodGroup'])) echo '<div class="error">'.$errors['bloodGroup'].'</div>'; ?>
        </div>

        <div class="form-group">
            <label for="medicalHistory">Medical History (Optional)</label>
            <textarea id="medicalHistory" name="medicalHistory"><?php echo $formData['medicalHistory']; ?></textarea>
            <?php if (isset($errors['medicalHistory'])) echo '<div class="error">'.$errors['medicalHistory'].'</div>'; ?>
        </div>

        <div class="form-group">
            <label for="doctorAssigned">Doctor Assigned *</label>
            <select id="doctorAssigned" name="doctorAssigned" required>
                <option value="" disabled <?php echo ($formData['doctorAssigned'] == '') ? 'selected' : ''; ?>>Select a doctor</option>
                <option value="Dr. Smith" <?php echo ($formData['doctorAssigned'] == 'Dr. Smith') ? 'selected' : ''; ?>>Dr. Smith</option>
                <option value="Dr. Brown" <?php echo ($formData['doctorAssigned'] == 'Dr. Brown') ? 'selected' : ''; ?>>Dr. Brown</option>
                <option value="Dr. Johnson" <?php echo ($formData['doctorAssigned'] == 'Dr. Johnson') ? 'selected' : ''; ?>>Dr. Johnson</option>
            </select>
            <?php if (isset($errors['doctorAssigned'])) echo '<div class="error">'.$errors['doctorAssigned'].'</div>'; ?>
        </div>

        <div class="form-group">
            <label for="medicalReports">Upload Medical Reports (Optional)</label>
            <input type="file" id="medicalReports" name="medicalReports" accept=".pdf,.jpg,.png">
            <?php if (isset($errors['medicalReports'])) echo '<div class="error">'.$errors['medicalReports'].'</div>'; ?>
        </div>

        <div class="form-group">
            <button type="submit">Submit</button>
        </div>
    </form>
</div>

</body>
</html>