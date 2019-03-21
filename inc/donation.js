/* eslint-disable no-undef */
// eslint-disable-next-line no-new
new Vue({
  el: '#app',
  data: {
    errors: [],
    version: 'v10',
    merchant_id: donation_vars.merchant_id,
    agreement_id: donation_vars.agreement_id,
    order_id: '0001',
    amount: '',
    currency: 'DKK',
    continueurl: 'https://testdomain.dk/ordregennemfoert',
    cancelurl: 'https://testdomain.dk/ordregennemfoert',
    callbackurl: 'https://testdomain.dk/ordregennemfoert',
    invoice_address: {
      name: '',
      street: '',
      zip_code: '',
      city: '',
      email: '',
    },
    subscibe_to_newsletter: '',
    terms_accepted: false,
    gdpr_accepted: false,
  },
  computed: {
    flattened_values() {
      const params1 = {
        agreement_id: this.agreement_id,
        amount: this.amount * 10,
        callbackurl: this.callbackurl,
        cancelurl: this.cancelurl,
        continueurl: this.continueurl,
        currency: this.currency,
        gdpr_accepted: this.gdpr_accepted,
      };
      const params2 = {
        city: this.invoice_address.city,
        email: this.invoice_address.email,
        name: this.invoice_address.name,
        street: this.invoice_address.street,
        zip_code: this.invoice_address.zip_code,
      };
      const params3 = {
        merchant_id: this.merchant_id,
        order_id: this.order_id,
        subscibe_to_newsletter: this.subscibe_to_newsletter,
        terms_accepted: this.terms_accepted,
        version: this.version,
      };
      return Object.values(params1).join(' ') + Object.values(params2).join(' ') + Object.values(params3).join(' ');
    },

    checksum() {
      return CryptoJS.HmacSHA256(this.flattened_values, 'Secret Passphrase');
    },


  },

  methods: {
    checkForm(e) {
      this.errors = [];

      if (!this.invoice_address.name) {
        this.errors.push('Udfyld navn.');
      }
      if (!this.invoice_address.street) {
        this.errors.push('Udfyld adresse.');
      }
      if (!this.invoice_address.zip_code) {
        this.errors.push('Udfyld postnummer.');
      }
      if (!this.invoice_address.city) {
        this.errors.push('Udfyld by.');
      }
      if (!this.invoice_address.email) {
        this.errors.push('Udfyld email.');
      } else if (!this.validEmail(this.invoice_address.email)) {
        this.errors.push('Indtast venligst en gyldig email adresse  .');
      }

      if (this.terms_accepted == false) {
        this.errors.push('Du skal acceptere handelsbetingelserne.');
      }
      if (this.gdpr_accepted == false) {
        this.errors.push('Du skal acceptere vores privatlivspolitik.');
      }

      if (!this.errors.length) {
        return true;
      }


      e.preventDefault();
    },
    validEmail(email) {
      const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    },
  },
});
