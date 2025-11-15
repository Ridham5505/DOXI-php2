<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Review & Rating</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        (function(){
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', savedTheme);
            }catch(_e){}
        })();
    </script>
    <link rel="stylesheet" href="styles.css">
    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-blue-dark: #1d4ed8;
        }
        [data-theme="dark"]{
            --primary-blue: #3b82f6;
            --primary-blue-dark: #2563eb;
            --white: #0f172a;
            --gray-50:#0b1220;
            --gray-100:#111827;
            --gray-200:#1f2937;
            --gray-300:#374151;
            --gray-500:#9ca3af;
            --gray-600:#d1d5db;
            --gray-700:#e5e7eb;
            --gray-900:#ffffff;
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.3);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 10px 10px -5px rgba(0, 0, 0, 0.3);
        }
        html, body { margin:0; background: var(--gray-50); color: var(--gray-900); transition: background 0.3s, color 0.3s; }
        body { background: var(--gray-50); }
        .page { max-width: 1100px; margin: 40px auto; padding: 0 var(--spacing-6); }
        .header { display:flex; align-items:center; justify-content: space-between; margin-bottom: var(--spacing-6); flex-wrap:wrap; gap:var(--spacing-4); }
        .header-left{display:flex; flex-direction:column; gap:6px;}
        .logo{display:flex; align-items:center;}
        .logo img{display:block;height:48px;width:auto;}
        .card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-xl); box-shadow: var(--shadow-md); padding: var(--spacing-6); margin-bottom: var(--spacing-6); }
        .grid { display:grid; gap: var(--spacing-6); }
        .cols-2 { grid-template-columns: 1fr 1fr; }
        @media (max-width: 900px){ .cols-2{ grid-template-columns: 1fr; } }
        label { font-weight:600; color: var(--gray-700); margin-bottom: var(--spacing-2); display:block; }
        select, textarea { width:100%; padding: var(--spacing-3) var(--spacing-4); border: 2px solid var(--gray-200); border-radius: var(--radius-md); font-family: var(--font-family); }
        textarea { min-height: 100px; resize: vertical; }
        .error { color: #dc2626; font-size: var(--font-size-sm); margin-top: 4px; }
        .invalid { border-color: #dc2626 !important; }
        .btn-row { display:flex; gap: var(--spacing-3); justify-content: flex-end; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 12px 14px; border-bottom: 1px solid var(--gray-200); text-align: left; }
        .table th { color: var(--gray-600); font-weight: 600; background: var(--gray-50); }
        .actions { display:flex; gap:8px; }
        .link { color: var(--primary-blue); font-weight:600; text-decoration:none; }
        .link:hover { text-decoration: underline; }
        .back { text-decoration:none; color: var(--gray-600); }
        .rating-stars { display:flex; gap: var(--spacing-2); align-items:center; margin: var(--spacing-3) 0; }
        .star { font-size: var(--font-size-2xl); cursor: pointer; color: var(--gray-300); transition: color 0.2s; }
        .star.active, .star:hover { color: #fbbf24; }
        .rating-value { font-weight: 600; color: var(--gray-700); margin-left: var(--spacing-2); }
        .review-item { background: var(--gray-50); padding: var(--spacing-4); border-radius: var(--radius-md); margin-bottom: var(--spacing-3); border-left: 4px solid var(--primary-blue); }
        .review-header { display:flex; justify-content:space-between; align-items:start; margin-bottom: var(--spacing-2); }
        .review-doctor { font-weight:700; color: var(--gray-900); }
        .review-date { font-size: var(--font-size-sm); color: var(--gray-600); }
        .review-rating { margin: var(--spacing-2) 0; }
        .review-comment { color: var(--gray-700); line-height: 1.6; }
        .empty { padding: var(--spacing-6); text-align:center; color: var(--gray-500); }
        /* Modal styles */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: var(--white); margin: 5% auto; padding: var(--spacing-6); border-radius: var(--radius-xl); box-shadow: var(--shadow-xl); width: 90%; max-width: 600px; position: relative; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4); border-bottom: 1px solid var(--gray-200); padding-bottom: var(--spacing-4); }
        .modal-title { font-size: var(--font-size-xl); font-weight: 700; color: var(--gray-900); }
        .close-modal { color: var(--gray-500); font-size: 28px; font-weight: bold; cursor: pointer; background: none; border: none; padding: 0; line-height: 1; }
        .close-modal:hover { color: var(--gray-900); }
        .modal-body { color: var(--gray-700); }
        .detail-row { display: flex; padding: var(--spacing-3) 0; border-bottom: 1px solid var(--gray-100); }
        .detail-label { font-weight: 600; color: var(--gray-700); min-width: 120px; }
        .detail-value { color: var(--gray-900); flex: 1; }
    </style>
    <link rel="icon" type="image/svg+xml" href="public/assets/doxi-icon.svg?v=1">
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="header-left">
                <div class="logo">
                    <img src="public/assets/doxi-lockup.svg" alt="DOXI logo" width="120" height="40">
                </div>
                <div class="tagline">Review & Rating</div>
            </div>
            <div style="display:flex; align-items:center; gap: var(--spacing-4);">
                <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()" style="padding: 8px 14px; border-radius: 8px; border: 1px solid var(--gray-200); background: var(--white); color: var(--gray-700); cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s;">
                    <span id="theme-icon">🌙</span>
                    <span id="theme-text">Dark</span>
                </button>
                <a class="back" href="patient-dashboard.php">← Back to Dashboard</a>
            </div>
        </div>

        <div class="grid cols-2">
            <!-- Submit Review Form -->
            <div class="card">
                <h3 style="margin-bottom: var(--spacing-4); color: var(--gray-900);">Submit Review</h3>
                <form id="review-form">
                    <input type="hidden" id="review-id">
                    <div style="margin-bottom: var(--spacing-4);">
                        <label for="doctor">Doctor</label>
                        <select id="doctor" required>
                            <option value="" selected disabled>Select Doctor...</option>
                        </select>
                        <div id="err-doctor" class="error"></div>
                    </div>
                    <div style="margin-bottom: var(--spacing-4);">
                        <label>Rating</label>
                        <div class="rating-stars" id="rating-stars">
                            <span class="star" data-rating="1">⭐</span>
                            <span class="star" data-rating="2">⭐</span>
                            <span class="star" data-rating="3">⭐</span>
                            <span class="star" data-rating="4">⭐</span>
                            <span class="star" data-rating="5">⭐</span>
                            <span class="rating-value" id="rating-text">Not rated</span>
                        </div>
                        <input type="hidden" id="rating" required>
                        <div id="err-rating" class="error"></div>
                    </div>
                    <div style="margin-bottom: var(--spacing-4);">
                        <label for="comment">Comment <span style="font-weight:400; color: var(--gray-500);">(No numbers allowed)</span></label>
                        <textarea id="comment" placeholder="Write your review in words only..." required maxlength="1000"></textarea>
                        <div id="err-comment" class="error"></div>
                    </div>
                    <div class="btn-row">
                        <button type="button" class="btn btn-outline" onclick="resetReviewForm()">Clear</button>
                        <button type="submit" class="btn btn-primary" id="submit-btn">Submit Review</button>
                    </div>
                </form>
            </div>

            <!-- My Reviews -->
            <div class="card">
                <h3 style="margin-bottom: var(--spacing-4); color: var(--gray-900);">My Reviews</h3>
                <div id="my-reviews"></div>
            </div>
        </div>
    </div>

    <!-- Modal for viewing review details -->
    <div id="view-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Review Details</h3>
                <button class="close-modal" onclick="closeViewModal()">&times;</button>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Review details will be populated here -->
            </div>
        </div>
    </div>

    <script>
        if (sessionStorage.getItem('isLoggedIn') !== 'true' || sessionStorage.getItem('userRole') !== 'patient') {
            window.location.href = 'login.php';
        }

        let currentPatientId = null;
        let selectedRating = 0;
        const REVIEW_EDIT_WINDOW_SECONDS = 30 * 60; // 30 minutes
        
        function getJSON(url){ return fetch(url).then(r=>r.json()); }
        function api(path, method='GET', body){
            return fetch(path, { method, headers:{ 'Content-Type':'application/json' }, body: body ? JSON.stringify(body) : undefined }).then(r=>r.json());
        }
        
        function esc(s){ return (s||'').toString().replace(/[&<>'"]/g, c=>({"&":"&amp;","<":"&lt;",">":"&gt;","'":"&#39;",'"':"&quot;"}[c])); }

        async function loadCurrentPatient(){
            const email = sessionStorage.getItem('userEmail');
            const res = await getJSON(`api/users.php?search=${encodeURIComponent(email)}&page=1&limit=1`);
            if (res.success && res.data && res.data.length){ 
                currentPatientId = res.data[0].id;
            }
        }

        async function loadDoctors(){
            const sel = document.getElementById('doctor');
            sel.innerHTML = '<option value="" selected disabled>Loading doctors...</option>';
            const res = await getJSON('api/users.php?role=doctor&doctor_status=approved&page=1&limit=100');
            sel.innerHTML='<option value="" selected disabled>Select Doctor...</option>';
            if (res.success){
                res.data.forEach(d=>{
                    const o = document.createElement('option');
                    o.value = d.id; 
                    o.textContent = `Dr. ${(d.first_name||'')} ${(d.last_name||'')}`.trim() || d.email; 
                    sel.appendChild(o);
                });
            }
        }

        function setupRatingStars(){
            const stars = document.querySelectorAll('.star');
            const ratingText = document.getElementById('rating-text');
            const ratingInput = document.getElementById('rating');
            
            stars.forEach((star, index)=>{
                star.addEventListener('click', ()=>{
                    selectedRating = index + 1;
                    ratingInput.value = selectedRating;
                    updateStarDisplay();
                    ratingText.textContent = `${selectedRating} out of 5`;
                });
                star.addEventListener('mouseenter', ()=>{
                    highlightStars(index + 1);
                });
            });
            
            document.getElementById('rating-stars').addEventListener('mouseleave', ()=>{
                updateStarDisplay();
            });
        }

        function highlightStars(count){
            const stars = document.querySelectorAll('.star');
            stars.forEach((star, index)=>{
                if (index < count){
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        function updateStarDisplay(){
            const stars = document.querySelectorAll('.star');
            stars.forEach((star, index)=>{
                if (index < selectedRating){
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        function parseDateTime(value){
            if (!value) return null;
            const normalized = value.replace(' ', 'T');
            const timestamp = Date.parse(normalized);
            return Number.isNaN(timestamp) ? null : timestamp;
        }

        function getRemainingEditableSeconds(review){
            if (!review || !review.created_at) return 0;
            const createdAt = parseDateTime(review.created_at);
            if (createdAt === null) return 0;
            const elapsedSeconds = Math.floor((Date.now() - createdAt) / 1000);
            return Math.max(0, REVIEW_EDIT_WINDOW_SECONDS - elapsedSeconds);
        }

        function formatCountdown(seconds){
            const mins = Math.floor(seconds / 60).toString().padStart(2,'0');
            const secs = Math.floor(seconds % 60).toString().padStart(2,'0');
            return `${mins}:${secs}`;
        }

        function isReviewEditable(review){
            return getRemainingEditableSeconds(review) > 0;
        }

        async function loadMyReviews(){
            if (!currentPatientId) return;
            const res = await getJSON(`api/reviews.php?patient_id=${currentPatientId}&limit=100`);
            const container = document.getElementById('my-reviews');
            
            if (!res.success || !res.data){
                container.innerHTML = '<div class="empty">Failed to load reviews.</div>';
                return;
            }

            const myReviews = (res.data || []).filter(r=> r.patient_id == currentPatientId);
            
            if (!myReviews.length){
                container.innerHTML = '<div class="empty">You haven\'t submitted any reviews yet.</div>';
                return;
            }

            container.innerHTML = '';
            myReviews.forEach(r=>{
                const el = document.createElement('div');
                el.className = 'review-item';
                const date = r.created_at ? new Date(r.created_at).toLocaleDateString() : 'N/A';
                const stars = '⭐'.repeat(r.rating || 0);
                const remainingSeconds = getRemainingEditableSeconds(r);
                const editable = remainingSeconds > 0;
                const timerText = editable ? `Editable for ${formatCountdown(remainingSeconds)}` : 'Final review';
                const timerColor = editable ? 'var(--primary-blue)' : 'var(--gray-500)';
                el.innerHTML = `
                    <div class="review-header">
                        <div class="review-doctor">${esc(r.doctor_name || 'Unknown Doctor')}</div>
                        <div class="review-date">${esc(date)}</div>
                    </div>
                    <div class="review-rating">Rating: ${stars} (${r.rating}/5)</div>
                    <div class="review-comment">${esc(r.comment || '')}</div>
                    <div style="margin-top: var(--spacing-3); display:flex; align-items:center; justify-content:space-between; gap: var(--spacing-3); flex-wrap:wrap;">
                        <div class="review-timer" style="font-weight:600; color:${timerColor};">${timerText}</div>
                        <div class="actions">
                            <a href="#" class="link" onclick="viewReview(${r.id});return false;">View</a>
                            ${editable ? `<a href="#" class="link" onclick="editReview(${r.id});return false;">Edit</a>` : ''}
                            ${editable ? `<a href="#" class="link" onclick="deleteReview(${r.id});return false;">Delete</a>` : ''}
                        </div>
                    </div>
                `;
                container.appendChild(el);
            });
        }

        function resetReviewForm(){
            document.getElementById('review-id').value = '';
            document.getElementById('doctor').value = '';
            document.getElementById('doctor').selectedIndex = 0;
            selectedRating = 0;
            document.getElementById('rating').value = '';
            document.getElementById('rating-text').textContent = 'Not rated';
            document.getElementById('comment').value = '';
            updateStarDisplay();
            document.getElementById('submit-btn').textContent = 'Submit Review';
            clearErrors();
        }

        function clearErrors(){
            setError('err-doctor','', 'doctor');
            setError('err-rating','', 'rating');
            setError('err-comment','', 'comment');
        }

        function validateCommentField(){
            const input = document.getElementById('comment');
            if (!input) return true;
            const errId = 'err-comment';
            const val = input.value.trim();
            let msg = '';
            if (!val || val.length === 0){
                msg = 'Please write a review.';
            } else if (/\d/.test(val)){
                msg = 'Numbers are not allowed. Please write your review in words only.';
            } else if (val.length < 10){
                msg = 'Please write a detailed review (at least 10 characters).';
            } else if (val.length > 1000){
                msg = 'Comment must be 1000 characters or fewer.';
            }
            setError(errId, msg, 'comment');
            return !msg;
        }

        function setError(id, message, inputId){
            const el = document.getElementById(id);
            if (el) el.textContent = message || '';
            if (inputId){
                const input = document.getElementById(inputId);
                if (input) input.classList.toggle('invalid', !!message);
            }
        }

        async function editReview(id){
            const res = await getJSON(`api/reviews.php?id=${id}`);
            if (res.success && res.data){
                const r = res.data;
                if (!isReviewEditable(r)){
                    alert('This review can no longer be edited (30 minutes have passed).');
                    loadMyReviews();
                    return;
                }
                document.getElementById('review-id').value = r.id;
                document.getElementById('doctor').value = r.doctor_id;
                selectedRating = r.rating;
                document.getElementById('rating').value = r.rating;
                document.getElementById('rating-text').textContent = `${r.rating} out of 5`;
                document.getElementById('comment').value = r.comment || '';
                updateStarDisplay();
                document.getElementById('submit-btn').textContent = 'Update Review';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        async function deleteReview(id){
            if (!confirm('Delete this review?')) return;
            const res = await api('api/reviews.php','DELETE',{ id, patient_id: currentPatientId });
            if (res.success){ 
                loadMyReviews(); 
            } else { 
                alert(res.message || 'Delete failed'); 
            }
        }

        async function viewReview(id){
            const res = await getJSON(`api/reviews.php?id=${id}`);
            if (!res.success || !res.data){
                alert('Failed to load review details.');
                return;
            }
            const r = res.data;
            const modalBody = document.getElementById('modal-body');
            const date = r.created_at ? new Date(r.created_at).toLocaleDateString() : 'N/A';
            const updatedDate = r.updated_at && r.updated_at !== r.created_at ? new Date(r.updated_at).toLocaleDateString() : null;
            const stars = '⭐'.repeat(r.rating || 0);
            const timerText = isReviewEditable(r) ? `Editable for ${formatCountdown(getRemainingEditableSeconds(r))}` : 'Final review';
            
            modalBody.innerHTML = `
                <div class="detail-row">
                    <div class="detail-label">Doctor Name:</div>
                    <div class="detail-value">${esc(r.doctor_name || 'Unknown Doctor')}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Patient Name:</div>
                    <div class="detail-value">${esc(r.patient_name || 'Anonymous')}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Rating:</div>
                    <div class="detail-value">${stars} (${r.rating}/5)</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Comment:</div>
                    <div class="detail-value" style="line-height: 1.6;">${esc(r.comment || 'No comment provided')}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Submitted:</div>
                    <div class="detail-value">${esc(date)}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Edit Window:</div>
                    <div class="detail-value">${esc(timerText)}</div>
                </div>
                ${updatedDate ? `
                <div class="detail-row">
                    <div class="detail-label">Last Updated:</div>
                    <div class="detail-value">${esc(updatedDate)}</div>
                </div>
                ` : ''}
            `;
            document.getElementById('view-modal').style.display = 'block';
        }

        function closeViewModal(){
            document.getElementById('view-modal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            const modal = document.getElementById('view-modal');
            if (e.target === modal){
                closeViewModal();
            }
        });

        function validateForm(){
            clearErrors();
            let valid = true;
            const doctor = document.getElementById('doctor').value;
            const rating = document.getElementById('rating').value;
            const comment = document.getElementById('comment').value.trim();

            if (!doctor || doctor === ''){
                valid = false;
                setError('err-doctor','Please select a doctor.','doctor');
            }

            if (!rating || rating === '' || parseInt(rating) < 1 || parseInt(rating) > 5){
                valid = false;
                setError('err-rating','Please select a rating (1-5 stars).','rating');
            }

            if (!comment || comment.trim().length === 0){
                valid = false;
                setError('err-comment','Please write a review.','comment');
            } else if (/\d/.test(comment)){
                valid = false;
                setError('err-comment','Numbers are not allowed. Please write your review in words only.','comment');
            } else if (comment.trim().length < 10){
                valid = false;
                setError('err-comment','Please write a detailed review (at least 10 characters).','comment');
            } else if (comment.length > 1000){
                valid = false;
                setError('err-comment','Comment must be 1000 characters or fewer.','comment');
            }

            return valid;
        }

        document.getElementById('review-form').addEventListener('submit', async (e)=>{
            e.preventDefault();
            if (!validateForm()) return;
            if (!currentPatientId){ alert('User not loaded yet'); return; }

            const payload = {
                doctor_id: parseInt(document.getElementById('doctor').value),
                patient_id: currentPatientId,
                rating: parseInt(document.getElementById('rating').value),
                comment: document.getElementById('comment').value.trim()
            };

            const reviewId = document.getElementById('review-id').value;
            const submitBtn = document.getElementById('submit-btn');
            const prev = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            let res;
            if (reviewId){
                payload.id = parseInt(reviewId);
                res = await api('api/reviews.php','PUT', payload);
            } else {
                res = await api('api/reviews.php','POST', payload);
            }

            if (res.success){
                resetReviewForm();
                loadMyReviews();
            } else {
                alert(res.message || 'Failed to submit review');
            }

            submitBtn.disabled = false;
            submitBtn.textContent = prev;
        });

        (async function init(){
            await loadCurrentPatient();
            await loadDoctors();
            await loadMyReviews();
            setupRatingStars();
            // Add real-time validation for comment field
            document.getElementById('comment').addEventListener('input', validateCommentField);
            document.getElementById('comment').addEventListener('change', validateCommentField);
        })();

        // Theme toggle functionality
        function applyTheme(theme){
            const t = (theme === 'dark') ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', t);
            try{ localStorage.setItem('theme', t); }catch(_e){}
            updateThemeToggle(t);
        }

        function updateThemeToggle(theme){
            const icon = document.getElementById('theme-icon');
            const text = document.getElementById('theme-text');
            if (icon && text){
                if (theme === 'dark'){
                    icon.textContent = '☀️';
                    text.textContent = 'Light';
                } else {
                    icon.textContent = '🌙';
                    text.textContent = 'Dark';
                }
            }
        }

        function toggleTheme(){
            const current = document.documentElement.getAttribute('data-theme') || 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            applyTheme(next);
        }

        (function(){
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                updateThemeToggle(savedTheme);
            }catch(_e){}
        })();
    </script>
</body>
</html>
