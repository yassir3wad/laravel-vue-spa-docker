<template>
  <b-card>
    <!-- form -->
    <validation-observer ref="form">
      <b-form ref="formRef" @submit.prevent="submit">
        <b-row>
          <!-- old password -->
          <b-col md="6">
            <b-form-group
                label="Old Password"
                label-for="account-old-password"
            >
              <validation-provider
                  #default="{ errors }"
                  name="curreny password"
                  vid="current_password"
                  rules="required">
                <b-input-group class="input-group-merge" :class="errors.length > 0 ? 'is-invalid':null">
                  <b-form-input
                      id="account-old-password"
                      v-model="form.current_password"
                      name="old-password"
                      :type="passwordFieldTypeOld"
                      placeholder="Old Password"
                      :state="errors.length > 0 ? false:null"
                  />
                  <b-input-group-append is-text>
                    <feather-icon
                        :icon="passwordToggleIconOld"
                        class="cursor-pointer"
                        @click="togglePasswordOld"
                    />
                  </b-input-group-append>
                </b-input-group>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <!--/ old password -->
        </b-row>
        <b-row>
          <!-- new password -->
          <b-col md="6">
            <b-form-group label-for="account-new-password" label="New Password">
              <validation-provider
                  #default="{ errors }"
                  name="new password" vid="password" rules="required">
                <b-input-group class="input-group-merge" :class="errors.length > 0 ? 'is-invalid':null">
                  <b-form-input
                      id="account-new-password"
                      v-model="form.password"
                      :type="passwordFieldTypeNew"
                      :state="errors.length > 0 ? false:null"
                      name="new-password"
                      placeholder="New Password"
                  />
                  <b-input-group-append is-text>
                    <feather-icon
                        :icon="passwordToggleIconNew"
                        class="cursor-pointer"
                        @click="togglePasswordNew"
                    />
                  </b-input-group-append>
                </b-input-group>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <!--/ new password -->

          <!-- retype password -->
          <b-col md="6">
            <b-form-group
                label-for="account-retype-new-password"
                label="Retype New Password"
            >
              <validation-provider
                  #default="{ errors }"
                  name="new password" vid="password_confirmation" rules="required|confirmed:password">
                <b-input-group class="input-group-merge" :class="errors.length > 0 ? 'is-invalid':null">
                  <b-form-input
                      id="account-retype-new-password"
                      v-model="form.password_confirmation"
                      :type="passwordFieldTypeRetype"
                      :state="errors.length > 0 ? false:null"
                      name="retype-password"
                      placeholder="New Password"
                  />
                  <b-input-group-append is-text>
                    <feather-icon
                        :icon="passwordToggleIconRetype"
                        class="cursor-pointer"
                        @click="togglePasswordRetype"
                    />
                  </b-input-group-append>
                </b-input-group>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <!--/ retype password -->

          <!-- buttons -->
          <b-col cols="12">
            <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mt-1 mr-1"
                type="submit"
                :disabled="processing">
              <b-spinner v-if="processing" small type="grow"/>

              Save changes
            </b-button>
            <b-button
                v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                type="reset"
                variant="outline-secondary"
                class="mt-1"
                @click="reset">
              Reset
            </b-button>
          </b-col>
          <!--/ buttons -->
        </b-row>
      </b-form>
    </validation-observer>
  </b-card>
</template>

<script>
import Ripple from 'vue-ripple-directive'

export default {
  directives: {
    Ripple,
  },
  data() {
    return {
      form: {
        current_password: null,
        password: null,
        password_confirmation: null,
      },
      processing: false,
      passwordFieldTypeOld: 'password',
      passwordFieldTypeNew: 'password',
      passwordFieldTypeRetype: 'password',
    }
  },
  computed: {
    passwordToggleIconOld() {
      return this.passwordFieldTypeOld === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    passwordToggleIconNew() {
      return this.passwordFieldTypeNew === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    passwordToggleIconRetype() {
      return this.passwordFieldTypeRetype === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
  },
  methods: {
    togglePasswordOld() {
      this.passwordFieldTypeOld = this.passwordFieldTypeOld === 'password' ? 'text' : 'password'
    },
    togglePasswordNew() {
      this.passwordFieldTypeNew = this.passwordFieldTypeNew === 'password' ? 'text' : 'password'
    },
    togglePasswordRetype() {
      this.passwordFieldTypeRetype = this.passwordFieldTypeRetype === 'password' ? 'text' : 'password'
    },
    submit() {
      this.processing = true;
      this.$refs.form.validate().then((success) => {
        if (success) {
          this.$http.put('/api/user/password', this.form).then(({data}) => {
            this.$toast.success(data.message);
            this.reset();
          })
              .catch(error => this.handleResponseError(error, this.$refs.form))
              .finally(() => {
                this.processing = false;
              });
        } else {
          this.processing = false
        }
      }).catch(() => {
        this.processing = false
      })

    },
    reset() {
      this.$refs.formRef.reset();
      this.$refs.form.reset();
    }
  },
}
</script>
