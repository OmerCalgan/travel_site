document.addEventListener("DOMContentLoaded", function() {
    // Tur detayları sayfasındaki yorumları yükle
    if (document.getElementById('tour-details')) {
        loadReviews();
    }

    // Yorum gönderme formu
    if (document.getElementById('add-review-form')) {
        document.getElementById('add-review-form').addEventListener('submit', function(e) {
            e.preventDefault();
            submitReview();
        });
    }
});

// AJAX ile yorumları yükle
function loadReviews() {
    const tourId = document.getElementById('tour-details').dataset.tourId;
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_reviews.php?tour_id=' + tourId, true);
    xhr.onload = function() {
        if (this.status == 200) {
            const reviews = JSON.parse(this.responseText);
            let output = '';

            if (reviews.length > 0) {
                reviews.forEach(function(review) {
                    output += `
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Rating: ${review.rating}</h5>
                                <p class="card-text">${review.comment}</p>
                                <p class="card-text"><small class="text-muted">Reviewed by User ${review.user_id} on ${review.created_at}</small></p>
                            </div>
                        </div>
                    `;
                });
            } else {
                output = '<p class="text-center">No reviews yet. Be the first to review this tour!</p>';
            }

            document.getElementById('reviews').innerHTML = output;
        }
    }
    xhr.send();
}

// AJAX ile yorum gönder
function submitReview() {
    const tourId = document.getElementById('tour_id').value;
    const rating = document.getElementById('rating').value;
    const comment = document.getElementById('comment').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'submit_review.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status == 200) {
            alert(this.responseText);
            loadReviews();
        } else {
            alert('Error: ' + this.status);
        }
    }
    xhr.send('tour_id=' + tourId + '&rating=' + rating + '&comment=' + comment);
}
