<?php
// PDF Processing Helper
require_once '../config.php';

class PDFProcessor {
    private $uploadDir = '../uploads/pdfs/';
    
    public function __construct() {
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
    
    /**
     * Extract text from PDF and convert to HTML while preserving formatting
     */
    public function extractToHTML($pdfPath) {
        // Use pdftohtml to preserve formatting
        $tempHtml = tempnam(sys_get_temp_dir(), 'pdf_') . '.html';
        
        // Try pdftohtml first (preserves formatting better)
        exec("pdftohtml -stdout -noframes " . escapeshellarg($pdfPath) . " 2>&1", $output, $return);
        
        if ($return === 0 && !empty($output)) {
            $html = implode("\n", $output);
            return $this->cleanHTML($html);
        }
        
        // Fallback to pdftotext with layout preservation
        exec("pdftotext -layout -nopgbrk " . escapeshellarg($pdfPath) . " - 2>&1", $output, $return);
        
        if ($return === 0 && !empty($output)) {
            $text = implode("\n", $output);
            return $this->textToHTML($text);
        }
        
        // Final fallback - basic pdftotext
        exec("pdftotext " . escapeshellarg($pdfPath) . " - 2>&1", $output, $return);
        
        if ($return === 0 && !empty($output)) {
            $text = implode("\n", $output);
            return $this->textToHTML($text);
        }
        
        throw new Exception("Could not extract text from PDF. Make sure poppler-utils is installed.");
    }
    
    /**
     * Convert plain text to formatted HTML
     */
    private function textToHTML($text) {
        $html = '';
        $lines = explode("\n", $text);
        $inParagraph = false;
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (empty($line)) {
                if ($inParagraph) {
                    $html .= "</p>\n";
                    $inParagraph = false;
                }
                continue;
            }
            
            // Detect headings (lines that are short and possibly in caps or title case)
            if (strlen($line) < 60 && (
                $line === strtoupper($line) || // ALL CAPS
                ucwords(strtolower($line)) === $line || // Title Case
                preg_match('/^[\d\.]+\s+[A-Z]/', $line) // Numbered heading
            )) {
                if ($inParagraph) {
                    $html .= "</p>\n";
                    $inParagraph = false;
                }
                $html .= "<h3>" . htmlspecialchars($line) . "</h3>\n";
                continue;
            }
            
            // Detect lists
            if (preg_match('/^[\-\*\•]\s+/', $line) || preg_match('/^\d+[\.\)]\s+/', $line)) {
                if ($inParagraph) {
                    $html .= "</p>\n";
                    $inParagraph = false;
                }
                $cleaned = preg_replace('/^[\-\*\•\d\.\)]+\s+/', '', $line);
                $html .= "<li>" . htmlspecialchars($cleaned) . "</li>\n";
                continue;
            }
            
            // Regular paragraph text
            if (!$inParagraph) {
                $html .= "<p>";
                $inParagraph = true;
            } else {
                $html .= " ";
            }
            
            $html .= htmlspecialchars($line);
        }
        
        if ($inParagraph) {
            $html .= "</p>\n";
        }
        
        // Wrap lists in ul tags
        $html = preg_replace('/(<li>.*?<\/li>\n)+/s', '<ul>$0</ul>', $html);
        
        return $html;
    }
    
    /**
     * Clean HTML extracted from pdftohtml
     */
    private function cleanHTML($html) {
        // Remove HTML header/footer
        $html = preg_replace('/<\!DOCTYPE.*?<body[^>]*>/is', '', $html);
        $html = preg_replace('/<\/body>.*?<\/html>/is', '', $html);
        
        // Remove inline styles but keep structure
        $html = preg_replace('/ style="[^"]*"/', '', $html);
        $html = preg_replace('/ class="[^"]*"/', '', $html);
        
        // Convert common PDF artifacts
        $html = str_replace('&nbsp;', ' ', $html);
        $html = preg_replace('/\s+/', ' ', $html);
        
        // Remove empty tags
        $html = preg_replace('/<p>\s*<\/p>/', '', $html);
        $html = preg_replace('/<div>\s*<\/div>/', '', $html);
        
        return trim($html);
    }
    
    /**
     * Upload and process PDF file
     */
    public function uploadPDF($file) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Upload failed with error code: " . $file['error']);
        }
        
        // Validate PDF
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if ($mimeType !== 'application/pdf') {
            throw new Exception("File must be a PDF. Detected type: " . $mimeType);
        }
        
        // Generate unique filename
        $filename = uniqid('pdf_', true) . '.pdf';
        $destination = $this->uploadDir . $filename;
        
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("Failed to save uploaded file");
        }
        
        return $destination;
    }
}
?>
