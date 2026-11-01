<?php
require_once 'config.php';

$email    = 'sofianeaddoune55@gmail.com';
$password = 'ad12345678';  // الكلمة التي تريدها

// توليد Hash صحيح من PHP نفسه
$hash = password_hash($password, PASSWORD_DEFAULT);

// تحديث كلمة المرور
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
$stmt->execute([$hash, $email]);

if ($stmt->rowCount() > 0) {
    echo "✅ تم إصلاح كلمة المرور بنجاح!<br><br>";
    echo "الإيميل: <b>$email</b><br>";
    echo "كلمة المرور: <b>$password</b><br><br>";
    echo "<a href='login.php' style='font-size:18px;color:green;'>👉 اضغط هنا لتسجيل الدخول</a>";
} else {
    echo "❌ لم يتم العثور على الإيميل في قاعدة البيانات";
}

// عرض الـ Hash للتأكد (اختياري)
echo "<hr><small>Hash المخزن الآن:<br><code>$hash</code></small>";
?>