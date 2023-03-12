<template>
  <b-card no-body class="mb-0">
    <div class="m-2">
      <!-- Table Top -->
      <b-row>
        <!-- Per Page -->
        <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
          <label>Show</label>
          <v-select
              v-model="pagination.perPage"
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              :options="pagination.perPageOptions"
              :clearable="false"
              class="per-page-selector d-inline-block mx-50"
          />
          <label>entries</label>
        </b-col>

        <!-- Search -->
        <b-col cols="12" md="6" v-if="actions.create">
          <div class="d-flex align-items-center justify-content-end">
            <b-button variant="primary" @click="$router.push({name: 'users.create'})">
              <span class="text-nowrap">Add User</span>
            </b-button>
          </div>
        </b-col>
      </b-row>

    </div>
    <b-table
        :busy="processing"
        ref="table"
        class="position-relative"
        :items="fetchData"
        responsive
        :fields="columns"
        primary-key="id"
        show-empty
        empty-text="No matching records found"
        striped
        :sort-by.sync="internalSort.column"
        :sort-desc.sync="internalSort.isSortDirDesc"
    >

      <template v-for="(_, name) in $scopedSlots" #[name]="data">
        <slot :name="name" v-bind="data"/>
      </template>

      <template #table-busy>
        <div class="text-center text-default my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong class="ml-1">Loading...</strong>
        </div>
      </template>

      <!-- Column: Actions -->
      <template #cell(actions)="data">
        <div class="text-nowrap">
          <span v-if="actions.view" class="mx-8px">
            <feather-icon
                :id="`row-${data.item.id}-view-icon`"
                icon="EyeIcon"
                size="16"
                class="cursor-pointer"
                @click="$router.push({ name: `${resource}.view`, params: { id: data.item.id } })"
            />
            <b-tooltip
                title="Details"
                class="cursor-pointer"
                :target="`row-${data.item.id}-view-icon`"
            />
          </span>

          <span v-if="actions.view" class="mx-8px">
            <feather-icon
                :id="`row-${data.item.id}-edit-icon`"
                icon="EditIcon"
                size="16"
                class="cursor-pointer"
                @click="$router.push({ name: `${resource}.edit`, params: { id: data.item.id } })"
            />
            <b-tooltip
                title="Edit"
                class="cursor-pointer"
                :target="`row-${data.item.id}-edit-icon`"
            />
          </span>

          <b-dropdown
              variant="link"
              no-caret
              :right="$store.state.appConfig.isRTL"
              toggle-class="p-0"
          >
            <template #button-content>
              <feather-icon
                  icon="MoreVerticalIcon"
                  size="16"
                  class="align-middle text-body"
              />
            </template>

            <b-dropdown-item v-if="actions.delete" @click="openDeleteModal(data.item)">
              <feather-icon icon="TrashIcon"/>
              <span class="align-middle ml-50">Delete</span>
            </b-dropdown-item>
          </b-dropdown>
        </div>
      </template>

    </b-table>
    <div class="mx-2 mb-2">
      <b-row>
        <b-col cols="12" sm="6" class="d-flex align-items-center justify-content-center justify-content-sm-start">
            <span class="text-muted">Showing {{ pagination.from }} to {{ pagination.to }} of {{
                pagination.total
              }} entries</span>
        </b-col>
        <!-- Pagination -->
        <b-col
            cols="12"
            sm="6"
            class="d-flex align-items-center justify-content-center justify-content-sm-end"
        >

          <b-pagination
              v-model="pagination.page"
              :total-rows="pagination.total"
              :per-page="pagination.perPage"
              first-number
              last-number
              class="mb-0 mt-1 mt-sm-0"
              prev-class="prev-item"
              next-class="next-item"
          >
            <template #prev-text>
              <feather-icon
                  icon="ChevronLeftIcon"
                  size="18"
              />
            </template>
            <template #next-text>
              <feather-icon icon="ChevronRightIcon" size="18"/>
            </template>
          </b-pagination>
        </b-col>
      </b-row>
    </div>

    <b-modal
        ref="delete-confirm-modal"
        title="Please Confirm"
        button-size="sm"
        ok-title="Delete"
        cancel-title="Cancel"
        cancel-variant="outline-secondary"
        ok-variant="outline-danger"
        @hidden="() => currentRow = modalProcessing = null"
        @ok="handleConfirmDeleteModal"
    >
      <template #modal-ok>
        <b-spinner v-if="modalProcessing" small type="grow"/>
        Delete
      </template>
      <b-card-text>Please confirm that you want to delete everything.</b-card-text>
    </b-modal>
  </b-card>
