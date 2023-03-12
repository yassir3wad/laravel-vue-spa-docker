<template>
  <b-card>
    <!-- form -->
    <validation-observer ref="form">
      <b-form ref="formRef" @submit.prevent="submit">
        <b-row>
          <!-- bio -->
          <b-col cols="12">
            <b-form-group
                label="Bio"
                label-for="bio-area">
              <validation-provider
                  #default="{ errors }"
                  name="bio"
                  vid="bio">
                <b-form-textarea
                    id="bio-area"
                    v-model="form.bio"
                    rows="4"
                    placeholder="Your bio data here..."
                    :state="errors.length > 0 ? false : null"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <!--/ bio -->

          <!-- birth date -->
          <b-col md="6">
            <b-form-group
                label-for="example-datepicker"
                label="Birth date"
            >
              <validation-provider
                  #default="{ errors }"
                  name="dob"
                  vid="dob">
                <flat-pickr
                    v-model="form.dob"
                    class="form-control"
                    :config="{maxDate: 'today'}"
                    name="date"
                    placeholder="Birth date"
                    :state="errors.length > 0 ? false : null"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <!--/ birth date -->


          <!-- mobile -->
          <b-col md="6">
            <b-form-group
                label-for="mobile"
                label="Mobile">
              <validation-provider
                  #default="{ errors }"
                  name="mobile"
                  vid="mobile">
                <VuePhoneNumberInput :fetch-country="false"
                                     :no-use-browser-locale="true"  @update="onMobileUpdate" v-model="form.tmp_mobile"
                                     :error="errors.length > 0 ? true : null"/>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <!-- mobile -->

          <b-col cols="12">
            <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                type="submit"
                class="mt-1 mr-1"
                :disabled="processing">

              <b-spinner v-if="processing" small type="grow"/>

              Save changes
            </b-button>
          </b-col>
        </b-row>
      </b-form>
    </validation-observer>
  </b-card>
</template>

<script>
import flatPickr from 'vue-flatpickr-component'
import Ripple from 'vue-ripple-directive'
import Cleave from 'vue-cleave-component'
// eslint-disable-next-line import/no-extraneous-dependencies
import 'cleave.js/dist/addons/cleave-phone.ps'
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';

// TODO: API for counties and enable dropdown
export default {
  components: {
    flatPickr,
    Cleave,
    VuePhoneNumberInput
  },
  directives: {
    Ripple,
  },
  props: {
    informationData: {
      type: Object,
    },
  },
  data() {
    return {
      form: {
        bio: this.informationData.bio,
        dob: this.informationData.dob,
        tmp_mobile: this.informationData.mobile,
        mobile: this.informationData.mobile,
      },
      processing: false
    }
  },
  methods: {
    submit() {
      this.processing = true;
      this.$refs.form.validate().then((success) => {
        if (success) {
          this.$http.put('/api/user/profile-information', Object.assign(this.form, {type: 'information'})).then(({data}) => {
            this.softToast('success', this.$t('Success'), data.message, 'CheckIcon')
            this.$store.dispatch('auth/user');
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
    onMobileUpdate(e) {
      this.form.mobile = e.formattedNumber;
    }
  },
}
</script>

<style lang="scss">
@import '@core/scss/vue/libs/vue-flatpicker.scss';
</style>
