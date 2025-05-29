document.querySelectorAll('.password-field').forEach(field => {
    const input = field.querySelector('input');
    const eyes = field.querySelectorAll('.toggle-eye');

    eyes.forEach(eye => {
        eye.addEventListener('click', () => {
            const isHidden = input.type === 'password';

            // Toggle input type
            input.type = isHidden ? 'text' : 'password';

            // Toggle which icon is shown
            eyes.forEach(img => {
                img.classList.toggle('hidden');
            });
        });
    });
});

function showToast(message) {
    const toastBox = document.getElementById('toastBox');
    const toast = document.createElement('div');
    toast.classList.add('toast', 'invalid');
    toast.innerHTML = `
        <i class='bx bxs-error'></i>
        <p style="flex: 1; margin-right: 10px;">${message}</p>
    `;
    toastBox.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 5000);
}