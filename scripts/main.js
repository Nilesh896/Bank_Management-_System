document.querySelector('form').addEventListener('submit', (e) => {
    const amount = document.querySelector('input[name="amount"]').value;
    if (amount <= 0) {
        alert('Please enter a valid amount.');
        e.preventDefault();
    }
});
