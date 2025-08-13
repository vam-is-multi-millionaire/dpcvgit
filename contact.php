<?php include_once('includes/header.php'); ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-4">
            <h1 class="display-4">Contact Us</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-7 mb-4 mb-lg-0">
            <div class="card">
                <div class="card-body text-dark">
                    <h2 class="card-title h3 mb-4">Send us a Message</h2>
                    <form action="#" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body text-dark">
                    <h2 class="card-title h3 mb-4">Contact Information</h2>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Itram 07, Surkhet, Nepal</p>
                    <p><i class="fas fa-phone me-2"></i> +977 9769955973</p>
                    <p><i class="fas fa-envelope me-2"></i> vamofficial00@gmail.com</p>
                    
                    <hr>

                    <h3 class="h4 mt-4">Business Hours</h3>
                    <p>Monday - Friday: 9:00 AM - 5:00 PM</p>
                    <p>Saturday - Sunday: Closed</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>