</template>

<script>

import {debounce} from 'lodash';

export default {
  name: "list-table",
  props: {
    columns: {
      type: Array,
      required: true
    },
    resource: {
      type: String,
      required: true
    },
    sort: {
      type: Object,
      default() {
        return {column: 'id', direction: 'desc'};
      }
    },
    apiPath: {
      type: String,
      required: true
    },
    filters: {
      type: Object,
      default() {
        return {};
      }
    },
    actions: {
      type: Object,
      default() {
        return {
          view: false,
          create: false,
          update: false,
          delete: false,
        };
      }
    }
  },
  data() {
    return {
      processing: false,
      modalProcessing: false,
      currentRow: false,
      internalSort: {
        column: 'created_at',
        isSortDirDesc: true
      },
      pagination: {
        page: 1,
        perPageOptions: [10, 25, 50, 100],
        perPage: 10,
        total: 0,
        count: 0,
        from: 0,
        to: 0,
      },
    }
  },
  methods: {
    fetchData(ctx, callback) {
      if (this.processing === true) return false;
      this.processing = true;
      this.$http.get(this.getApiUrl()).then(({data}) => {
        this.pagination.total = data.meta.total;
        this.pagination.count = data.data.length;
        this.pagination.from = data.meta.from;
        this.pagination.to = data.meta.to;
        callback(data.data)
      }).catch(error => {
        this.toast('danger', this.$t("Error"), error.response.data.message)
        callback([])
      }).finally(() => {
        this.processing = false
      });
    },
    refresh() {
      this.$refs.table.refresh();
    },
    getApiUrl() {
      let api = this.apiPath;

      if (!api.includes('?')) {
        api += '?';
      } else {
        api += '&';
      }

      api += `page=${this.pagination.page}&per_page=${this.pagination.perPage}`;

      Object.entries(this.filters).forEach(val => {
        const value = typeof val[1].format === 'function' ? val[1].format() : val[1].value;
        if (value !== null && value !== undefined) {
          api += `&${val[0]}=${value}`;
        }
      });

      if (this.internalSort.column) {
        api += `&sort=${this.internalSort.column}&sort_dir=${this.internalSort.isSortDirDesc === true ? 'desc' : 'asc'}`
      }

      return api;
    },
    openDeleteModal(row) {
      this.currentRow = row;
      this.$refs['delete-confirm-modal'].show();
    },
    handleConfirmDeleteModal(e) {
      e.preventDefault();
      this.modalProcessing = true;
      this.$http.delete(`${this.apiPath}/${this.currentRow.id}`).then(({data}) => {
        this.refresh();
        this.toast('success', 'Success', data.message);
        this.$refs['delete-confirm-modal'].hide();
      }).catch(error => this.handleResponseError(error)).finally(() => this.modalProcessing = false);
    },
  },
  watch: {
    filters: {
      deep: true,
      handler: debounce(function () {
        this.refresh();
      }, 500)
    },
    'pagination.page': debounce(function () {
      this.refresh();
    }, 500),
    'pagination.perPage': debounce(function () {
      this.refresh();
    }, 500),
    sort: {
      deep: true,
      handler: (newVal) => {
        this.internalSort.column = newVal.column;
        this.internalSort.isSortDirDesc = newVal.direction === 'desc';
      }
    },
  },
  computed: {},
  mounted() {
  }
}
</script>

<style scoped>
.mx-8px {
  margin-left: 5px;
  margin-right: 5px;
}
</style>