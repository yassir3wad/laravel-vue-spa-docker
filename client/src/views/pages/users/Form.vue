<template>
  <validation-observer ref="form">
    <b-form ref="formRef" @submit.prevent="submit">
      <b-row>
        <b-col
            cols="12"
            xl="9">
          <b-card>
            <b-row>
              <!-- email -->
              <b-col cols="6">
                <b-form-group
                    label="Email"
                    label-for="vi-email"
                >
                  <validation-provider
                      #default="{ errors }"
                      name="email"
                      rules="required|email"
                  >
                    <b-input-group class="input-group-merge">
                      <b-input-group-prepend is-text>
                        <feather-icon icon="MailIcon"/>
                      </b-input-group-prepend>
                      <b-form-input
                          id="vi-email"
                          v-model="form.email"
                          type="email"
                          :state="errors.length > 0 ? false:null"
                          placeholder="Email"
                      />
                    </b-input-group>
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>


              <b-col cols="6">
                <b-form-group
                    label="Username"
                    label-for="vi-username"
                >
                  <validation-provider
                      #default="{ errors }"
                      name="username"
                      vid="username"
                      rules="required|alpha_num"
                  >
                    <b-input-group class="input-group-merge">
                      <b-input-group-prepend is-text>
                        <feather-icon icon="UserCheckIcon"/>
                      </b-input-group-prepend>
                      <b-form-input
                          id="vi-username"
                          placeholder="Username"
                          v-model="form.username"
                          name="username"
                          :state="errors.length > 0 ? false:null"
                      />
                    </b-input-group>
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>


              <!-- mobile -->
              <b-col cols="6">
                <b-form-group
                    label-for="mobile"
                    label="Mobile">
                  <validation-provider
                      #default="{ errors }"
                      name="mobile"
                      vid="mobile">
                    <VuePhoneNumberInput :fetch-country="false"
                                         :no-use-browser-locale="true" @update="onMobileUpdate"
                                         v-model="form.tmp_mobile"
                                         :error="errors.length > 0 ? true : null"/>
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>

              <b-col cols="6">
                <b-form-group
                    label="Role"
                    label-for="vi-role">
                  <v-select
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="roleOptions"
                      class="w-100"
                  />
                </b-form-group>
              </b-col>

              <!-- password -->
              <b-col cols="6">
                <b-form-group
                    label="Password"
                    label-for="vi-password"
                >
                  <validation-provider
                      #default="{ errors }"
                      name="password" vid="password" rules="required">
                    <b-input-group class="input-group-merge">
                      <b-input-group-prepend is-text>
                        <feather-icon icon="LockIcon"/>
                      </b-input-group-prepend>
                      <b-form-input
                          id="vi-password"
                          type="password"
                          v-model="form.password"
                          :state="errors.length > 0 ? false:null"
                          placeholder="Password"
                      />
                    </b-input-group>
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>

              <b-col cols="6">
                <b-form-group
                    label="Password Confirmation"
                    label-for="vi-password-confirmation"
                >
                  <validation-provider
                      #default="{ errors }"
                      name="new password" vid="password_confirmation" rules="required|confirmed:password">
                    <b-input-group class="input-group-merge">
                      <b-input-group-prepend is-text>
                        <feather-icon icon="LockIcon"/>
                      </b-input-group-prepend>
                      <b-form-input
                          id="vi-password-confirmation"
                          type="password"
                          v-model="form.password_confirmation"
                          :state="errors.length > 0 ? false:null"
                          placeholder="Password Confirmation"
                      />
                    </b-input-group>
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>

              <b-col cols="6">
                <validation-provider
                    #default="{ errors }"
                    name="files"
                    rules="required"
                    vid="files">
                  <file-uploader :errors="errors" v-model="form.image" server-key="users"></file-uploader>
                </validation-provider>
              </b-col>
            </b-row>
          </b-card>
        </b-col>

        <b-col
            cols="12"
            xl="3"
            class="mt-md-0 mt-2">
          <b-card>

            <!-- Button: Send Invoice -->
            <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mb-75"
                block
            >
              Save
            </b-button>
          </b-card>
        </b-col>
      </b-row>
    </b-form>
  </validation-observer>
</template>

<script>

import Ripple from 'vue-ripple-directive'
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
import {useInputImageRenderer} from '@core/comp-functions/forms/form-utils'
import {ref} from "@vue/composition-api";
import FileUploader from "@/views/components/FileUploader";

export default {
  components: {
    FileUploader,
    VuePhoneNumberInput
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      form: {
        email: null,
        mobile: null,
        username: null,
        password: null,
        password_confirmation: null,
        image: [
          {
            url: 'http://localhost:8000/storage/users/QoMsO2tMPx9nCBD5WqgorI3PGBzcixoZtaZ3wLBt.png',
            path: 'users/QoMsO2tMPx9nCBD5WqgorI3PGBzcixoZtaZ3wLBt.png'
          }
        ],
      },
      profileFile: null,
      processing: false,
    }
  },
  methods: {
    onMobileUpdate(e) {
      this.form.mobile = e.formattedNumber;
    },
    submit() {
      this.processing = true;

      this.$refs.form.validate().then((success) => {
        if (success) {
        } else {
          this.processing = false
        }
      }).catch(() => {
        this.processing = false
      })
    }
  },
  setup() {
    const roleOptions = [
      {label: 'Admin', value: 'admin'},
      {label: 'Author', value: 'author'},
      {label: 'Editor', value: 'editor'},
      {label: 'Maintainer', value: 'maintainer'},
      {label: 'Subscriber', value: 'subscriber'},
    ];

    const refInputEl = ref(null);
    const previewEl = ref(null);
    const cb = (rawImage) => {
      previewEl.value.setAttribute('src', rawImage);
    }

    const {inputImageRenderer} = useInputImageRenderer(refInputEl, cb)

    return {
      roleOptions,
      refInputEl,
      previewEl,
      inputImageRenderer,
    };
  }
}
</script>