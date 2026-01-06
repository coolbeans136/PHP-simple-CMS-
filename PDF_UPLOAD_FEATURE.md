# PDF Upload Feature for CMS

## What's Been Added

I've added PDF upload functionality to your CMS that **preserves formatting** from the PDF document.

### Features:

1. **Upload PDF files** to pages and posts
2. **Automatically extracts content** while preserving:
   - Headings (converted to H3 tags)
   - Paragraphs
   - Lists (bullet points and numbered)
   - Text structure and layout

3. **Smart content detection**:
   - Extracts titles from document headings
   - Auto-generates slugs from titles
   - Creates excerpts from first paragraph (for posts)

### Files Created/Modified:

- **`/admin/pdf_processor.php`** - New PDF processing class
- **`/admin/edit_page.php`** - Updated with PDF upload field
- **`/admin/edit_post.php`** - Updated with PDF upload field  
- **`/uploads/pdfs/`** - Directory for uploaded PDFs

### How It Works:

The system uses three methods to extract PDF content (in order of preference):

1. **pdftohtml** - Preserves formatting best, converts to HTML
2. **pdftotext with layout** - Preserves text positioning and structure
3. **basic pdftotext** - Fallback for simple text extraction

The extracted text is then converted to clean HTML with:
- Headings detected from uppercase text, title case, or numbered sections
- Lists identified by bullets (-, *, •) or numbers
- Paragraphs properly formatted
- Structure preserved

### How to Use:

1. Go to **Add New Page** or **Add New Post**
2. Click the **"Upload PDF"** field
3. Select your PDF file
4. Click **Save**
5. The content will be automatically extracted and populated!

### Requirements:

✅ **poppler-utils** installed (includes pdftotext and pdftohtml)
✅ **uploads/pdfs/** directory created and writable

Both are already set up and ready to use!

### Example:

When you upload a PDF with this structure:
```
INTRODUCTION

This is the first paragraph of content.

Features:
- Feature one
- Feature two
```

It becomes:
```html
<h3>INTRODUCTION</h3>
<p>This is the first paragraph of content.</p>
<h3>Features:</h3>
<ul>
  <li>Feature one</li>
  <li>Feature two</li>
</ul>
```

The formatting structure from your PDF is maintained in the HTML output!
