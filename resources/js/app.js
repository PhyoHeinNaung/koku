import './bootstrap';

import collapse from '@alpinejs/collapse';
import sort from '@alpinejs/sort';

document.addEventListener('alpine:init', () => {
    Alpine.plugin(collapse);
    Alpine.plugin(sort);
});

document.addEventListener('livewire:init', () => {
    let activeRequests = 0;

    Livewire.hook('request', ({ succeed, fail }) => {
        activeRequests++;
        window.dispatchEvent(new CustomEvent('loading-bar-start'));

        const stop = () => {
            activeRequests = Math.max(0, activeRequests - 1);
            if (activeRequests === 0) {
                window.dispatchEvent(new CustomEvent('loading-bar-stop'));
            }
        };

        succeed(stop);
        fail(stop);
    });
});

window.initCheckoutStripe = function (clientSecret) {
    const stripeKey = document.querySelector('meta[name="stripe-key"]').content;
    const stripe = Stripe(stripeKey);
    const elements = stripe.elements({ clientSecret });
    const paymentElement = elements.create('payment');
    paymentElement.mount('#payment-element');

    const button = document.getElementById('submit-payment');
    const errorBox = document.getElementById('payment-error');

    button.addEventListener('click', async () => {
        button.disabled = true;
        button.textContent = 'Processing...';
        errorBox.classList.add('hidden');

        const { error, paymentIntent } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: window.location.origin + '/checkout/return',
            },
            redirect: 'if_required',
        });

        if (error) {
            errorBox.textContent = error.message;
            errorBox.classList.remove('hidden');
            button.disabled = false;
            button.textContent = 'Try Again';
            return;
        }

        if (paymentIntent && paymentIntent.status === 'succeeded') {
            window.location.href = '/checkout/success?payment_intent=' + paymentIntent.id;
        }
    });
};
