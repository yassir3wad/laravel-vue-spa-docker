<template>
  <b-tabs
      vertical
      content-class="col-12 col-md-9 mt-1 mt-md-0"
      pills
      nav-wrapper-class="col-md-3 col-12"
      nav-class="nav-left"
  >

    <!-- general tab -->
    <b-tab active>
      <!-- title -->
      <template #title>
        <feather-icon
            icon="UserIcon"
            size="18"
            class="mr-50"
        />
        <span class="font-weight-bold">General</span>
      </template>

      <account-setting-general v-if="user"
                               :general-data="user"
      />
    </b-tab>
    <!--/ general tab -->

    <!-- change password tab -->
    <b-tab>

      <!-- title -->
      <template #title>
        <feather-icon
            icon="LockIcon"
            size="18"
            class="mr-50"
        />
        <span class="font-weight-bold">Change Password</span>
      </template>

      <account-setting-password/>
    </b-tab>
    <!--/ change password tab -->

    <!-- info -->
    <b-tab>

      <!-- title -->
      <template #title>
        <feather-icon
            icon="InfoIcon"
            size="18"
            class="mr-50"
        />
        <span class="font-weight-bold">Information</span>
      </template>

      <account-setting-information
          v-if="user"
          :information-data="user"
      />
    </b-tab>
  </b-tabs>
</template>

<script>
import AccountSettingGeneral from './AccountSettingGeneral.vue'
import AccountSettingPassword from './AccountSettingPassword.vue'
import AccountSettingInformation from './AccountSettingInformation.vue'
import {mapGetters} from 'vuex';
import {SET_BREADCRUMB} from "@/store/breadcrumbs.store";

export default {
  components: {
    AccountSettingGeneral,
    AccountSettingPassword,
    AccountSettingInformation,
  },
  computed: {
    ...mapGetters({
      user: 'auth/user'
    })
  },
  mounted() {
    this.$store.dispatch(SET_BREADCRUMB, [
      {text: this.$t('Profile'), active: true}
    ]);
  }
}
</script>
