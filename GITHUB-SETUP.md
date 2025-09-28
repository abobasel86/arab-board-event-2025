# خطوات ربط المشروع بـ GitHub

## 📋 المتطلبات المسبقة

1. **حساب GitHub:** تأكد من أن لديك حساب على GitHub
2. **Git مثبت:** تأكد من تثبيت Git على جهازك
3. **صلاحية الوصول:** تأكد من صلاحياتك على المجلد

## 🚀 خطوات الربط

### 1. تهيئة Git في المجلد

افتح PowerShell في مجلد الموضوع واكتب:

```powershell
cd "d:\event\wp-content\themes\arab-board-event-2025"
git init
git add .
git commit -m "Initial commit: Arab Board Event 2025 WordPress Theme"
```

### 2. إنشاء Repository جديد على GitHub

1. اذهب إلى [GitHub.com](https://github.com)
2. اضغط على **"New repository"**
3. اسم المستودع: `arab-board-event-2025`
4. الوصف: `WordPress Theme for Arab Board Health Specialties Conference 2025`
5. اجعله **Public** أو **Private** حسب رغبتك
6. **لا تضع** README.md (لأنه موجود بالفعل)
7. اضغط **"Create repository"**

### 3. ربط المشروع المحلي بـ GitHub

```powershell
git remote add origin https://github.com/abobasel86/arab-board-event-2025.git
git branch -M main
git push -u origin main
```

### 4. التحقق من الربط

```powershell
git remote -v
```

يجب أن ترى:
```
origin  https://github.com/abobasel86/arab-board-event-2025.git (fetch)
origin  https://github.com/abobasel86/arab-board-event-2025.git (push)
```

## 🔄 العمليات اليومية

### إضافة تغييرات جديدة
```powershell
git add .
git commit -m "وصف التغيير"
git push
```

### سحب التحديثات من GitHub
```powershell
git pull
```

### فحص حالة الملفات
```powershell
git status
```

### عرض تاريخ التغييرات
```powershell
git log --oneline
```

## 🛠️ إعدادات إضافية

### إعداد معلومات المطور
```powershell
git config --global user.name "abobasel86"
git config --global user.email "your-email@example.com"
```

### إنشاء فرع جديد للتطوير
```powershell
git checkout -b development
git push -u origin development
```

## 📋 ملاحظات مهمة

1. **تأكد من .gitignore:** الملف موجود لتجنب رفع ملفات غير مرغوبة
2. **الملفات الحساسة:** لا ترفع كلمات المرور أو مفاتيح API
3. **النسخ الاحتياطية:** GitHub يعمل كنسخة احتياطية لمشروعك
4. **التعاون:** يمكن للآخرين المساهمة في مشروعك

## 🎯 الخطوات السريعة (نسخ ولصق)

```powershell
# انتقل للمجلد
cd "d:\event\wp-content\themes\arab-board-event-2025"

# تهيئة Git
git init
git add .
git commit -m "Initial commit: Arab Board Event 2025 WordPress Theme"

# ربط بـ GitHub (بعد إنشاء Repository)
git remote add origin https://github.com/abobasel86/arab-board-event-2025.git
git branch -M main
git push -u origin main
```

## ✅ التحقق من النجاح

بعد تنفيذ الخطوات، ستجد مشروعك على:
`https://github.com/abobasel86/arab-board-event-2025`

## 🆘 في حالة وجود مشاكل

### مشكلة: "remote origin already exists"
```powershell
git remote remove origin
git remote add origin https://github.com/abobasel86/arab-board-event-2025.git
```

### مشكلة: "Updates were rejected"
```powershell
git pull origin main --allow-unrelated-histories
git push
```

### مشكلة: أسماء المستخدم وكلمة المرور
- استخدم **Personal Access Token** بدلاً من كلمة المرور
- اذهب إلى GitHub Settings > Developer settings > Personal access tokens

---

**الآن مشروعك مربوط بـ GitHub بنجاح! 🎉**