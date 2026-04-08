window.addEventListener('stripe-init', async (e) => {
    const { clientSecret, paymentId } = e.detail;
    const result = await stripe.confirmCardPayment(clientSecret, {
        payment_method: {
            card: cardElement,
        }
    });
    if (result.error) {
        alert(result.error.message);
    } else {
        if (result.paymentIntent.status === 'succeeded') {
            Livewire.dispatch('confirm-payment', {
                paymentId: paymentId
            });
        }
    }
});