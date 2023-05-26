import Vue from 'vue';
import { StripePlugin } from '@vue-stripe/vue-stripe';

import { STRIPE_PK } from "./config";

const options = {
  pk: STRIPE_PK,
  testMode: true, // Boolean. To override the insecure host warning
};

Vue.use(StripePlugin, options);