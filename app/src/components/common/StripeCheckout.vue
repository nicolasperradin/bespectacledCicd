<template>
    <div>
        <stripe-checkout ref="checkoutRef" mode="payment" :pk="publishableKey" :line-items="lineItems"
            :success-url="successURL" :cancel-url="cancelURL" @loading="v => loading = v" />
        <button @click="submit">Pay now!</button>
    </div>
</template>


  
<script>
import { StripeCheckout } from '@vue-stripe/vue-stripe';
import { STRIPE_PK } from "../../utils/config";

export default {
    components: {
        StripeCheckout,
    },
    data() {
        this.publishableKey = STRIPE_PK;
        return {
            publishableKey: STRIPE_PK,
            loading: false,
            lineItems: [
                {
                    price: 'price_1N5m0aBXXTfYpnS86RsjWQE9', // The id of the one-time price you created in your Stripe dashboard
                    quantity: 1,
                },
            ],
            successURL: window.location.origin + '?success=true',
            cancelURL: window.location.origin + '?canceled=true',
        };
    },
    methods: {
        submit() {
            // You will be redirected to Stripe's secure checkout page
            this.$refs.checkoutRef.redirectToCheckout();

            // You can also pass a callback that will be called after the redirect

            // this.$refs.checkoutRef.redirectToCheckout((error) => {
            //     // handle possible errors
            //     if (error) {
            //         console.log(error);
            //     }
        },
    },
};
</script>