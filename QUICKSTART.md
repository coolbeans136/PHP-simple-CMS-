# 🚀 Quick Start: Rich Media Features

## Setup (One-Time)

### Step 1: Update Database
Run the automated setup:
```bash
./setup_media.sh
```

Or manually:
```bash
mysql -u root -p cms_db < update_db.sql
```

### Step 2: Verify Permissions
```bash
chmod 755 uploads/
```

That's it! ✅

## Using Rich Media

### 📸 Upload Images

**Method 1: Via Media Library**
1. Go to **Admin → Media**
2. Click file input, select image
3. Click **Upload**
4. Click 📋 copy icon to get URL

**Method 2: Directly in Editor**
1. Edit any page/post
2. Click 🖼️ Image icon in editor toolbar
3. Drag & drop or select file
4. Image automatically uploads and inserts

### 🎥 Add Videos

**Embedded (YouTube/Vimeo)**
1. In editor, click 📹 Media icon
2. Paste video URL
3. Click OK

**Uploaded Video**
1. Click 📹 Media icon
2. Upload tab → Choose file
3. Select MP4/WebM/OGG
4. Click OK

### ✏️ Format Content

| Feature | Icon | Shortcut |
|---------|------|----------|
| **Bold** | **B** | Ctrl+B |
| *Italic* | *I* | Ctrl+I |
| Heading | ¶ | Dropdown |
| Link | 🔗 | Ctrl+K |
| List | • | Button |
| Table | ⊞ | Button |
| Code | </> | Button |

### 📊 Create Tables

1. Click table icon (⊞)
2. Select rows × columns
3. Enter content in cells
4. Right-click for more options

### 💡 Tips

✅ **Image optimization**: Resize images before upload (max 2MB)  
✅ **Alt text**: Add descriptions for accessibility  
✅ **Responsive**: All media auto-resizes for mobile  
✅ **Videos**: Use YouTube/Vimeo for better performance  
✅ **URLs**: Media Library provides direct URLs  

## Features Available

✅ Visual WYSIWYG editor  
✅ Image upload & insertion  
✅ Video embedding  
✅ Table creation  
✅ Text formatting  
✅ Code blocks  
✅ Blockquotes  
✅ Links  
✅ Lists (bulleted/numbered)  
✅ Text alignment  
✅ Colors & fonts  
✅ Media library  

## Common Tasks

### Change Image Size
1. Click on image in editor
2. Drag corners to resize
3. Or right-click → Image properties

### Add Link to Text
1. Select text
2. Press Ctrl+K or click 🔗
3. Enter URL
4. Choose "Open in new tab" if desired

### Insert Code Block
1. Click </> Code button
2. Enter code
3. Or use backticks in Markdown mode

### Format as Quote
1. Type text
2. Select it
3. Click Format → Formats → Blockquote

## Browser Access

**Admin Panel**: `http://yoursite.com/admin/`  
**Media Library**: `http://yoursite.com/admin/media.php`  
**Public Pages**: `http://yoursite.com/public/pages.php`  

## Need Help?

📖 **Full Documentation**: See [README_MEDIA.md](README_MEDIA.md)  
📝 **Changes Log**: See [CHANGES.md](CHANGES.md)  
🐛 **Issues**: Check troubleshooting section in README_MEDIA.md  

---

**You're all set! Start creating rich content! 🎨**
