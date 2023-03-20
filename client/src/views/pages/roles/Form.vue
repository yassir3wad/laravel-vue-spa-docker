<template>
  <validation-observer ref="form">
    <b-form ref="formRef" @submit.prevent="submit">
      <b-row>
        <b-col class="mt-2 mb-1 d-flex flex-row-reverse">
          <b-button
              variant="outline-secondary">
            {{ $t('Cancel') }}
          </b-button>

          <b-button
              class="mr-1"
              variant="primary"
              type="submit"
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          >
            {{ $t('Save') }}
          </b-button>
        </b-col>
      </b-row>
      <b-card class="mb-1">
        <b-row>
          <b-col cols="12">
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
            </b-row>
          </b-col>
        </b-row>

        <h4 class="d-block mt-2 mb-1">{{ $t('Permissions') }}</h4>
        <b-row>
          <b-col cols="4" v-for="(item, index) in permissions" :key="index">
            <b-card
                border-variant="secondary"
                class="business-card"
            >
              <b-card-header class="pb-1">
                <b-card-title class="font-weight-bold">{{ index }}</b-card-title>
                <b-form-checkbox
                    v-model="selectedAll"
                    :value="index"
                    @change="onChangeSelect(index, permissions)">
                  {{ labelSelectUnSelect(index) }}
                </b-form-checkbox>
              </b-card-header>

              <b-card-body>
                <div class="business-items">
                  <div
                      v-for="child in item"
                      :key="`item-${child.id}`"
                      class="business-item"
                  >
                    <div class="d-flex align-items-center justify-content-between">
                      <b-form-checkbox
                          v-model="selected"
                          :key="child.id"
                          :value="child.id"
                      >
                        {{ child.name }}
                      </b-form-checkbox>
                    </div>
                  </div>
                </div>
              </b-card-body>
            </b-card>
          </b-col>
        </b-row>
      </b-card>
      <b-row class="mb-3">
        <b-col class="d-flex flex-row-reverse">
          <b-button
              variant="outline-secondary">
            {{ $t('Cancel') }}
          </b-button>

          <b-button
              class="mr-1"
              variant="primary"
              type="submit"
              :disabled="processing"
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          >
            <b-spinner v-if="processing" small type="grow"/>

            {{ $t('Save') }}
          </b-button>
        </b-col>
      </b-row>
    </b-form>
  </validation-observer>
</template>

<script>

import Ripple from 'vue-ripple-directive'
import {SET_BREADCRUMB} from "@/store/breadcrumbs.store";
import Mobile from "@/views/components/Mobile";
import {getPermissions, checkPermissionsSelection} from "@core/comp-functions/roles";

export default {
  components: {
    Mobile
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      form: {
        name: null,
        permissions: []
      },
      id: this.$route.params.id,
      processing: false,
    }
  },
  methods: {
    submit() {
      this.processing = true;

      this.$refs.form.validate().then((success) => {
        if (success) {
          this.$http[this.id ? 'put' : 'post']('/api/roles' + (this.id ? `/${this.id}` : ''), this.form).then(({data}) => {
            this.softToast('success', this.$t('Success'), data.message, 'CheckIcon')
            this.$router.push({name: 'roles.index'});
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
  created() {
    if (this.id) {
      this.$http.get(`/api/roles/${this.id}`).then(response => {
        const data = response.data.data;
        this.selected = data.permissions.map(x => x.id);
        this.form = {
          name: data.name,
          permissions: this.selected
        };
      }).catch(this.handleResponseError);
    }
  },
  mounted() {
    this.$store.dispatch(SET_BREADCRUMB, [
      {text: this.$t('modules.roles.roles'), to: {name: 'roles.index'}},
      {text: this.id ? this.$t('modules.roles.edit') : this.$t('modules.roles.create'), active: true}
    ]);
  },
  watch: {
    selected: function ($val) {
      this.form.permissions = $val;
      this.checkAllSelected(this.permissions);
    }
  },
  setup() {
    const {groupedPermissions} = getPermissions();

    return {
      ...checkPermissionsSelection(),

      // ...methods

      // ...computed
      permissions: groupedPermissions

      // ...data
    }
  }
}
</script>