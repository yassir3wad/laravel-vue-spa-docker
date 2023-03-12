<template>
  <div class="auth-wrapper auth-v1 px-2">
    <div class="auth-inner py-2">

      <!-- Forgot Password v1 -->
      <b-card class="mb-0">
        <b-link class="brand-logo">
          <!-- logo -->
          <vuexy-logo/>

          <h2 class="brand-text text-primary ml-1">
            Vuexy
          </h2>
        </b-link>

        <b-card-title class="mb-1">
          Forgot Password? 🔒
        </b-card-title>
        <b-card-text class="mb-2">
          Enter your email and we'll send you instructions to reset your password
        </b-card-text>

        <!-- form -->
        <validation-observer ref="form">
          <b-form
              class="auth-forgot-password-form mt-2"
              @submit.prevent="submit"
          >
            <!-- email -->
            <b-form-group
                label="Email"
                label-for="forgot-password-email"
            >
              <validation-provider
                  #default="{ errors }"
                  name="email"
                  rules="required|email"
              >
                <b-form-input
                    id="forgot-password-email"
                    v-model="email"
                    :state="errors.length > 0 ? false:null"
                    name="forgot-password-email"
                    placeholder="john@example.com"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>

            <!-- submit button -->
            <b-button
                :disabled="processing"
                variant="primary"
                block
                type="submit"
            >
              <b-spinner v-if="processing" small type="grow"/>

              Send reset link
            </b-button>
          </b-form>
        </validation-observer>

        <b-card-text class="text-center mt-2">
          <b-link :to="{name:'login'}">
            <feather-icon icon="ChevronLeftIcon"/>
            Back to login
          </b-link>
        </b-card-text>

      </b-card>
      <!-- /Forgot Password v1 -->
    </div>
  </div>
</template>

<script>
import VuexyLogo from '@core/layouts/components/Logo.vue'

export default {
  components: {
    VuexyLogo,
  },
  data() {
    return {
      email: null,
      processing: false,
    }
  },
  methods: {
    submit() {
      this.processing = true

      this.$refs.form.validate().then((success) => {
        if (success) {
          this.$http.post('/api/forgot-password', {
            email: this.email
          }).then(({data}) => {
            this.$toast.success(data.message);
          })
              .catch(error => this.handleResponseError(error, this.$refs.form))
              .finally(() => {
                this.processing = false
              });
        } else {
          this.processing = false
        }
      }).catch(() => {
        this.processing = false
      })
    },
  },
  mounted() {
      document.title = this.$t('Forget Password');
  },
}
</script>

<style lang="scss">
@import '@core/scss/vue/pages/page-auth.scss';
</style>
