<template>
  <validation-observer ref="form">
    <b-form ref="formRef" @submit.prevent="submit">
      <b-row>
        <b-col cols="12" xl="9">
          <b-card>
            <b-row>
              <b-col cols="6">
                <b-form-group
                    :label="$t('Name')"
                    label-for="name"
                >
                  <validation-provider
                      #default="{ errors }"
                      name="name"
                      rules="required"
                  >
                    <b-form-input
                        id="name"
                        v-model="form.name"
                        :state="errors.length > 0 ? false : null"
                        :placeholder="$t('Name')"
                    />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>


              <b-col cols="6">
                <b-form-group
                    :label="$t('Email')"
                    label-for="email"
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
                          id="email"
                          v-model="form.email"
                          type="email"
                          :state="errors.length > 0 ? false : null"
                          :placeholder="$t('Email')"
                      />
                    </b-input-group>
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>


              <b-col cols="6">
                <b-form-group
                    :label="$t('Username')"
                    label-for="username"
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
                          id="username"
                          :placeholder="$t('Username')"
                          v-model="form.username"
                          name="username"
                          :state="errors.length > 0 ? false : null"
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
                    :label="$t('Mobile')">
                  <validation-provider
                      #default="{ errors }"
                      name="mobile"
                      rules="required"
                      vid="mobile">

                    <Mobile v-model="form.mobile" :errors="errors"/>

                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>

              <b-col cols="6">
                <b-form-group
                    :label="$t('Role')"
                    label-for="role_id">
                  <validation-provider
                      #default="{ errors }"
                      name="required"
                      rules=""
                      vid="role_id">
                    <v-select
                        id="role_id"
                        v-model="form.role_id"
                        :state="errors.length > 0 ? false : null"
                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                        :options="roleOptions"
                        class="w-100"
                    />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>

              <b-col v-if="!id" cols="6">
                <b-form-group
                    :label="$t('Password')"
                    label-for="password"
                >
                  <validation-provider
                      #default="{ errors }"
                      name="password" vid="password" rules="required">
                    <b-input-group class="input-group-merge">
                      <b-input-group-prepend is-text>
                        <feather-icon icon="LockIcon"/>
                      </b-input-group-prepend>
                      <b-form-input
                          id="password"
                          type="password"
                          v-model="form.password"
                          :state="errors.length > 0 ? false : null"
                          :placeholder="$t('Password')"
                      />
                    </b-input-group>
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>

              <b-col v-if="!id" cols="6">
                <b-form-group
                    :label="$t('Password Confirmation')"
                    label-for="password_confirmation"
                >
                  <validation-provider
                      #default="{ errors }"
                      name="new password" vid="password_confirmation" rules="required|confirmed:password">
                    <b-input-group class="input-group-merge">
                      <b-input-group-prepend is-text>
                        <feather-icon icon="LockIcon"/>
                      </b-input-group-prepend>
                      <b-form-input
                          id="password_confirmation"
                          type="password"
                          v-model="form.password_confirmation"
                          :state="errors.length > 0 ? false:null"
                          :placeholder="$t('Password Confirmation')"
                      />
                    </b-input-group>
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
            </b-row>
          </b-card>
        </b-col>

        <b-col cols="12" xl="3" class="mt-md-0 mt-2">
          <b-card>
            <div class="mb-2">
              <b-form-group
                  :label="$t('Image')"
                  label-align="center"
                  label-for="image"
              >
                <validation-provider
                    #default="{ errors }"
                    name="image"
                    rules="required"
                    vid="image">
                  <file-uploader id="image" layout="circle" :errors="errors" v-model="form.image"></file-uploader>
                  <small class="text-danger text-center d-block" style="margin-top: -8px"
                         v-if="errors && errors.length">{{
                      errors[0]
                    }}</small>
                </validation-provider>
              </b-form-group>
            </div>

            <div class="mb-2">
              <validation-provider
                  #default="{ errors }"
                  name="status"
                  rules="required"
                  vid="status">
                <b-form-checkbox
                    v-model="form.status"
                    id="status"
                    name="status"
                    switch
                    value="active"
                    unchecked-value="inactive">
                  {{ $t('Is Active') }}
                </b-form-checkbox>
              </validation-provider>
            </div>

            <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mb-75"
                block
                type="submit">
              {{ $t('Save') }}
            </b-button>
          </b-card>
        </b-col>
      </b-row>
    </b-form>
  </validation-observer>
</template>

<script>

import Ripple from 'vue-ripple-directive'
import FileUploader from "@/views/components/FileUploader";
import {SET_BREADCRUMB} from "@/store/breadcrumbs.store";
import Mobile from "@/views/components/Mobile";

export default {
  components: {
    FileUploader,
    Mobile
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      form: {
        name: null,
        email: null,
        mobile: null,
        username: null,
        password: null,
        password_confirmation: null,
        role_id: null,
        status: 'active',
        image: null,
      },
      id: this.$route.params.id,
      processing: false,
    }
  },
  methods: {
    submit() {
      this.processing = true;
      console.log('form', this.form);

      this.$refs.form.validate().then((success) => {
        if (success) {
          const formData = new FormData();

          ['name', 'username', 'email', 'mobile', 'role_id', 'status'].forEach(field => {
            formData.append(field, this.form[field]);
          });

          if (this.form.image && this.form.image instanceof File) {
            formData.append('image', this.form.image);
          }


          if (this.id) {
            formData.append('_method', 'PUT');
          } else {
            formData.append('password', this.form.password);
            formData.append('password_confirmation', this.form.password_confirmation);
          }

          this.$http.post('/api/users' + (this.id ? `/${this.id}` : ''), formData).then(({data}) => {
            this.softToast('success', this.$t('Success'), data.message, 'CheckIcon')
            this.$router.push({name: 'users.index'});
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
  created() {
    if (this.id) {
      this.$http.get(`/api/users/${this.id}`).then(response => {
        const data = response.data.data;
        this.$nextTick(() => {
          this.form = {
            name: data.name,
            email: data.email,
            mobile: data.mobile,
            username: data.username,
            role_id: data.role_id,
            status: data.status,
            image: data.image,
          };
        });
      }).catch(this.handleResponseError);
    }
  },
  mounted() {
    this.$store.dispatch(SET_BREADCRUMB, [
      {text: this.$t('modules.users.users'), to: {name: 'users.index'}},
      {text: this.id ? this.$t('modules.users.edit') : this.$t('modules.users.create'), active: true}
    ]);
  },
  setup() {
    const roleOptions = [
      {label: 'Admin', value: 'admin'},
      {label: 'Author', value: 'author'},
      {label: 'Editor', value: 'editor'},
      {label: 'Maintainer', value: 'maintainer'},
      {label: 'Subscriber', value: 'subscriber'},
    ];

    return {
      roleOptions,
    };
  },
}
</script>