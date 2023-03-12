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
                id="full-name"
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
                id="full-name"
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
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                v-model="filters.role_id.value"
                :options="roleOptions"
                class="w-100"
            />
          </b-col>

          <b-col
              cols="12"
              md="4"
              class="mt-2">
            <label>Status</label>
            <v-select
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                v-model="filters.status.value"
                :options="statusOptions"
                class="w-100"
            />
          </b-col>
        </b-row>
      </b-card-body>
    </b-card>

    <list-table api-path="/api/users" :filters="filters" :columns="columns" :actions="actions">
      <template #cell(name)="data">
        <b-media vertical-align="center">
          <template #aside>
            <b-avatar
                size="32"
                :src="data.item.image"
                :text="avatarText(data.item.name)"
                :to="{ name: 'users.index', params: { id: data.item.id } }"
            />
          </template>

          <b-link
              :to="{ name: 'users.index', params: { id: data.item.id } }"
              class="font-weight-bold d-block text-nowrap"
          >
            {{ data.item.name }}
          </b-link>
          <small class="text-muted">@{{ data.item.username }}</small>
        </b-media>
      </template>

    </list-table>

  </div>
</template>

<script>
import {avatarText} from '@core/utils/filter'
import ListTable from '../../components/ListTable';
import {SET_BREADCRUMB} from "@/store/breadcrumbs.store";

export default {
  components: {
    ListTable,
  },
  data() {
    return {
      actions: {
        view: true,
        create: true,
        update: true,
        delete: true,
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
            return this.value?.value;
          }
        },
        status: {
          value: null,
          format() {
            return this.value?.value;
          }
        },
      }
    };
  },
  computed: {
    columns() {
      return [
        {key: 'id', label: 'ID', sortable: true},
        {key: 'name', label: 'User', sortable: true},
        {key: 'email', label: 'Email', sortable: true},
        {key: 'created_at', label: 'Created At', sortable: true},
        {key: 'actions'},
      ];
    },
    roleOptions() {
      return [
        {label: 'Admin', value: 'admin'},
        {label: 'Author', value: 'author'},
        {label: 'Editor', value: 'editor'},
        {label: 'Maintainer', value: 'maintainer'},
        {label: 'Subscriber', value: 'subscriber'},
      ];
    },
    statusOptions() {
      return [
        {label: 'Pending', value: 'pending'},
        {label: 'Active', value: 'active'},
        {label: 'Inactive', value: 'inactive'},
      ];
    }
  },
  mounted() {
    this.$store.dispatch(SET_BREADCRUMB, [
      {text: this.$t('modules.users.users'), active: true}
    ]);
  },
  setup() {
    const resolveUserStatusVariant = status => {
      if (status === 'pending') return 'warning'
      if (status === 'active') return 'success'
      if (status === 'inactive') return 'secondary'
      return 'primary'
    }

    return {
      // Filter
      avatarText,

      // UI
      resolveUserStatusVariant,
    }
  },
}
</script>

<style lang="scss" scoped>
.per-page-selector {
  width: 90px;
}
</style>
