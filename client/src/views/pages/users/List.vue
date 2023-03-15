<template>
  <div>
    <b-card no-body>
      <b-card-header class="pb-50">
        <h5>Filters</h5>
      </b-card-header>
      <b-card-body>
        <b-row>
          <b-col cols="12"
                 md="4"
                 class="mt-2">
            <label>Name</label>
            <b-form-input
                v-model="filters.name.value"
                trim
                class="w-100"
            />
          </b-col>

          <b-col cols="12"
                 md="4"
                 class="mt-2">
            <label>Email</label>
            <b-form-input
                v-model="filters.email.value"
                trim
                class="w-100"
            />
          </b-col>

          <b-col
              cols="12"
              md="4"
              class="mt-2">
            <label>Role</label>
            <v-select
                :dir="appDir"
                v-model="filters.role_id.value"
                :options="roles"
                label="name"
                class="w-100"
            />
          </b-col>

          <b-col
              cols="12"
              md="4"
              class="mt-2">
            <label>Status</label>
            <v-select
                :dir="appDir"
                v-model="filters.status.value"
                :options="$t('active_status_list')"
                class="w-100"
            />
          </b-col>

          <b-col
              cols="12"
              md="4"
              class="mt-2">
            <label for="from_date">{{ $t('From Date') }}</label>
            <b-form-datepicker
                id="from_date"
                :placeholder="$t('Choose a date')"
                :locale="appLocale"
                :direction="appDir"
                v-model="filters.from_date.value"
                :dropup="false"
                no-flip
                class="w-100"
            />
          </b-col>

          <b-col
              cols="12"
              md="4"
              class="mt-2">
            <label for="to_date">{{ $t('To Date') }}</label>
            <b-form-datepicker
                id="to_date"
                :placeholder="$t('Choose a date')"
                :locale="appLocale"
                :direction="appDir"
                v-model="filters.to_date.value"
                :dropup="false"
                no-flip
                class="w-100"
            />
          </b-col>
        </b-row>
      </b-card-body>
    </b-card>

    <list-table resource="users" api-path="/api/users" :filters="filters" :columns="columns" :actions="actions">
      <template #cell(name)="data">
        <b-media vertical-align="center">
          <template #aside>
            <b-avatar
                size="32"
                :src="data.item.image"
                :text="avatarText(data.item.name)"
                :to="{ name: 'users.edit', params: { id: data.item.id } }"
            />
          </template>

          <b-link
              :to="{ name: 'users.edit', params: { id: data.item.id } }"
              class="font-weight-bold d-block text-nowrap"
          >
            {{ data.item.name }}
          </b-link>
          <small class="text-muted">@{{ data.item.username }}</small>
        </b-media>
      </template>
      <template #cell(status)="data">
        <b-badge
            pill
            :variant="`light-${resolveStatusVariant(data.item.status)}`"
            class="text-capitalize"
        >
          {{ data.item.status_value }}
        </b-badge>
      </template>

      <template #custom_dropdown_actions="{ item }">
        <b-dropdown-item v-if="item.actions.can_reset_password" @click="openResetPasswordModal(item)">
          <feather-icon icon="UnlockIcon"/>
          <span class="align-middle ml-50">{{ $t('Reset Password') }}</span>
        </b-dropdown-item>
      </template>

    </list-table>

    <reset-password-modal v-model="showResetPasswordModal" :user.sync="currentRow"></reset-password-modal>
  </div>
</template>

<script>
import {avatarText} from '@core/utils/filter'
import ListTable from '../../components/ListTable';
import ResetPasswordModal from '../../components/modals/ResetPasswordModal';
import {SET_BREADCRUMB} from "@/store/breadcrumbs.store";
import {resolveStatusVariant} from "@core/mixins/helpers";
import {getRoles} from "@core/comp-functions/roles";

export default {
  components: {
    ListTable,
    ResetPasswordModal
  },
  data() {
    return {
      actions: {
        view: this.$ability.can('view', 'user'),
        create: this.$ability.can('create', 'user'),
        update: this.$ability.can('update', 'user'),
        delete: this.$ability.can('delete', 'user'),
      },
      filters: {
        name: {
          value: null
        },
        email: {
          value: null
        },
        role_id: {
          value: null,
          format() {
            return this.value?.id;
          }
        },
        status: {
          value: null,
          format() {
            return this.value?.value;
          }
        },
        from_date: {
          value: null
        },
        to_date: {
          value: null
        },
      },
      showResetPasswordModal: false,
      currentRow: null,
    };
  },
  computed: {
    columns() {
      return [
        {key: 'id', label: 'ID', sortable: true},
        {key: 'name', label: 'User', sortable: true},
        {key: 'email', label: 'Email', sortable: true},
        {key: 'status', label: 'Status', sortable: true},
        {key: 'created_at', label: 'Created At', sortable: true},
        {key: 'actions'},
      ];
    },
  },
  mounted() {
    this.$store.dispatch(SET_BREADCRUMB, [
      {text: this.$t('modules.users.users'), active: true}
    ]);
  },
  methods: {
    openResetPasswordModal(row) {
      this.showResetPasswordModal = true;
      this.currentRow = row;
    }
  },
  setup() {
    const {roles} = getRoles();

    return {
      roles,

      // Filter
      avatarText,

      // UI
      resolveStatusVariant,
    }
  },
}
</script>

<style lang="scss" scoped>
.per-page-selector {
  width: 90px;
}
</style>
