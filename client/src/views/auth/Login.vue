<template>
  <div class="auth-wrapper auth-v2">
    <b-row class="auth-inner m-0">

      <!-- Brand logo-->
      <b-link class="brand-logo">
        <vuexy-logo/>
        <h2 class="brand-text text-primary ml-1">
          Vuexy
        </h2>
      </b-link>
      <!-- /Brand logo-->

      <!-- Left Text-->
      <b-col
          lg="8"
          class="d-none d-lg-flex align-items-center p-5"
      >
        <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
          <b-img
              fluid
              :src="imgUrl"
              alt="Login V2"
          />
        </div>
      </b-col>
      <!-- /Left Text-->

      <!-- Login-->
      <b-col
          lg="4"
          class="d-flex align-items-center auth-bg px-2 p-lg-5"
      >
        <b-col
            sm="8"
            md="6"
            lg="12"
            class="px-xl-2 mx-auto"
        >
          <b-card-title
              title-tag="h2"
              class="font-weight-bold mb-1"
          >
            Welcome to Vuexy! 👋
          </b-card-title>
          <b-card-text class="mb-2">
            Please sign-in to your account and start the adventure
          </b-card-text>

          <!-- form -->
          <validation-observer ref="form">
            <b-form
                class="auth-login-form mt-2"
                @submit.prevent
            >
              <!-- email -->
              <b-form-group
                  label="Email"
                  label-for="login-email"
              >
                <validation-provider
                    #default="{ errors }"
                    name="email"
                    rules="required|email"
                >
                  <b-form-input
                      id="login-email"
                      v-model="email"
                      :state="errors.length > 0 ? false:null"
                      name="login-email"
                      placeholder="john@example.com"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>

              <!-- forgot password -->
              <b-form-group>
                <div class="d-flex justify-content-between">
                  <label for="login-password">Password</label>
                  <b-link :to="{name:'forget_password'}">
                    <small>Forgot Password?</small>
                  </b-link>
                </div>
                <validation-provider
                    #default="{ errors }"
                    name="Password"
                    rules="required"
                >
                  <b-input-group
                      class="input-group-merge"
                      :class="errors.length > 0 ? 'is-invalid':null"
                  >
                    <b-form-input
                        id="login-password"
                        v-model="password"
                        :state="errors.length > 0 ? false:null"
                        class="form-control-merge"
                        :type="passwordFieldType"
                        name="login-password"
                        placeholder="············"
                    />
                    <b-input-group-append is-text>
                      <feather-icon
                          class="cursor-pointer"
                          :icon="passwordToggleIcon"
                          @click="togglePasswordVisibility"
                      />
                    </b-input-group-append>
                  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>

              <!-- checkbox -->
              <b-form-group>
                <b-form-checkbox
                    id="remember-me"
                    v-model="status"
                    name="checkbox-1"
                >
                  Remember Me
                </b-form-checkbox>
              </b-form-group>

              <!-- submit buttons -->
              <b-button
                  :disabled="processing"
                  type="submit"
                  variant="primary"
                  block
                  @click="submit"
              >
                <b-spinner v-if="processing" small type="grow"/>
                Sign in
              </b-button>
            </b-form>
          </validation-observer>
        </b-col>
      </b-col>
      <!-- /Login-->
    </b-row>
  </div>
</template>

<script>
/* eslint-disable global-require */
import VuexyLogo from '@core/layouts/components/Logo.vue'
import {togglePasswordVisibility} from '@core/mixins/ui/forms'

export default {
  components: {
    VuexyLogo,
  },
  mixins: [togglePasswordVisibility],
  data() {
    return {
      status: '',
      password: '',
      email: '',
      processing: false,
      sideImg: require('@/assets/images/pages/login-v2.svg'),
    }
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    imgUrl() {
      if (this.$store.state.appConfig.layout.skin === 'dark') {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.sideImg = require('@/assets/images/pages/login-v2-dark.svg')
        return this.sideImg
      }
      return this.sideImg
    },
  },
  methods: {
    submit() {
      this.processing = true;

      this.$refs.form.validate().then((success) => {
        if (success) {
          this.$store.dispatch('auth/login', {
            email: this.email,
            password: this.password,
          }).then(() => {
            this.$toast.success(this.$t("Successfully logged in"));
            this.$router.push({name: 'home'});
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
