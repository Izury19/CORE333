<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forward Files to Document Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f9fafb;
            margin: 0;
            padding: 20px;
            font-family: system-ui, -apple-system, sans-serif;
        }
        
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 24px;
        }
        
        .form-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .form-subtitle {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        
        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            background-color: #f9fafb;
            color: #6b7280;
            resize: vertical;
        }
        
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.2s;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
        }
        
        .info-box {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1 class="form-title">Forward Files to Document Manager</h1>
            <p class="form-subtitle">Send finalized reports to Administrative Systems</p>
        </div>
        
        <div class="info-box">
            📤 Forwarding to: <strong>admin.cranecali-ms.com</strong><br>
            🔒 Password protected: <strong>document</strong> (open), <strong>admin</strong> (edit)
        </div>
        
        <form id="forwardForm">
            <!-- Document Type -->
            <div class="form-group">
                <label class="form-label">Document Type</label>
                <select class="form-control" name="document_type" id="documentType" disabled>
                    <option>Financial Intelligence Report</option>
                    <option>Regulatory Compliance Report</option>
                    <option>Project Status Update</option>
                </select>
            </div>
            
            <!-- Category -->
            <div class="form-group">
                <label class="form-label">Category</label>
                <select class="form-control" name="category" id="category" disabled>
                    <option>Financial</option>
                    <option>Compliance</option>
                    <option>Project</option>
                </select>
            </div>
            
            <!-- Submit Button -->
            <button type="button" class="btn-primary" onclick="submitForward()">
                Forward to Document Manager
            </button>
        </form>
    </div>

    <script>
        // Auto-fill form if data was passed
        document.addEventListener('DOMContentLoaded', function() {
            const docType = localStorage.getItem('forwardDocumentType');
            const category = localStorage.getItem('forwardCategory');
            
            if(docType && category) {
                document.getElementById('documentType').value = docType;
                document.getElementById('category').value = category;
            }
        });
        
        function submitForward() {
            const formData = {
                document_type: document.getElementById('documentType').value,
                category: document.getElementById('category').value
            };
            
            // Show loading state
            const btn = event.target;
            const originalText = btn.textContent;
            btn.textContent = 'Forwarding...';
            btn.disabled = true;
            
            // Send to your controller (which forwards to admin API)
            fetch('/CORE333/public/forward-document', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('✅ SUCCESS!\n\n' + data.message + '\n\nFile: ' + data.filename + '\nCategory: ' + data.category + '\n\n📄 Sent to: admin.calicrane-ms.com');
                    
                    // Close modal
                    if (window.parent !== window) {
                        window.parent.closeForwardModal();
                    }
                } else {
                    alert('❌ Error: ' + data.message + '\n\nPlease contact system administrator.');
                }
                
                // Reset button
                btn.textContent = originalText;
                btn.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Network error - please check your connection and try again.');
                btn.textContent = originalText;
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>