<template>
  <b-modal
      ref="modal"
      :title="$t('Reset Password')"
      centered
      button-size="sm"
      :ok-title="$t('Save')"
      :cancel-title="$t('Cancel')"
      cancel-variant="outline-secondary"
      @hidden="handleCancelModal"
      @ok="handleSubmitModal">

    <template #modal-ok>
      <b-spinner v-if="processing" small type="grow"/>
      {{ $t('Save') }}
    </template>

    <validation-observer ref="form">
      <b-form ref="formRef" @submit.prevent="handleSubmitModal">
        <b-form-group label-for="password" :label="$t('New Password')">
          <validation-provider
              #default="{ errors }"
              name="new password" vid="password" rules="required">
            <b-input-group class="input-group-merge" :class="errors.length > 0 ? 'is-invalid':null">
              <b-form-input
                  id="password"
                  v-model="form.password"
                  :type="passwordFieldTypeNew"
                  :state="errors.length > 0 ? false:null"
                  name="new-password"
                  :placeholder="$t('New Password')"
              />
              <b-input-group-append is-text>
                <feather-icon
                    :icon="passwordFieldTypeNew === 'password' ? 'EyeIcon' : 'EyeOffIcon'"
                    class="cursor-pointer"
                    @click="() => passwordFieldTypeNew = passwordFieldTypeNew === 'password' ? 'text' : 'password'"
                />
              </b-input-group-append>
            </b-input-group>
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group
            label-for="password_confirmation"
            :label="$t('Retype New Password')"
        >
          <validation-provider
              #default="{ errors }"
              name="new password" vid="password_confirmation" rules="required|confirmed:password">
            <b-input-group class="input-group-merge" :class="errors.length > 0 ? 'is-invalid':null">
              <b-form-input
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  :type="passwordFieldTypeRetype"
                  :state="errors.length > 0 ? false:null"
                  name="retype-password"
                  :placeholder="$t('Retype New Password')"
              />
              <b-input-group-append is-text>
                <feather-icon
                    :icon="passwordFieldTypeRetype === 'password' ? 'EyeIcon' : 'EyeOffIcon'"
                    class="cursor-pointer"
                    @click="() => passwordFieldTypeRetype = passwordFieldTypeRetype === 'password' ? 'text' : 'password'"
                />
              </b-input-group-append>
            </b-input-group>
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>
      </b-form>
    </validation-observer>
  </b-modal>
</template>

<script>
export default {
  name: 'reset-password-modal',
  props: {
    user: {
      type: Object
    },
    value: {
      default() {
        return false;
      }
    },
  },
  data() {
    return {
      form: {
        password: null,
        password_confirmation: null,
      },
      processing: false,
      internalValue: false,
      passwordFieldTypeNew: 'password',
      passwordFieldTypeRetype: 'password',
    }
  },
  methods: {
    handleCancelModal() {
      Object.assign(this.$data, this.$options.data());
      this.$emit('update:user', null);
    },
    handleSubmitModal(e) {
      e.preventDefault();
      this.processing = true;

      this.$refs.form.validate().then((success) => {
        if (success) {
          this.$http.patch(`/api/users/${this.user.id}/reset-password`, this.form).then(({data}) => {
            this.toast('success', 'Success', data.message);
            this.$refs.modal.hide();
          }).catch(error => this.handleResponseError(error, this.$refs.form))
              .finally(() => this.processing = false);
        } else {
          this.processing = false
        }
      }).catch(() => {
        this.processing = false
      })
    }
  },
  watch: {
    value(val) {
      this.internalValue = val;
    },
    internalValue: {
      handler(val) {
        if (val) {
          this.$refs.modal.show();
        } else {
          this.$refs.modal.hide();
        }

        this.$emit('input', val);
      }
    },
  }
}
</script>