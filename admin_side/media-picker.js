// Media Picker JavaScript
class MediaPicker {
    constructor() {
        this.modal = null;
        this.callback = null;
        this.selectedMedia = null;
        this.initialize();
    }

    initialize() {
        // Create modal HTML
        const modalHtml = `
            <div id="mediaPickerModal" class="media-picker-modal">
                <div class="media-picker-content">
                    <div class="media-picker-header">
                        <h3><i class="bi bi-images"></i> Select Media</h3>
                        <button class="media-picker-close" onclick="mediaPicker.close()">&times;</button>
                    </div>
                    <div class="media-picker-body">
                        <div id="mediaPickerGrid" class="media-picker-grid"></div>
                        <div id="mediaPickerEmpty" class="media-picker-empty" style="display:none;">
                            <i class="bi bi-images" style="font-size: 64px; color: #ddd;"></i>
                            <p>No media files yet. Upload one below!</p>
                        </div>
                    </div>
                    <div class="media-picker-footer">
                        <div class="media-picker-upload">
                            <input type="file" id="quickUpload" accept="image/*,video/*,.pdf" style="display:none;">
                            <button class="btn btn-secondary" onclick="document.getElementById('quickUpload').click()">
                                <i class="bi bi-upload"></i> Quick Upload
                            </button>
                            <span id="uploadStatus"></span>
                        </div>
                        <div>
                            <button class="btn btn-secondary" onclick="mediaPicker.close()">Cancel</button>
                            <button class="btn btn-primary" onclick="mediaPicker.select()">
                                <i class="bi bi-check-lg"></i> Select
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Add to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        this.modal = document.getElementById('mediaPickerModal');
        
        // Set up quick upload
        document.getElementById('quickUpload').addEventListener('change', (e) => this.handleQuickUpload(e));
        
        // Close on outside click
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.close();
            }
        });
    }

    async open(callback) {
        this.callback = callback;
        this.selectedMedia = null;
        await this.loadMedia();
        this.modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    close() {
        this.modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        this.selectedMedia = null;
    }

    async loadMedia() {
        try {
            const response = await fetch('get_media.php');
            const media = await response.json();
            
            const grid = document.getElementById('mediaPickerGrid');
            const empty = document.getElementById('mediaPickerEmpty');
            
            if (media.length === 0) {
                grid.innerHTML = '';
                empty.style.display = 'block';
                return;
            }
            
            empty.style.display = 'none';
            grid.innerHTML = media.map(item => this.createMediaItem(item)).join('');
            
            // Add click handlers
            grid.querySelectorAll('.media-picker-item').forEach(item => {
                item.addEventListener('click', () => this.selectItem(item));
            });
        } catch (error) {
            console.error('Error loading media:', error);
        }
    }

    createMediaItem(item) {
        let preview = '';
        if (item.file_type === 'image') {
            preview = `<img src="${item.file_path}" alt="${item.original_filename}">`;
        } else if (item.file_type === 'video') {
            preview = `<video src="${item.file_path}"></video>`;
        } else {
            preview = `<i class="bi bi-file-earmark icon"></i>`;
        }
        
        return `
            <div class="media-picker-item" data-id="${item.id}" data-path="${item.file_path}">
                ${preview}
                <div class="media-picker-item-name">${item.original_filename}</div>
            </div>
        `;
    }

    selectItem(element) {
        // Remove previous selection
        document.querySelectorAll('.media-picker-item').forEach(item => {
            item.classList.remove('selected');
        });
        
        // Add selection
        element.classList.add('selected');
        this.selectedMedia = {
            id: element.dataset.id,
            path: element.dataset.path
        };
    }

    select() {
        if (this.selectedMedia && this.callback) {
            this.callback(this.selectedMedia.path);
            this.close();
        } else {
            alert('Please select a media item first');
        }
    }

    async handleQuickUpload(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        const status = document.getElementById('uploadStatus');
        status.innerHTML = '<span class="text-muted">Uploading...</span>';
        
        const formData = new FormData();
        formData.append('media_file', file);
        
        try {
            const response = await fetch('media.php', {
                method: 'POST',
                body: formData
            });
            
            if (response.ok) {
                status.innerHTML = '<span class="text-success">✓ Uploaded!</span>';
                setTimeout(() => status.innerHTML = '', 2000);
                await this.loadMedia();
            } else {
                status.innerHTML = '<span class="text-danger">Upload failed</span>';
            }
        } catch (error) {
            console.error('Upload error:', error);
            status.innerHTML = '<span class="text-danger">Upload error</span>';
        }
        
        event.target.value = '';
    }
}

// Initialize global instance
let mediaPicker;
document.addEventListener('DOMContentLoaded', () => {
    mediaPicker = new MediaPicker();
});

// Helper function to open picker
function openMediaPicker(callback) {
    mediaPicker.open(callback);
}
