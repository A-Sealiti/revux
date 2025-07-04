console.log("loaded");

document.addEventListener('DOMContentLoaded', function () {
    const likeButtons = document.querySelectorAll('.like-button');

    likeButtons.forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const scooter_id = button.closest('form').querySelector('input[name="scooter_id"]').value;

            fetch('like_product.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'scooter_id=' + scooter_id,
            })
                .then(response => response.text())
                .then(data => {
                    const icon = button.querySelector('i');
                    const likeCount = button.querySelector('.like-count');

                    if (data === 'added') {
                        // Toon een rood hartje
                        icon.classList.add('bi-suit-heart-fill', 'text-danger');
                        icon.classList.remove('bi-suit-heart');

                        if (likeCount) {
                            likeCount.style.display = 'inline';
                            let count = parseInt(likeCount.innerText.replace(/[^\d]/g, '')) + 1;
                            likeCount.innerText = `(${count})`;
                        }
                    } else if (data === 'removed') {
                        // Reset naar een zwart, leeg hart
                        icon.classList.add('bi-suit-heart');
                        icon.classList.remove('bi-suit-heart-fill', 'text-danger');

                        if (likeCount) {
                            let count = parseInt(likeCount.innerText.replace(/[^\d]/g, '')) - 1;
                            likeCount.innerText = `(${count})`;

                            if (count <= 0) {
                                likeCount.style.display = 'none';
                            }
                        }
                    }

                    // Pagina opnieuw laden na de actie
                    location.reload();
                })
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star');
    const sterrenInput = document.getElementById('sterren');
    const likeButtons = document.querySelectorAll('.like-button');


    stars.forEach(star => {
        star.addEventListener('click', function () {
            const rating = this.getAttribute('data-value');

            // Als de huidige ster al geselecteerd is, reset alles
            if (sterrenInput.value === rating) {
                sterrenInput.value = '';
                stars.forEach(s => s.style.color = 'gray');
            } else {
                // Anders selecteer en kleur de sterren
                sterrenInput.value = rating;

                // Reset alle sterren naar grijs
                stars.forEach(s => s.style.color = 'gray');

                // Kleur de geselecteerde ster en alle voorgaande sterren goud
                this.style.color = 'gold';
                let prevSibling = this.previousElementSibling;
                while (prevSibling) {
                    prevSibling.style.color = 'gold';
                    prevSibling = prevSibling.previousElementSibling;
                }
            }
        });
    });

// Loop voor likes
    likeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const heartIcon = this.querySelector('.bi');

            if (heartIcon.classList.contains('bi-suit-heart')) {
                heartIcon.classList.remove('bi-suit-heart');
                heartIcon.classList.add('bi-suit-heart-fill');
                heartIcon.style.color = "red";
            } else {
                heartIcon.classList.remove('bi-suit-heart-fill');
                heartIcon.classList.add('bi-suit-heart');
                heartIcon.style.color = "black";
            }
        });
    });

    // wachtwoord
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });

});

