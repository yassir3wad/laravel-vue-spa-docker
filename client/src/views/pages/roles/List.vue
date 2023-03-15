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

    <list-table resource="roles" api-path="/api/roles" :sort="{column: 'id', direction: 'asc'}" :filters="filters"
                :columns="columns" :actions="actions">
      <template #cell(permissions_count)="data">
        <b-progress
            :max="totalPermissions"
            striped
            animated
            :class="`progress-bar-${getRoleStrengthVariant((data.item.permissions_count / totalPermissions) * 100)}`"
        >
          <b-progress-bar
              :value="data.item.permissions_count"
              :label="`${parseInt((data.item.permissions_count / totalPermissions) * 100)}%`"
              :variant="getRoleStrengthVariant((data.item.permissions_count / totalPermissions) * 100)"
          />
        </b-progress>
      </template>
    </list-table>

  </div>
</template>

<script>
import ListTable from '../../components/ListTable';
import {SET_BREADCRUMB} from "@/store/breadcrumbs.store";
import {getPermissions, getRoleStrengthVariant} from "@core/comp-functions/roles";

export default {
  components: {
    ListTable,
  },
  data() {
    return {
      actions: {
        view: this.$ability.can('view', 'role'),
        create: this.$ability.can('create', 'role'),
        update: this.$ability.can('update', 'role'),
        delete: this.$ability.can('delete', 'role'),
      },
      filters: {
        name: {
          value: null
        },
        from_date: {
          value: null
        },
        to_date: {
          value: null
        },
      },
    };
  },
  computed: {
    columns() {
      return [
        {key: 'id', label: 'ID', sortable: true},
        {key: 'name', label: 'Name', sortable: true},
        {key: 'permissions_count', label: 'Strength', sortable: false},
        {key: 'created_at', label: 'Created At', sortable: true},
        {key: 'actions'},
      ];
    }
  },
  mounted() {
    this.$store.dispatch(SET_BREADCRUMB, [
      {text: this.$t('modules.roles.roles'), active: true}
    ]);
  },
  setup() {
    const {totalPermissions} = getPermissions();

    return {
      // ...methods
      getRoleStrengthVariant,

      // ...computed
      totalPermissions,

      // ...data
    }
  }
}
</script>