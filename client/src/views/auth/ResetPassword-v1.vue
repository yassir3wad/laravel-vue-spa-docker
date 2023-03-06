<template>
  <div class="auth-wrapper auth-v1 px-2">
    <div class="auth-inner py-2">
      <!-- Reset Password v1 -->
      <b-card class="mb-0">

        <!-- logo -->
        <b-link class="brand-logo">
          <vuexy-logo/>

          <h2 class="brand-text text-primary ml-1">
            Vuexy
          </h2>
        </b-link>

        <b-card-title class="mb-1">
          Reset Password 🔒
        </b-card-title>
        <b-card-text class="mb-2">
          Your new password must be different from previously used passwords
        </b-card-text>

        <!-- form -->
        <validation-observer ref="form">
          <b-form
              method="POST"
              class="auth-reset-password-form mt-2"
              @submit.prevent="submit"
          >

            <!-- password -->
            <b-form-group
                label="New Password"
                label-for="reset-password-new"
            >
              <validation-provider
                  #default="{ errors }"
                  name="password"
                  vid="password"
                  rules="required"
              >
                <b-input-group
                    class="input-group-merge"
                    :class="errors.length > 0 ? 'is-invalid':null"
                >
                  <b-form-input
                      id="reset-password-new"
                      v-model="form.password"
                      :type="password1FieldType"
                      :state="errors.length > 0 ? false:null"
                      class="form-control-merge"
                      name="reset-password-new"
                      placeholder="············"
                  />
                  <b-input-group-append is-text>
                    <feather-icon
                        class="cursor-pointer"
                        :icon="password1ToggleIcon"
                        @click="togglePassword1Visibility"
                    />
                  </b-input-group-append>
                </b-input-group>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>

            <!-- confirm password -->
            <b-form-group
                label-for="reset-password-confirm"
                label="Confirm Password"
            >
              <validation-provider
                  #default="{ errors }"
                  name="Confirm Password"
                  vid="password_confirmation"
                  rules="required|confirmed:password"
              >
                <b-input-group
                    class="input-group-merge"
                    :class="errors.length > 0 ? 'is-invalid':null"
                >
                  <b-form-input
                      id="reset-password-confirm"
                      v-model="form.password_confirmation"
                      :type="password2FieldType"
                      class="form-control-merge"
                      :state="errors.length > 0 ? false:null"
                      name="reset-password-confirm"
                      placeholder="············"
                  />
                  <b-input-group-append is-text>
                    <feather-icon
                        class="cursor-pointer"
                        :icon="password2ToggleIcon"
                        @click="togglePassword2Visibility"
                    />
                  </b-input-group-append>
                </b-input-group>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>

            <!-- submit button -->
            <b-button
                :disabled="processing"
                block
                type="submit"
                variant="primary"
            >
              <b-spinner v-if="processing" small type="grow"/>

              Set New Password
            </b-button>
          </b-form>
        </validation-observer>

        <p class="text-center mt-2">
          <b-link :to="{name:'auth-login-v1'}">
            <feather-icon icon="ChevronLeftIcon"/>
            Back to login
          </b-link>
        </p>

      </b-card>
      <!-- /Reset Password v1 -->
    </div>
  </div>

</template>

<script>
import VuexyLogo from '@core/layouts/components/Logo.vue'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: {
    VuexyLogo,
  },
  data() {
    return {
      form: {
        token: this.$route.query.token,
        email: decodeURIComponent(this.$route.query.email),
        password: null,
        password_confirmation: null,
      },
      processing: false,

      // Toggle Password
      password1FieldType: 'password',
      password2FieldType: 'password',
    }
  },
  computed: {
    password1ToggleIcon() {
      return this.password1FieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    password2ToggleIcon() {
      return this.password2FieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
  },
  methods: {
    togglePassword1Visibility() {
      this.password1FieldType = this.password1FieldType === 'password' ? 'text' : 'password'
    },
    togglePassword2Visibility() {
      this.password2FieldType = this.password2FieldType === 'password' ? 'text' : 'password'
    },
    submit() {
      this.processing = true

      this.$refs.form.validate().then((success) => {
        if (success) {
          this.$http.post('/reset-password', this.form).then(({data}) => {
            this.$toast.success(data.message);
            this.$router.push({name: 'login'});
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
}
</script>

<style lang="scss">
@import '@core/scss/vue/pages/page-auth.scss';
</style>
