# Rich Media & Enhanced Formatting Features

## Overview
The CMS now supports rich media content including images, videos, and advanced formatting options.

## New Features Added

### 1. Rich Text Editor (TinyMCE)
- **Visual WYSIWYG editor** for pages and posts
- **Formatting tools**: Bold, italic, headings, lists, alignment
- **Media insertion**: Images, videos, embedded content
- **Tables**: Create and edit tables
- **Code blocks**: Insert formatted code
- **Links**: Easy link creation and management

### 2. Media Library
- **Upload images**: JPG, PNG, GIF, WebP, SVG
- **Upload videos**: MP4, WebM, OGG
- **Upload documents**: PDF files
- **Media management**: View, copy URLs, delete files
- **Direct integration**: Drag-drop images into the editor

### 3. Enhanced Content Display
- **Responsive images**: Auto-resize for mobile devices
- **Styled videos**: Rounded corners, proper spacing
- **Tables**: Professional formatting with borders
- **Blockquotes**: Elegant quote styling
- **Code blocks**: Syntax-friendly display

## How to Use

### Uploading Media

1. **Via Media Library**:
   - Navigate to Admin → Media
   - Click "Upload" button
   - Select file(s) from your computer
   - Click "Upload"
   - Copy the URL to use in content

2. **Directly in Editor**:
   - While editing a page/post, click the Image icon
   - Drag and drop or select files
   - Images are automatically uploaded and inserted

### Formatting Content

1. **Text Formatting**:
   - Use toolbar buttons for bold, italic, underline
   - Select heading levels (H1-H6)
   - Create bulleted or numbered lists

2. **Adding Images**:
   - Click Image icon in toolbar
   - Upload new image or enter URL
   - Set alt text for accessibility
   - Adjust dimensions if needed

3. **Embedding Videos**:
   - Click Media icon in toolbar
   - Paste video URL (YouTube, Vimeo, etc.)
   - Or upload video file directly
   - Video will be responsive

4. **Creating Tables**:
   - Click Table icon
   - Select rows and columns
   - Right-click for more options

5. **Adding Links**:
   - Select text
   - Click Link icon
   - Enter URL
   - Choose to open in new tab (optional)

### Best Practices

- **Image sizes**: Optimize images before upload (recommended max 2MB)
- **Video hosting**: For large videos, consider YouTube/Vimeo embedding
- **Alt text**: Always add descriptive alt text for images (SEO & accessibility)
- **File names**: Use descriptive names with hyphens (e.g., "summer-event-2026.jpg")
- **Responsive design**: Content automatically adapts to screen sizes

## Technical Details

### Database Changes
- New `media` table stores uploaded file information
- Tracks filename, file type, size, uploader, and timestamp

### File Structure
```
uploads/           # Media storage directory
  .htaccess        # Security and MIME type configuration
admin/
  media.php        # Media library interface
  upload_handler.php  # Handles file uploads from editor
```

### Supported Formats

**Images**: JPG, JPEG, PNG, GIF, WebP, SVG  
**Videos**: MP4, WebM, OGG  
**Documents**: PDF

### Security
- File type validation on upload
- Unique filename generation to prevent conflicts
- Directory listing disabled
- Proper MIME type headers

## Troubleshooting

**Upload fails**:
- Check file size limits in PHP configuration
- Verify `uploads/` directory has write permissions
- Ensure file type is supported

**Images don't display**:
- Check that uploads directory is accessible
- Verify file path in database
- Check browser console for errors

**Editor doesn't load**:
- Check internet connection (TinyMCE loads from CDN)
- Verify JavaScript is enabled
- Clear browser cache

## Future Enhancements
- Image cropping and editing
- Featured images for posts
- Image galleries
- Video thumbnails
- File organization with folders
