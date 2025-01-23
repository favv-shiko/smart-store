<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating']); // قراءة التقييم المرسل

    // بيانات الاتصال بقاعدة البيانات
    $servername = "localhost";
    $username = "username"; // استبدل باسم المستخدم الخاص بك
    $password = "password"; // استبدل بكلمة المرور الخاصة بك
    $dbname = "database_name"; // استبدل باسم قاعدة البيانات

    // إنشاء اتصال بقاعدة البيانات
    $conn = new mysqli($servername, $username, $password, $dbname);

    // التحقق من الاتصال
    if ($conn->connect_error) {
        die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
    }

    // إدخال التقييم إلى الجدول
    $stmt = $conn->prepare("INSERT INTO ratings (rating) VALUES (?)");
    $stmt->bind_param("i", $rating);

    if ($stmt->execute()) {
        echo "تم حفظ التقييم بنجاح!";
    } else {
        echo "حدث خطأ أثناء حفظ التقييم.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "طلب غير صالح.";
}
?>
