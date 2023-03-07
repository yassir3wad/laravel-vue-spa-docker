<template>
  <b-card>
    <validation-observer ref="form">
      <b-form>
        <b-row>
          <!-- media -->
          <b-media no-body>
            <b-media-aside>
              <b-link>
                <b-img
                    ref="previewEl"
                    rounded
                    :src="form.image"
                    height="80"
                    width="80"
                />
              </b-link>
              <!--/ avatar -->
            </b-media-aside>

            <b-media-body class="mt-75 ml-75">
              <!-- upload button -->
              <b-button
                  v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                  variant="primary"
                  size="sm"
                  class="mb-75 mr-75"
                  @click="$refs.refInputEl.$el.click()"
              >
                Upload
              </b-button>
              <b-form-file
                  ref="refInputEl"
                  v-model="profileFile"
                  accept=".jpg, .png"
                  :hidden="true"
                  plain
                  @input="inputImageRenderer"
              />
              <!--/ upload button -->
              <b-card-text>Allowed JPG or PNG. Max size of 2MB</b-card-text>
            </b-media-body>
          </b-media>
          <!--/ media -->
        </b-row>
        <b-row class="mt-2">
          <b-col sm="6">
            <b-form-group
                label="Username"
                label-for="account-username"
            >
              <validation-provider
                  #default="{ errors }"
                  name="username"
                  vid="username"
                  rules="required|alpha_num"
              >
                <b-form-input
                    v-model="form.username"
                    placeholder="Username"
                    name="username"
                    :state="errors.length > 0 ? false:null"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col sm="6">
            <b-form-group
                label="Name"
                label-for="account-name"
            >
              <validation-provider
                  #default="{ errors }"
                  name="name"
                  vid="name"
                  rules="required"
              >
                <b-form-input
                    v-model="form.name"
                    name="name"
                    placeholder="Name"
                    :state="errors.length > 0 ? false:null"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col sm="6">
            <b-form-group label="E-mail" label-for="account-e-mail">
              <validation-provider
                  #default="{ errors }"
                  name="email"
                  vid="email"
                  rules="required|email"
              >
                <b-form-input
                    v-model="form.email"
                    name="email"
                    placeholder="Email"
                    :state="errors.length > 0 ? false:null"

                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mt-2 mr-1"
                @click="submit"
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
import Ripple from 'vue-ripple-directive'
import {useInputImageRenderer} from '@core/comp-functions/forms/form-utils'
import {ref} from '@vue/composition-api'

export default {
  directives: {
    Ripple,
  },
  props: {
    generalData: {
      type: Object,
    },
  },
  data() {
    return {
      form: {
        name: this.generalData.name,
        email: this.generalData.email,
        image: this.generalData.image,
        username: this.generalData.username,
      },
      profileFile: null,
      processing: false,
    }
  },
  methods: {
    submit() {
      this.processing = true;
      this.$refs.form.validate().then((success) => {
        if (success) {
          const formData = new FormData();
          formData.append('name', this.form.name);
          formData.append('email', this.form.email);
          formData.append('username', this.form.username);
          if (this.profileFile && this.profileFile instanceof File) {
            formData.append('image', this.profileFile);
          }

          formData.append('_method', 'PUT');
          formData.append('type', 'general');

          this.$http.post('/api/user/profile-information', formData).then(({data}) => {
            this.$toast.success(data.message);
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

    }
  },
  setup() {
    const refInputEl = ref(null);
    const previewEl = ref(null);
    const cb = (rawImage) => {
      previewEl.value.setAttribute('src', rawImage);
    }

    const {inputImageRenderer} = useInputImageRenderer(refInputEl, cb)

    return {
      refInputEl,
      previewEl,
      inputImageRenderer,
    }
  },
}
</script>
